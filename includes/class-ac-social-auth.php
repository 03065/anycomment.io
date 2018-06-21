<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('AC_SocialAuth')) :
    /**
     * AC_SocialAuth helps to process website authentication.
     */
    class AC_SocialAuth
    {
        /**
         * Different social types.
         * Used for routing, images, etc.
         */
        const SOCIAL_VKONTAKTE = 'vkontakte';
        const SOCIAL_FACEBOOK = 'facebook';
        const SOCIAL_TWITTER = 'twitter';
        const SOCIAL_GOOGLE = 'google';
        const SOCIAL_ODNOKLASSNIKI = 'odnoklassniki';

        const META_SOCIAL_TYPE = 'anycomment_social';
        const META_SOCIAL_AVATAR = 'anycomment_social_avatar';

        /**
         * @var \Hybridauth\Hybridauth
         */
        private $auth;

        protected static $rest_prefix = 'anycomment';
        protected static $rest_version = 'v1';

        /**
         * AC_SocialAuth constructor.
         */
        public function __construct()
        {
            $this->init_rest_route();
        }

        /**
         * Get REST namespace.
         * @return string
         */
        private static function get_rest_namespace()
        {
            return sprintf('%s/%s', static::$rest_prefix, static::$rest_version);
        }

        /**
         * Used to initiate REST routes to log in client, etc.
         */
        private function init_rest_route()
        {
            add_action('rest_api_init', function () {
                $namespace = static::get_rest_namespace();
                $route = '/auth/(?P<social>\w[\w\s]*)/';
                register_rest_route($namespace, $route, [
                    'methods' => 'GET',
                    'callback' => [$this, 'process_socials'],
                ]);
            });
        }

        /**
         * Main method to process social-like request to authentication, etc.
         * @param WP_REST_Request $request
         * @return mixed
         */
        public function process_socials(WP_REST_Request $request)
        {
            $redirect = $request->get_param('redirect');
            $social = $request->get_param('social');
            $cookie_redirect = 'post_redirect';

            if (!static::social_exists($social) || is_user_logged_in()) {
                wp_redirect($redirect);
                exit();
            }

            if ($redirect !== null) {
                setcookie($cookie_redirect, $redirect, time() + 3600);
            } else {
                $redirect = isset($_COOKIE[$cookie_redirect]) ? $_COOKIE[$cookie_redirect] : '/';
            }

            try {
                $adapter = $this->prepare_auth()
                    ->authenticate(ucfirst($social));
            } catch (\Throwable $exception) {
                wp_redirect($redirect);
                exit();
            }

            $tokens = $adapter->getAccessToken();

            $user = $adapter->getUserProfile();


            if (!$user instanceof \Hybridauth\User\Profile) {
                wp_redirect($redirect);
                exit();
            }


            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }

            $this->process_authentication($user, $social);

            if (session_status() == PHP_SESSION_ACTIVE) {
                session_destroy();
            }

            wp_redirect($redirect);
            exit();
        }

        /**
         * Check whether social exists or not.
         *
         * @param string $social Social name. Example: vkontakte. Name will be uppercased.
         * @return bool
         */
        public static function social_exists($social)
        {
            return constant(sprintf('self::SOCIAL_%s', strtoupper($social))) !== null;
        }

        /**
         * Prepare authentication.
         * @throws \Hybridauth\Exception\InvalidArgumentException on failure.
         * @return \Hybridauth\Hybridauth
         */
        private function prepare_auth()
        {
            $config = [
                'providers' => [
                    self::SOCIAL_VKONTAKTE => [
                        'enabled' => AC_SocialSettings::isVkOn(),
                        'keys' => ['id' => AC_SocialSettings::getVkAppId(), 'secret' => AC_SocialSettings::getVkSecureKey()],
                        'callback' => static::get_vk_callback(),
                    ],
                    self::SOCIAL_GOOGLE => [
                        'enabled' => AC_SocialSettings::isGoogleOn(),
                        'keys' => ['id' => AC_SocialSettings::getGoogleClientId(), 'secret' => AC_SocialSettings::getGoogleSecret()],
                        'scope' => 'profile https://www.googleapis.com/auth/plus.login https://www.googleapis.com/auth/plus.profile.emails.read',
                        'callback' => static::get_google_callback(),
                    ],
                    self::SOCIAL_FACEBOOK => [
                        'enabled' => AC_SocialSettings::isFbOn(),
                        'scope' => 'email, public_profile',
                        'keys' => ['id' => AC_SocialSettings::getFbAppId(), 'secret' => AC_SocialSettings::getFbAppSecret()],
                        'callback' => static::get_facebook_callback(),
                    ],
                    self::SOCIAL_TWITTER => [
                        'enabled' => AC_SocialSettings::isTwitterOn(),
                        'keys' => ['key' => AC_SocialSettings::getTwitterConsumerKey(), 'secret' => AC_SocialSettings::getTwitterConsumerSecret()],
                        'callback' => static::get_twitter_callback(),
                    ]
                ],
            ];

            $this->auth = new \Hybridauth\Hybridauth($config);

            return $this->auth;
        }

        /**
         * Get vk callback URL.
         * @param null|string $redirect Redirect URL added to the link.
         * @return string
         */
        public static function get_vk_callback($redirect = null)
        {
            return static::get_callback_url(self::SOCIAL_VKONTAKTE, $redirect);
        }

        /**
         * Get Google callback URL.
         * @param null|string $redirect Redirect URL added to the link.
         * @return string
         */
        public static function get_google_callback($redirect = null)
        {
            return static::get_callback_url(self::SOCIAL_GOOGLE, $redirect);
        }

        /**
         * Get Facebook callback URL.
         * @param null|string $redirect Redirect URL added to the link.
         * @return string
         */
        public static function get_facebook_callback($redirect = null)
        {
            return static::get_callback_url(self::SOCIAL_FACEBOOK, $redirect);
        }

        /**
         * Get Twitter callback URL.
         * @param null|string $redirect Redirect URL added to the link.
         * @return string
         */
        public static function get_twitter_callback($redirect = null)
        {
            return static::get_callback_url(self::SOCIAL_TWITTER, $redirect);
        }

        /**
         * Get REST url + redirect url with it.
         * @param string $social_type URL type, e.g. vk
         * @param string|null $redirect Redirect URL where to send back user.
         * @return string
         */
        public static function get_callback_url($social_type, $redirect = null)
        {
            $url = static::get_rest_namespace() . "/auth/" . $social_type;

            if ($redirect !== null) {
                $url .= "?redirect=$redirect";
            }
            return rest_url($url);
        }

        /**
         * Process Google authorization.
         * @param \Hybridauth\User\Profile $user User to be processed.
         * @param string $social Social name.
         * @return bool False on failure.
         */
        private function process_authentication(Hybridauth\User\Profile $user, $social)
        {
            $email = isset($user->email) && !empty($user->email) ?
                $user->email :
                null;

            $userdata = [
                'user_login' => $user->identifier,
                'display_name' => $user->displayName,
                'user_nicename' => $user->firstName,
                'user_url' => $user->profileURL
            ];

            if ($email !== null) {
                $userdata['user_email'] = $email;
            }

            $searchBy = $email !== null ? 'email' : 'login';
            $searchValue = $email !== null ? $email : $userdata['user_login'];

            return $this->auth_user(
                $social,
                $searchBy,
                $searchValue,
                $userdata,
                ['anycomment_social_avatar' => $user->photoURL]
            );
        }

        /**
         * Authenticate user with additional check whether such
         * user already exists or not. When user does not exist,
         * it create it. When exists, uses existing information to authorize.
         *
         * @see AC_SocialAuth::get_user_by() for more information about $field and $value fields.
         *
         * @param string $social Social type. e.g. twitter.
         * @param string $field Field name to be used to check existence of such user.
         * @param string $value Value of the $field to be checked for.
         * @param array $user_data User data to be created for user, when does not exist.
         * @param array $user_meta User meta data to be create for user, when does not exist.
         * @return bool
         */
        public function auth_user($social, $field, $value, array $user_data, array $user_meta)
        {
            if ($field == 'login') {
                // Need to make username unique per social network, otherwise
                // some of the IDs might have collision at some point
                // format: social_login
                // login usually is ID
                $value = sprintf('%s_%s', $social, $value);
            }

            if (isset($user_data['user_login'])) {
                $user_data['user_login'] = sprintf('%s_%s', $social, $user_data['user_login']);
            }

            // Preset social type as it is passed anyways in the method
            if (!isset($user_meta[self::META_SOCIAL_TYPE])) {
                $user_meta[self::META_SOCIAL_TYPE] = $social;
            }

            $user = $this->get_user_by($social, $field, $value);

            if ($user === false) {
                $newUserId = $this->insert_user($user_data, $user_meta);

                if (!$newUserId) {
                    return false;
                }

                $userId = $newUserId;
            } else {
                $userId = $user->ID;
                $this->update_user_meta($user->ID, $user_meta);
            }

            wp_clear_auth_cookie();
            wp_set_current_user($userId);
            wp_set_auth_cookie($userId, true);

            return true;
        }


        /**
         * Check weather user exists.
         * @param string $social Social type. e.g. twitter.
         * @param string $field Field to check for user existence (e.g. username).
         * @param string $value Fields to check for value of $field.
         * @return false|WP_User false on failure.
         */
        public function get_user_by($social, $field, $value)
        {
            $user = get_user_by($field, $value);

            if ($user === false) {
                return false;
            }

            $social_from_meta = trim(get_user_meta($user->ID, self::META_SOCIAL_TYPE, true));
            $social = trim($social);

            if ($social_from_meta !== $social) {
                return false;
            }

            return $user;
        }

        /**
         * @param array $user_data See options below:
         * - user_login
         * - user_email
         * - display_name
         * - user_nicename
         * - user_url
         * @param array $user_meta User meta to be added when `$userdata` added successfully.
         * @return false|int false on failure, int on success.
         *
         * @see wp_generate_password() for how password is being generated.
         * @see wp_insert_user() for how `$userdata` param is being processed.
         * @see add_user_meta() for how `$usermeta` param is being processed.
         */
        public function insert_user($user_data, $user_meta)
        {
            if (!isset($user_data['userpass'])) {
                // Generate some random password for user
                $user_data['user_pass'] = wp_generate_password(12, true);
            }

            $newUserId = wp_insert_user($user_data);

            // If unable to create new user
            if ($newUserId instanceof WP_Error) {
                return false;
            }

            $metaInsertCount = 0;
            foreach ($user_meta as $key => $value) {
                if (add_user_meta($newUserId, $key, $value)) {
                    $metaInsertCount++;
                }
            }

            // If number of inserted metas is less then was requested to add
            if ($metaInsertCount < count($user_meta)) {
                return false;
            }

            return $newUserId;
        }

        /**
         * Update user meta by ID.
         *
         * @see update_user_meta() when meta does not exist, it will be added. See for more info.
         *
         * @param int $user_id User's ID.
         * @param array $usermeta List of user meta to be updated.
         * @return bool
         */
        public function update_user_meta($user_id, $usermeta)
        {
            if (!get_user_by('id', $user_id) || !is_array($usermeta) || !empty($usermeta)) {
                return false;
            }

            $metaUpdateCount = 0;
            foreach ($usermeta as $key => $value) {
                if (update_user_meta($user_id, $key, $value)) {
                    $metaUpdateCount++;
                }
            }

            // If number of inserted metas is less then was requested to add
            if ($metaUpdateCount < count($usermeta)) {
                return false;
            }

            return true;
        }

        /**
         * Get current user avatar URL.
         *
         * @return string|null NULL returned when user does not have any avatar.
         */
        public function get_active_user_avatar_url()
        {
            if (($user = wp_get_current_user()) instanceof WP_User) {

                return $this->get_user_avatar_url($user->ID);
            }

            return null;
        }

        /**
         * Get user social type.
         * @param int $user_id User ID to be checked social type for.
         * @return mixed
         */
        public static function get_user_social_type($user_id)
        {
            return get_user_meta($user_id, 'anycomment_social', true);
        }

        /**
         * Get user avatar by user id.
         *
         * @param int $user_id User ID to be searched for.
         * @return mixed|null
         */
        public function get_user_avatar_url($user_id)
        {
            // Integration with WP User Avatar:https://wordpress.org/plugins/wp-user-avatar/
            if (class_exists('WP_User_Avatar_Setup')) {
                global $wpua_functions;
                if ($wpua_functions->has_wp_user_avatar($user_id)) {
                    return $wpua_functions->get_wp_user_avatar_src($user_id, 50);
                }
            }

            $avatarUrl = get_user_meta($user_id, self::META_SOCIAL_AVATAR, true);

            if (!empty($avatarUrl)) {
                return $avatarUrl;
            }

            return AnyComment()->plugin_url() . '/assets/img/no-avatar.svg';
        }

        /**
         * Utility function to check if a gravatar exists for a given email or id
         * @param int|string|object $id_or_email A user ID,  email address, or comment object
         * @return bool if the gravatar exists or not
         */
        public function validate_gravatar($id_or_email)
        {
            //id or email code borrowed from wp-includes/pluggable.php
            $email = '';
            if (is_numeric($id_or_email)) {
                $id = (int)$id_or_email;
                $user = get_userdata($id);
                if ($user)
                    $email = $user->user_email;
            } elseif (is_object($id_or_email)) {
                // No avatar for pingbacks or trackbacks
                $allowed_comment_types = apply_filters('get_avatar_comment_types', array('comment'));
                if (!empty($id_or_email->comment_type) && !in_array($id_or_email->comment_type, (array)$allowed_comment_types))
                    return false;

                if (!empty($id_or_email->user_id)) {
                    $id = (int)$id_or_email->user_id;
                    $user = get_userdata($id);
                    if ($user)
                        $email = $user->user_email;
                } elseif (!empty($id_or_email->comment_author_email)) {
                    $email = $id_or_email->comment_author_email;
                }
            } else {
                $email = $id_or_email;
            }

            $hashkey = md5(strtolower(trim($email)));
            $uri = 'http://www.gravatar.com/avatar/' . $hashkey . '?d=404';

            $data = wp_cache_get($hashkey);
            if (false === $data) {
                $response = wp_remote_head($uri);
                if (is_wp_error($response)) {
                    $data = 'not200';
                } else {
                    $data = $response['response']['code'];
                }
                wp_cache_set($hashkey, $data, $group = '', $expire = 60 * 5);

            }
            if ($data == '200') {
                return true;
            } else {
                return false;
            }
        }
    }
endif;