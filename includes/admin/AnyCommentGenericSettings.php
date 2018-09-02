<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'AnyCommentGenericSettings' ) ) :
	/**
	 * AC_AdminSettingPage helps to process generic plugin settings.
	 */
	class AnyCommentGenericSettings extends AnyCommentAdminOptions {

		/**
		 * Theme chosen for comments.
		 */
		const OPTION_THEME = 'option_theme';

		/**
		 * Notify about new comment.
		 */
		const OPTION_NOTIFY_ON_NEW_COMMENT = 'option_notify_on_new_comment';

		/**
		 * Send email notification to users about new reply.
		 */
		const OPTION_NOTIFY_ON_NEW_REPLY = 'option_notify_on_new_reply';

		/**
		 * Notify administrator about new comment.
		 */
		const OPTION_NOTIFY_ADMINISTRATOR = 'option_notify_administrator';

		/**
		 * Checkbox whether plugin is active or not. Can be used to set-up API keys, etc,
		 * before plugin is ready to be shown to users.
		 */
		const OPTION_PLUGIN_TOGGLE = 'option_plugin_toggle';

		/**
		 * Default user group on register.
		 */
		const OPTION_REGISTER_DEFAULT_GROUP = 'option_register_default_group';

		/**
		 * Interval, expressed in seconds per which check new comments.
		 * When OPTION_NOTIFY_ON_NEW_COMMENT is not enabled, this constant not used.
		 */
		const OPTION_INTERVAL_COMMENTS_CHECK = 'option_interval_comment_check';

		/**
		 * Number of comments displayed per page and on the page load.
		 */
		const OPTION_COUNT_PER_PAGE = 'option_comments_count_per_page';

		/**
		 * Link to the user agreement.
		 */
		const OPTION_USER_AGREEMENT_LINK = 'option_comments_user_agreement_link';

		/**
		 * Show/hide copyright.
		 */
		const OPTION_COPYRIGHT_TOGGLE = 'option_copyright_toggle';

		/**
		 * Load comments on scroll to it.
		 */
		const OPTION_LOAD_ON_SCROLL = 'options_load_on_scroll';

		/**
		 * Mark comments for moderation before they are added.
		 */
		const OPTION_MODERATE_FIRST = 'options_moderate_first';

		/**
		 * List of words to mark comments as spam.
		 */
		const OPTION_MODERATE_WORDS = 'options_moderate_words';

		/**
		 * Show/hide profile URL on client mini social icon.
		 */
		const OPTION_SHOW_PROFILE_URL = 'options_show_profile_url';

		/**
		 * Show/hide video attachments.
		 */
		const OPTION_SHOW_VIDEO_ATTACHMENTS = 'options_show_video_attachments';

		/**
		 * Show/hide image attachments.
		 */
		const OPTION_SHOW_IMAGE_ATTACHMENTS = 'options_show_image_attachments';

		/**
		 * Whether required to make links clickable.
		 */
		const OPTION_MAKE_LINKS_CLICKABLE = 'options_make_links_clickable';

		/**
		 * Define form type: only guest users, only social networks or both of it.
		 */
		const OPTION_FORM_TYPE = 'options_form_type';

		/**
		 * FORM TYPES
		 */

		/**
		 * Option to enable comments only from guest.
		 */
		const FORM_OPTION_GUEST_ONLY = 'form_option_guest_only';

		/**
		 * Option to allow comments from users who authorized using social.
		 */
		const FORM_OPTION_SOCIALS_ONLY = 'form_option_socials_only';

		/**
		 * Option to allow both: guest & social login.
		 */
		const FORM_OPTION_ALL = 'form_option_all';


		/**
		 * FILES UPLOAD
		 */
		const OPTION_FILES_GUEST_CAN_UPLOAD = 'options_files_guest_can_upload';
		const OPTION_FILES_MIME_TYPES = 'options_files_mime_types';
		const OPTION_FILES_LIMIT = 'options_files_limit';
		const OPTION_FILES_LIMIT_PERIOD = 'options_files_limit_period';
		const OPTION_FILES_MAX_SIZE = 'options_files_max_size';

		/**
		 * DESIGN
		 */
		const OPTION_DESIGN_CUSTOM_TOGGLE = 'options_design_custom_toggle';

		const OPTION_DESIGN_FONT_SIZE = 'options_design_font_size';
		const OPTION_DESIGN_FONT_FAMILY = 'options_design_font_family';

		const OPTION_DESIGN_SEMI_HIDDEN_COLOR = 'options_design_semi_hidden_color';
		const OPTION_DESIGN_LINK_COLOR = 'options_design_link_color';
		const OPTION_DESIGN_TEXT_COLOR = 'options_design_text_color';

		const OPTION_DESIGN_FORM_FIELD_BACKGROUND_COLOR = 'options_design_form_field_background_color';

		const OPTION_DESIGN_ATTACHMENT_COLOR = 'options_design_attachment_color';
		const OPTION_DESIGN_ATTACHMENT_BACKGROUND_COLOR = 'options_design_attachment_background_color';

		const OPTION_DESIGN_PARENT_AVATAR_SIZE = 'options_design_parent_avatar_size';
		const OPTION_DESIGN_CHILD_AVATAR_SIZE = 'options_design_child_avatar_size';

		const OPTION_DESIGN_BUTTON_COLOR = 'options_design_button_color';
		const OPTION_DESIGN_BUTTON_BACKGROUND_COLOR = 'options_design_button_background_color';
		const OPTION_DESIGN_BUTTON_BACKGROUND_COLOR_ACTIVE = 'options_design_button_background_color_active';
		const OPTION_DESIGN_BUTTON_RADIUS = 'options_design_button_radius';

		const OPTION_DESIGN_GLOBAL_RADIUS = 'options_design_global_radius';

		/**
		 * THEMES
		 */

		/**
		 * Dark theme.
		 */
		const THEME_DARK = 'dark';

		/**
		 * Light theme.
		 */
		const THEME_LIGHT = 'light';

		/**
		 * Normal subscriber (from WordPress)
		 */
		const DEFAULT_ROLE_SUBSCRIBER = 'subscriber';

		/**
		 * Custom social subscriber. Role introduced via this plugin.
		 */
		const DEFAULT_ROLE_SOCIAL_SUBSCRIBER = 'social_subscriber';

		/**
		 * @inheritdoc
		 */
		protected $option_group = 'anycomment-generic-group';
		/**
		 * @inheritdoc
		 */
		protected $option_name = 'anycomment-generic';
		/**
		 * @inheritdoc
		 */
		protected $page_slug = 'anycomment-settings';

		/**
		 * @inheritdoc
		 */
		protected $default_options = [
			self::OPTION_THEME                   => self::THEME_LIGHT,
			self::OPTION_COPYRIGHT_TOGGLE        => 'on',
			self::OPTION_COUNT_PER_PAGE          => 20,
			self::OPTION_INTERVAL_COMMENTS_CHECK => 10,
			self::OPTION_FORM_TYPE               => self::FORM_OPTION_SOCIALS_ONLY,

			// Files
			self::OPTION_FILES_LIMIT             => 5,
			self::OPTION_FILES_LIMIT_PERIOD      => 300,
			self::OPTION_FILES_MAX_SIZE          => 1.5,
			self::OPTION_FILES_MIME_TYPES        => 'image/*, .pdf',

			// Design
			self::OPTION_DESIGN_FONT_SIZE        => '15px',
			self::OPTION_DESIGN_FONT_FAMILY      => "'Noto-Sans', sans-serif",

			self::OPTION_DESIGN_SEMI_HIDDEN_COLOR => '#b6c1c6',
			self::OPTION_DESIGN_LINK_COLOR        => '#3658f7',
			self::OPTION_DESIGN_TEXT_COLOR        => '#333333',

			self::OPTION_DESIGN_FORM_FIELD_BACKGROUND_COLOR => '#ffffff',

			self::OPTION_DESIGN_ATTACHMENT_COLOR            => '#eeeeee',
			self::OPTION_DESIGN_ATTACHMENT_BACKGROUND_COLOR => '#eeeeee',

			self::OPTION_DESIGN_PARENT_AVATAR_SIZE => '60px',
			self::OPTION_DESIGN_CHILD_AVATAR_SIZE  => '48px',

			self::OPTION_DESIGN_BUTTON_COLOR                   => '#ffffff',
			self::OPTION_DESIGN_BUTTON_BACKGROUND_COLOR        => '#53af4a',
			self::OPTION_DESIGN_BUTTON_BACKGROUND_COLOR_ACTIVE => '#4f9f49',
			self::OPTION_DESIGN_BUTTON_RADIUS                  => '40px',

			self::OPTION_DESIGN_GLOBAL_RADIUS => '4px',
		];


		/**
		 * AnyCommentAdminPages constructor.
		 *
		 * @param bool $init if required to init the modle.
		 */
		public function __construct( $init = true ) {
			parent::__construct();
			if ( $init ) {
				$this->init_hooks();
			}
		}

		/**
		 * Initiate hooks.
		 */
		private function init_hooks() {
			add_action( 'admin_init', [ $this, 'init_settings' ] );

			// Create role
			add_role(
				AnyCommentGenericSettings::DEFAULT_ROLE_SOCIAL_SUBSCRIBER,
				__( 'Social Network Subscriber', 'anycomment' ),
				[
					'read'         => true,
					'edit_posts'   => false,
					'delete_posts' => false,
				]
			);
		}

		/**
		 * {@inheritdoc}
		 */
		public function init_settings() {
			add_settings_section(
				'section_generic',
				__( 'Generic', "anycomment" ),
				null,
				$this->page_slug
			);

			add_settings_section(
				'section_design',
				__( 'Design', "anycomment" ),
				null,
				$this->page_slug
			);

			add_settings_section(
				'section_moderation',
				__( 'Moderation', "anycomment" ),
				null,
				$this->page_slug
			);

			add_settings_section(
				'section_notifications',
				__( 'Notifications', "anycomment" ),
				null,
				$this->page_slug
			);

			add_settings_section(
				'section_files',
				__( 'Files', "anycomment" ),
				null,
				$this->page_slug
			);


			$this->render_fields(
				$this->page_slug,
				'section_generic',
				[
					[
						'id'          => self::OPTION_PLUGIN_TOGGLE,
						'title'       => __( 'Enable Comments', "anycomment" ),
						'callback'    => 'input_checkbox',
						'description' => esc_html( __( 'When on, comments are visible. When off, default WordPress\' comments shown. This can be used to configure social networks on fresh installation.', "anycomment" ) )
					],
					[
						'id'          => self::OPTION_FORM_TYPE,
						'title'       => __( 'Comment form', "anycomment" ),
						'callback'    => 'input_select',
						'description' => esc_html( __( 'Comment form', "anycomment" ) ),
						'args'        => [
							'options' => [
								self::FORM_OPTION_ALL          => __( 'Social, WordPress & guests', 'anycomment' ),
								self::FORM_OPTION_SOCIALS_ONLY => __( 'Socials & WordPress users only.', 'anycomment' ),
								self::FORM_OPTION_GUEST_ONLY   => __( 'Guests only. ', 'anycomment' ),
							]
						],
					],
					[
						'id'          => self::OPTION_REGISTER_DEFAULT_GROUP,
						'title'       => __( 'Register User Group', "anycomment" ),
						'callback'    => 'input_select',
						'description' => esc_html( __( 'When users will authorize via plugin, they are being registered and be assigned with group selected above.', "anycomment" ) ),
						'args'        => [
							'options' => [
								self::DEFAULT_ROLE_SUBSCRIBER        => __( 'Subscriber', 'anycomment' ),
								self::DEFAULT_ROLE_SOCIAL_SUBSCRIBER => __( 'Social Network Subscriber', 'anycomment' ),
							]
						],
					],
					[
						'id'          => self::OPTION_COUNT_PER_PAGE,
						'title'       => __( 'Number of Comments Loaded', "anycomment" ),
						'callback'    => 'input_number',
						'description' => esc_html( __( 'Number of comments to load initially and per page.', "anycomment" ) )
					],
					[
						'id'          => self::OPTION_LOAD_ON_SCROLL,
						'title'       => __( 'Load on Scroll', "anycomment" ),
						'callback'    => 'input_checkbox',
						'description' => esc_html( __( 'Load comments when user scrolls to it.', "anycomment" ) )
					],

					[
						'id'          => self::OPTION_SHOW_PROFILE_URL,
						'title'       => __( 'Show Profile URL', "anycomment" ),
						'callback'    => 'input_checkbox',
						'description' => esc_html( __( 'Show link to user in the social media when available (name of the user will be clickable).', "anycomment" ) )
					],

					[
						'id'          => self::OPTION_SHOW_VIDEO_ATTACHMENTS,
						'title'       => __( 'Display Video Attachments', "anycomment" ),
						'callback'    => 'input_checkbox',
						'description' => esc_html( __( 'Display video link from comment as attachment.', "anycomment" ) )
					],

					[
						'id'          => self::OPTION_SHOW_IMAGE_ATTACHMENTS,
						'title'       => __( 'Display Image Attachments', "anycomment" ),
						'callback'    => 'input_checkbox',
						'description' => esc_html( __( 'Display image link from comment as attachment.', "anycomment" ) )
					],

					[
						'id'          => self::OPTION_MAKE_LINKS_CLICKABLE,
						'title'       => __( 'Links Clickable', "anycomment" ),
						'callback'    => 'input_checkbox',
						'description' => esc_html( __( 'Links in comment are clickable.', "anycomment" ) )
					],

					[
						'id'          => self::OPTION_USER_AGREEMENT_LINK,
						'title'       => __( 'User Agreement Link', "anycomment" ),
						'callback'    => 'input_text',
						'description' => esc_html( __( 'Link to User Agreement, where described how your process users data once they authorize via social network and/or add new comment.', "anycomment" ) )
					],
					[
						'id'          => self::OPTION_COPYRIGHT_TOGGLE,
						'title'       => __( 'Thanks', "anycomment" ),
						'callback'    => 'input_checkbox',
						'description' => esc_html( __( 'Show AnyComment\'s link in the footer of comments. Copyright helps to bring awareness of such plugin and bring people to allow us to understand that it is a wanted product and give more often updated.', "anycomment" ) )
					],
				]
			);

			$this->render_fields(
				$this->page_slug,
				'section_design',
				[
					[
						'id'          => self::OPTION_THEME,
						'title'       => __( 'Theme', "anycomment" ),
						'callback'    => 'input_select',
						'args'        => [
							'options' => [
								self::THEME_DARK  => __( 'Dark', 'anycomment' ),
								self::THEME_LIGHT => __( 'Light', 'anycomment' ),
							]
						],
						'description' => esc_html( __( 'Choose comments theme.', "anycomment" ) )
					],
					[
						'id'          => self::OPTION_DESIGN_CUSTOM_TOGGLE,
						'title'       => __( 'Custom Design', "anycomment" ),
						'callback'    => 'input_checkbox',
						'description' => esc_html( __( 'Use custom design.', "anycomment" ) )
					],
					[
						'id'          => self::OPTION_DESIGN_GLOBAL_RADIUS,
						'title'       => __( 'Border radius', "anycomment" ),
						'callback'    => 'input_text',
						'description' => esc_html( __( 'Border radius.', "anycomment" ) )
					],
					[
						'id'          => self::OPTION_DESIGN_FONT_SIZE,
						'title'       => __( 'Font Size', "anycomment" ),
						'callback'    => 'input_text',
						'description' => esc_html( __( 'Global font size.', "anycomment" ) )
					],
					[
						'id'          => self::OPTION_DESIGN_FONT_FAMILY,
						'title'       => __( 'Font', "anycomment" ),
						'callback'    => 'input_text',
						'description' => esc_html( __( 'Global family.', "anycomment" ) )
					],

					[
						'id'          => self::OPTION_DESIGN_TEXT_COLOR,
						'title'       => __( 'Text Color', "anycomment" ),
						'callback'    => 'input_color',
						'description' => esc_html( __( 'Global text color.', "anycomment" ) )
					],
					[
						'id'          => self::OPTION_DESIGN_LINK_COLOR,
						'title'       => __( 'Link Color', "anycomment" ),
						'callback'    => 'input_color',
						'description' => esc_html( __( 'Links color.', "anycomment" ) )
					],
					[
						'id'          => self::OPTION_DESIGN_SEMI_HIDDEN_COLOR,
						'title'       => __( 'Semi Hidden Color', "anycomment" ),
						'callback'    => 'input_color',
						'description' => esc_html( __( 'Semi hidden color. Used for dates, actions (e.g. reply, edit, etc).', "anycomment" ) )
					],
					[
						'id'          => self::OPTION_DESIGN_FORM_FIELD_BACKGROUND_COLOR,
						'title'       => __( 'Form Fields Background', "anycomment" ),
						'callback'    => 'input_color',
						'description' => esc_html( __( 'Form fields background color.', "anycomment" ) )
					],

					[
						'id'          => self::OPTION_DESIGN_ATTACHMENT_COLOR,
						'title'       => __( 'Attachment Text Color', "anycomment" ),
						'callback'    => 'input_color',
						'description' => esc_html( __( 'Attachment text color.', "anycomment" ) )
					],

					[
						'id'          => self::OPTION_DESIGN_ATTACHMENT_BACKGROUND_COLOR,
						'title'       => __( 'Attachment Background Color', "anycomment" ),
						'callback'    => 'input_color',
						'description' => esc_html( __( 'Attachment background color.', "anycomment" ) )
					],

					[
						'id'          => self::OPTION_DESIGN_PARENT_AVATAR_SIZE,
						'title'       => __( 'Avatar Parent Size', "anycomment" ),
						'callback'    => 'input_text',
						'description' => esc_html( __( 'Parent avatar size (main comment).', "anycomment" ) )
					],

					[
						'id'          => self::OPTION_DESIGN_CHILD_AVATAR_SIZE,
						'title'       => __( 'Avatar Child Size', "anycomment" ),
						'callback'    => 'input_text',
						'description' => esc_html( __( 'Child avatar size (inside reply).', "anycomment" ) )
					],

					[
						'id'          => self::OPTION_DESIGN_BUTTON_RADIUS,
						'title'       => __( 'Button Radius', "anycomment" ),
						'callback'    => 'input_text',
						'description' => esc_html( __( 'Button border radius.', "anycomment" ) )
					],
					[
						'id'          => self::OPTION_DESIGN_BUTTON_COLOR,
						'title'       => __( 'Button Color', "anycomment" ),
						'callback'    => 'input_color',
						'description' => esc_html( __( 'Button text color.', "anycomment" ) )
					],

					[
						'id'          => self::OPTION_DESIGN_BUTTON_BACKGROUND_COLOR,
						'title'       => __( 'Button Background Color', "anycomment" ),
						'callback'    => 'input_color',
						'description' => esc_html( __( 'Button background color.', "anycomment" ) )
					],

					[
						'id'          => self::OPTION_DESIGN_BUTTON_BACKGROUND_COLOR_ACTIVE,
						'title'       => __( 'Button Background Color Active', "anycomment" ),
						'callback'    => 'input_color',
						'description' => esc_html( __( 'Button background color when active.', "anycomment" ) )
					],
				]
			);

			$this->render_fields(
				$this->page_slug,
				'section_moderation',
				[
					[
						'id'          => self::OPTION_MODERATE_FIRST,
						'title'       => __( 'Moderate First', "anycomment" ),
						'callback'    => 'input_checkbox',
						'description' => esc_html( __( 'Moderators should check comment before it appears.', "anycomment" ) )
					],
					[
						'id'          => self::OPTION_MODERATE_WORDS,
						'title'       => __( 'Spam Words', "anycomment" ),
						'callback'    => 'input_textarea',
						'description' => esc_html( __( 'Comment should be marked for moderation when matched word from this list of comma-separated values.', "anycomment" ) )
					],
				]
			);

			$this->render_fields(
				$this->page_slug,
				'section_notifications',
				[
					[
						'id'          => self::OPTION_NOTIFY_ON_NEW_COMMENT,
						'title'       => __( 'New Comment Alert', "anycomment" ),
						'callback'    => 'input_checkbox',
						'description' => esc_html( __( 'Show hint about new comment when user is on the comments page. Once clicked on alert, new comment will be displayed.', "anycomment" ) )
					],
					[
						'id'          => self::OPTION_INTERVAL_COMMENTS_CHECK,
						'title'       => __( 'New Comment Interval Checking', "anycomment" ),
						'callback'    => 'input_number',
						'description' => esc_html( __( 'Interval (in seconds) to check for new comments. Minimum 5 and maximum is 100 seconds.', "anycomment" ) )
					],
					[
						'id'          => self::OPTION_NOTIFY_ADMINISTRATOR,
						'title'       => __( 'Notify Administrator', "anycomment" ),
						'callback'    => 'input_checkbox',
						'description' => esc_html( __( 'Notify administrator via email about new comment.', "anycomment" ) )
					],
					[
						'id'          => self::OPTION_NOTIFY_ON_NEW_REPLY,
						'title'       => __( 'Email Notifications', "anycomment" ),
						'callback'    => 'input_checkbox',
						'description' => esc_html( __( 'Notify users by email (if specified) about new replies. Make sure you have proper SMTP configurations in order to send emails.', "anycomment" ) )
					],
				]
			);

			$this->render_fields(
				$this->page_slug,
				'section_files',
				[
					[
						'id'          => self::OPTION_FILES_GUEST_CAN_UPLOAD,
						'title'       => __( 'File Upload By Guests', "anycomment" ),
						'callback'    => 'input_checkbox',
						'description' => esc_html( __( 'Guest users can upload documents. Please be careful about this setting as some users may potentially misuse this and periodically upload unwanted files.', "anycomment" ) )
					],
					[
						'id'          => self::OPTION_FILES_MIME_TYPES,
						'title'       => __( 'File MIME Types', "anycomment" ),
						'callback'    => 'input_text',
						'description' => esc_html( __( 'Comman-separated list of allowed MIME types (e.g. .png, .jpg, etc). Alternatively, you may write "image/*" for all image types or "audio/*" for audios.', "anycomment" ) )
					],
					[
						'id'          => self::OPTION_FILES_LIMIT,
						'title'       => __( 'File Upload Limit', "anycomment" ),
						'callback'    => 'input_number',
						'description' => esc_html( __( 'Maximum number of files to upload per period defined in the field below.', "anycomment" ) )
					],
					[
						'id'          => self::OPTION_FILES_LIMIT_PERIOD,
						'title'       => __( 'File Upload Limit Period', "anycomment" ),
						'callback'    => 'input_number',
						'description' => esc_html( __( 'If user will cross the limit (defined above) within specified period (in seconds) in this field, he will be give a warning.', "anycomment" ) )
					],
					[
						'id'          => self::OPTION_FILES_MAX_SIZE,
						'title'       => __( 'File Size', "anycomment" ),
						'callback'    => 'input_number',
						'description' => esc_html( __( 'Maximum allowed file size in megabytes. For example, regular PNG image is about ~ 1.5-2MB, JPEG are even smaller.', "anycomment" ) )
					],
				]
			);
		}

		/**
		 * top level menu:
		 * callback functions
		 *
		 * @param bool $wrapper Whether to wrap for with header or not.
		 */
		public function page_html( $wrapper = true ) {
			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}

			if ( isset( $_GET['settings-updated'] ) ) {
				add_settings_error( $this->alert_key, 'anycomment_message', __( 'Settings Saved', 'anycomment' ), 'updated' );
			}

			static::applyStyleOnDesignChange();

			settings_errors( $this->alert_key );
			?>
			<?php if ( $wrapper ): ?>
                <div class="wrap">
                <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
			<?php endif; ?>
            <form action="options.php" method="post" class="anycomment-form">
				<?php
				settings_fields( $this->option_group );
				?>

                <div class="anycomment-tabs">
                    <aside class="anycomment-tabs__menu">
						<?php $this->do_tab_menu( $this->page_slug ) ?>
                    </aside>
                    <div class="anycomment-tabs__container">
						<?php
						$this->do_tab_sections( $this->page_slug, false );
						submit_button( __( 'Save', 'anycomment' ) );
						?>
                    </div>
                </div>
            </form>
            <script src="<?= AnyComment()->plugin_url() ?>/assets/js/forms.js"></script>
            <script src="<?= AnyComment()->plugin_url() ?>/assets/js/select2.min.js"></script>
			<?php if ( $wrapper ): ?>
                </div>
			<?php endif; ?>
			<?php
		}

		/**
		 * Used for customized theme'ing.
		 *
		 * It can combine multiple SCSS to one SCSS, convert it to CSS, minify,
		 * replace images with static from react. About last point read URL below.
		 *
		 * @link https://github.com/matthiasmullie/minify can be added later for minifying result CSS for speed-up purposes
		 *
		 * @return string String on success, false on failure.
		 */
		private static function combineStylesAndProcess() {
			include_once( AnyComment()->plugin_path() . '/includes/libs/scssphp/scss.inc.php' );

			$scss = new \Leafo\ScssPhp\Compiler();
			$scss->setFormatter( 'Leafo\ScssPhp\Formatter\Crunched' );

			$scssPath = AnyComment()->plugin_path() . '/assets/theming/';

			$content = trim( file_get_contents( $scssPath . 'comments.scss' ) );

			if ( empty( $content ) ) {
				return false;
			}

			/**
			 * Replace custom styles from plugin
			 */
			$hexRegex = '#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})';

			$arr = [
				"/\\$(font-size):\s([0-9].*[px|pt|em]);/m" => sprintf( '$$1: %s;', AnyCommentGenericSettings::getDesignFontSize() ),
				"/\\$(font-family):\s(.*?);/m"             => sprintf( '$$1: %s;', AnyCommentGenericSettings::getDesignFontFamily() ),
				"/\\$(link-color):\s($hexRegex);/m"        => sprintf( '$$1: %s;', AnyCommentGenericSettings::getDesignLinkColor() ),
				"/\\$(text-color):\s($hexRegex);/m"        => sprintf( '$$1: %s;', AnyCommentGenericSettings::getDesignTextColor() ),

				"/\\$(semi-hidden-color):\s($hexRegex);/m" => sprintf( '$$1: %s;', AnyCommentGenericSettings::getDesignSemiHiddenColor() ),

				"/\\$(form-field-background-color):\s($hexRegex);/m" => sprintf( '$$1: %s;', AnyCommentGenericSettings::getDesignFormFieldBackgroundColor() ),

				"/\\$(attachment-color):\s($hexRegex]+);/m"          => sprintf( '$$1: %s;', AnyCommentGenericSettings::getDesignAttachmentColor() ),
				"/\\$(attachment-background-color):\s($hexRegex);/m" => sprintf( '$$1: %s;', AnyCommentGenericSettings::getDesignAttachmentBackgroundColor() ),

				"/\\$(parent-avatar-size):\s(#[0-9].*[pt|px|em]);/m" => sprintf( '$$1: %s;', AnyCommentGenericSettings::getDesignParentAvatarSize() ),
				"/\\$(child-avatar-size):\s(#[0-9].*[pt|px|em]);/m"  => sprintf( '$$1: %s;', AnyCommentGenericSettings::getDesignChildAvatarSize() ),

				"/\\$(btn-radius):\s([0-9].*[px|%]);/m"              => sprintf( '$$1: %s;', AnyCommentGenericSettings::getDesignButtonRadius() ),
				"/\\$(btn-color):\s($hexRegex);/m"                   => sprintf( '$$1: %s;', AnyCommentGenericSettings::getDesignButtonColor() ),
				"/\\$(btn-background-color):\s($hexRegex);/m"        => sprintf( '$$1: %s;', AnyCommentGenericSettings::getDesignButtonBackgroundColor() ),
				"/\\$(btn-background-color-active):\s($hexRegex);/m" => sprintf( '$$1: %s;', AnyCommentGenericSettings::getDesignButtonBackgroundColorActive() ),

				"/\\$(global-radius):\s([a-z0-9]+[px|%]);/m" => sprintf( '$$1: %s;', AnyCommentGenericSettings::getDesignGlobalRadius() ),
			];


			foreach ( $arr as $pattern => $replacement ) {
				$replacedString = preg_replace( $pattern, $replacement, $content );

				if ( $replacedString !== null ) {
					$content = $replacedString;
				}
			}

			/**
			 * Replace relative paths of the images in the stylesheet with react-way,
			 * as there is no way to remove it via react-create-app
			 * @link https://github.com/facebook/create-react-app/issues/821 for further information
			 */

			$staticFolder = AnyComment()->plugin_path() . '/static/media/';
			$assets       = $staticFolder . '*.*';

			$fileAssetList = glob( $assets );

			if ( ! empty( $fileAssetList ) ) {
				foreach ( $fileAssetList as $key => $assetFullPath ) {
					preg_match( '/\/media\/(.*)\.[a-z0-9]+\.(svg|png|jpg|jpeg|ico|gif)$/m', $assetFullPath, $matches );

					if ( count( $matches ) !== 3 ) {
						continue;
					}

					$fullMatchAndUrl = AnyComment()->plugin_url() . '/static' . $matches[0];
					$fileName        = $matches[1];
					$extension       = $matches[2];

					$pattern = "/\.\.\/img\/?(.*?\/)$fileName\.$extension/m";
					$content = preg_replace( $pattern, $fullMatchAndUrl, $content );
				}
			}


			if ( strpos( $content, '@import' ) !== null ) {
				preg_match_all( '/@import\s"([a-z-]+)";/m', $content, $matches );

				if ( ! empty( $matches ) && ! empty( $matches[1] ) ) {
					foreach ( $matches[1] as $key => $match ) {
						$subContentPath = sprintf( "%s%s.scss", $scssPath, $match );
						$subContent     = trim( file_get_contents( $subContentPath ) );

						$search  = $matches[0][ $key ];
						$replace = '';

						if ( $subContent !== false && ! empty( $subContent ) ) {
							$replace = $subContent;
						}

						$content = str_replace( $search, $replace, $content );
					}
				}
			}

			$toastCss = file_get_contents( $scssPath . 'ReactToastify.css' );

			if ( $toastCss !== false ) {
				$content .= $toastCss;
			}

			return $scss->compile( $content );
		}

		public static function applyStyleOnDesignChange() {
			$hash        = static::getDesignHash();
			$filePattern = 'main-custom-%s.min.css';
			$path        = AnyComment()->plugin_path() . '/static/css/';

			$fullPath = $path . sprintf( $filePattern, $hash );

			if ( ! file_exists( $fullPath ) ) {

				// Need to check whether files with such patter already exist and delete
				// to avoid duplicate unwanted files
				$oldCustomFiles = glob( $path . sprintf( $filePattern, '*' ) );

				$generatedCss = static::combineStylesAndProcess();

				if ( empty( $generatedCss ) ) {
					return false;
				}

				if ( ! empty( $oldCustomFiles ) ) {
					foreach ( $oldCustomFiles as $key => $oldFile ) {
						unlink( $oldFile );
					}
				}

				$fileSaved = file_put_contents( $fullPath, $generatedCss );

				return $fileSaved !== false;
			}

			return false;
		}

		/**
		 * Get design hash to check whether it was changed or not.
		 *
		 * @return string
		 */
		public static function getDesignHash() {
			$items = [];

			$options = static::instance()->getOptions();

			if ( ! empty( $options ) ) {
				foreach ( $options as $option_name => $option_value ) {
					if ( strpos( $option_name, '_design_' ) !== false ) {
						$items[ $option_name ] = $option_value;
					}
				}
			}

			return md5( serialize( $items ) );
		}

		/**
		 * Get custom design hash.
		 *
		 * @return null|string NULL on failure (when nothing in the design specified yet.
		 */
		public static function getCustomDesignStylesheetUrl() {
			$hash = static::getDesignHash();

			if ( empty( $hash ) ) {
				return null;
			}

			return AnyComment()->plugin_url() . sprintf( '/static/css/main-custom-%s.min.css', $hash );
		}


		/**
		 * Check whether plugin is enabled or not.
		 *
		 * @return bool
		 */
		public static function isEnabled() {
			return static::instance()->getOption( self::OPTION_PLUGIN_TOGGLE ) !== null;
		}

		/**
		 * Check whether it is required to load comments on scroll.
		 *
		 * @return bool
		 */
		public static function isLoadOnScroll() {
			return static::instance()->getOption( self::OPTION_LOAD_ON_SCROLL ) !== null;
		}

		/**
		 * Check whether it is required to mark comments for moderation.
		 *
		 * @return bool
		 */
		public static function isModerateFirst() {
			return static::instance()->getOption( self::OPTION_MODERATE_FIRST ) !== null;
		}

		/**
		 * Check whether it is required to show video attachments.
		 *
		 * @return bool
		 */
		public static function isShowVideoAttachments() {
			return static::instance()->getOption( self::OPTION_SHOW_VIDEO_ATTACHMENTS ) !== null;
		}

		/**
		 * Check whether it is required to show image attachments.
		 *
		 * @return bool
		 */
		public static function isShowImageAttachments() {
			return static::instance()->getOption( self::OPTION_SHOW_IMAGE_ATTACHMENTS ) !== null;
		}

		/**
		 * Check whether it is required to make links clickable.
		 *
		 * @return bool
		 */
		public static function isLinkClickable() {
			return static::instance()->getOption( self::OPTION_MAKE_LINKS_CLICKABLE ) !== null;
		}

		/**
		 * Check whether it is required to show social profile URL or not.
		 *
		 * @return bool
		 */
		public static function isShowProfileUrl() {
			return static::instance()->getOption( self::OPTION_SHOW_PROFILE_URL ) !== null;
		}

		/**
		 * Check whether it is required to notify with alert on new comment.
		 *
		 * @return bool
		 */
		public static function isNotifyOnNewComment() {
			return static::instance()->getOption( self::OPTION_NOTIFY_ON_NEW_COMMENT ) !== null;
		}

		/**
		 * Check whether it is required to notify administrator about new comment.
		 *
		 * @return bool
		 */
		public static function isNotifyAdministrator() {
			return static::instance()->getOption( self::OPTION_NOTIFY_ADMINISTRATOR ) !== null;
		}

		/**
		 * Check whether it is required to notify by sending email on new reply.
		 *
		 * @return bool
		 */
		public static function isNotifyOnNewReply() {
			return static::instance()->getOption( self::OPTION_NOTIFY_ON_NEW_REPLY ) !== null;
		}

		/**
		 * Get list of words to moderate.
		 *
		 * @return string|null
		 */
		public static function getModerateWords() {
			return static::instance()->getOption( self::OPTION_MODERATE_WORDS );
		}


		/**
		 * Check whether guests uses can upload files.
		 *
		 * @return bool
		 */
		public static function isGuestCanUpload() {
			return static::instance()->getOption( self::OPTION_FILES_GUEST_CAN_UPLOAD );
		}

		/**
		 * Get file max size.
		 *
		 * @return float|null
		 */
		public static function getFileMaxSize() {
			return static::instance()->getOption( self::OPTION_FILES_MAX_SIZE );
		}

		/**
		 * Get file upload limit.
		 *
		 * @return float|null
		 */
		public static function getFileLimit() {
			return static::instance()->getOption( self::OPTION_FILES_LIMIT );
		}

		/**
		 * Get file upload period limit in seconds.
		 *
		 * @return int|null
		 */
		public static function getFileUploadLimit() {
			return static::instance()->getOption( self::OPTION_FILES_LIMIT_PERIOD );
		}

		/**
		 * Get allowed file MIME types.
		 *
		 * @return string|null
		 */
		public static function getFileMimeTypes() {
			return static::instance()->getOption( self::OPTION_FILES_MIME_TYPES );
		}

		/**
		 * Method is used to check for correctness of the file mime type again what is defined in settigs.
		 *
		 * @link https://github.com/okonet/attr-accept/blob/master/src/index.js (credits, used in frontend)
		 * @since 0.0.52
		 *
		 * @param array $file Regular array item from $_FILE
		 *
		 * @return bool
		 */
		public static function isAllowedMimeType( $file ) {
			$acceptedFilesArray = explode( ',', static::getFileMimeTypes() );

			if ( empty( $acceptedFilesArray ) ) {
				return false;
			}

			$fileName     = isset( $file['name'] ) ? $file['name'] : null;
			$mimeType     = isset( $file['type'] ) ? $file['type'] : null;
			$baseMimeType = preg_replace( '/\/.*$/', '', $mimeType );

			foreach ( $acceptedFilesArray as $key => $type ) {
				$validType = trim( $type );
				if ( $validType{0} === '.' ) {
					return strpos( strtolower( $fileName ), strtolower( $validType ) ) !== false;
				} else if ( strpos( $validType, '/*' ) !== false ) {
					// This is something like a image/* mime type
					return $baseMimeType === preg_replace( '/\/.*$/', '', $validType );
				}

				return $mimeType === $validType;
			}

			return true;
		}

		/**
		 * Enable custom design.
		 *
		 * @return bool
		 */
		public static function isDesignCustom() {
			return static::instance()->getOption( self::OPTION_DESIGN_CUSTOM_TOGGLE ) !== null;
		}

		/**
		 * Get design font size.
		 *
		 * @return string|null
		 */
		public static function getDesignFontSize() {
			return AnyCommentInputHelper::getSizeForCss( static::instance()->getOption( self::OPTION_DESIGN_FONT_SIZE ) );
		}

		/**
		 * Get design font family size.
		 *
		 * @return string|null
		 */
		public static function getDesignFontFamily() {
			return static::instance()->getOption( self::OPTION_DESIGN_FONT_FAMILY );
		}

		/**
		 * Get design semi hidden color.
		 *
		 * @return string|null
		 */
		public static function getDesignSemiHiddenColor() {
			return AnyCommentInputHelper::getHexForCss( static::instance()->getOption( self::OPTION_DESIGN_SEMI_HIDDEN_COLOR ) );
		}


		/**
		 * Get link color.
		 *
		 * @return string|null
		 */
		public static function getDesignLinkColor() {
			return AnyCommentInputHelper::getHexForCss( static::instance()->getOption( self::OPTION_DESIGN_LINK_COLOR ) );
		}

		/**
		 * Get text color.
		 *
		 * @return string|null
		 */
		public static function getDesignTextColor() {
			return AnyCommentInputHelper::getHexForCss( static::instance()->getOption( self::OPTION_DESIGN_TEXT_COLOR ) );
		}

		/**
		 * Get design form field background color.
		 *
		 * @return string|null
		 */
		public static function getDesignFormFieldBackgroundColor() {
			return AnyCommentInputHelper::getHexForCss( static::instance()->getOption( self::OPTION_DESIGN_FORM_FIELD_BACKGROUND_COLOR ) );
		}

		/**
		 * Get design attachment color.
		 *
		 * @return string|null
		 */
		public static function getDesignAttachmentColor() {
			return AnyCommentInputHelper::getHexForCss( static::instance()->getOption( self::OPTION_DESIGN_ATTACHMENT_COLOR ) );
		}

		/**
		 * Get design attachment background color.
		 *
		 * @return string|null
		 */
		public static function getDesignAttachmentBackgroundColor() {
			return AnyCommentInputHelper::getHexForCss( static::instance()->getOption( self::OPTION_DESIGN_ATTACHMENT_BACKGROUND_COLOR ) );
		}

		/**
		 * Get design parent avatar size.
		 *
		 * @return string|null
		 */
		public static function getDesignParentAvatarSize() {
			return AnyCommentInputHelper::getSizeForCss( static::instance()->getOption( self::OPTION_DESIGN_PARENT_AVATAR_SIZE ) );
		}

		/**
		 * Get design child avatar size.
		 *
		 * @return string|null
		 */
		public static function getDesignChildAvatarSize() {
			return AnyCommentInputHelper::getSizeForCss( static::instance()->getOption( self::OPTION_DESIGN_CHILD_AVATAR_SIZE ) );
		}

		/**
		 * Get design button color.
		 *
		 * @return string|null
		 */
		public static function getDesignButtonColor() {
			return AnyCommentInputHelper::getHexForCss( static::instance()->getOption( self::OPTION_DESIGN_BUTTON_COLOR ) );
		}

		/**
		 * Get design button background color.
		 *
		 * @return string|null
		 */
		public static function getDesignButtonBackgroundColor() {
			return AnyCommentInputHelper::getHexForCss( static::instance()->getOption( self::OPTION_DESIGN_BUTTON_BACKGROUND_COLOR ) );
		}

		/**
		 * Get design button background color color.
		 *
		 * @return string|null
		 */
		public static function getDesignButtonBackgroundColorActive() {
			return AnyCommentInputHelper::getHexForCss( static::instance()->getOption( self::OPTION_DESIGN_BUTTON_BACKGROUND_COLOR_ACTIVE ) );
		}

		/**
		 * Get design button radius.
		 *
		 * @return string|null
		 */
		public static function getDesignButtonRadius() {
			return AnyCommentInputHelper::getSizeForCss( static::instance()->getOption( self::OPTION_DESIGN_BUTTON_RADIUS ) );
		}

		/**
		 * Get design button radius.
		 *
		 * @return string|null
		 */
		public static function getDesignGlobalRadius() {
			return AnyCommentInputHelper::getSizeForCss( static::instance()->getOption( self::OPTION_DESIGN_GLOBAL_RADIUS ) );
		}

		/**
		 * Get interval in seconds per each check for new comments.
		 *
		 * @see AnyCommentGenericSettings::isNotifyOnNewReply() for more information. Which option is ignored when notification disabled.
		 *
		 * @return string
		 */
		public static function getIntervalCommentsCheck() {
			$intervalInSeconds = static::instance()->getOption( self::OPTION_INTERVAL_COMMENTS_CHECK );

			if ( $intervalInSeconds < 5 ) {
				$intervalInSeconds = 5;
			} elseif ( $intervalInSeconds > 100 ) {
				$intervalInSeconds = 100;
			}

			return $intervalInSeconds;
		}

		/**
		 * Get default group for registered user.
		 *
		 * @return string
		 */
		public static function getRegisterDefaultGroup() {
			return static::instance()->getOption( self::OPTION_REGISTER_DEFAULT_GROUP );
		}

		/**
		 * Get user agreement link. Used when user is guest and be authorizing using social network.
		 *
		 * @return string|null
		 */
		public static function getUserAgreementLink() {
			return static::instance()->getOption( self::OPTION_USER_AGREEMENT_LINK );
		}

		/**
		 * Get comment loaded per page setting value.
		 *
		 * @return int
		 */
		public static function getPerPage() {
			$value = (int) static::instance()->getOption( self::OPTION_COUNT_PER_PAGE );

			if ( $value < 5 ) {
				$value = 5;
			}

			return $value;
		}

		/**
		 * Get currently chosen theme.
		 * When value store is not matching any of the existing
		 * themes -> returns `dark` as default.
		 *
		 * @return string|null
		 */
		public static function getTheme() {
			$value = static::instance()->getOption( self::OPTION_THEME );

			if ( $value === null || $value !== self::THEME_DARK && $value !== self::THEME_LIGHT ) {
				return self::THEME_LIGHT;
			}

			return $value;
		}

		/**
		 * Get form type.
		 *
		 * @return string|null
		 */
		public static function getFormType() {
			return static::instance()->getOption( self::OPTION_FORM_TYPE );
		}

		/**
		 * Check whether form type is for all.
		 *
		 * @return bool
		 */
		public static function isFormTypeAll() {
			return static::getFormType() === self::FORM_OPTION_ALL;
		}

		/**
		 * Check whether form type is for social only.
		 *
		 * @return bool
		 */
		public static function isFormTypeSocials() {
			return static::getFormType() === self::FORM_OPTION_SOCIALS_ONLY;
		}

		/**
		 * Check whether form type is for guests only.
		 *
		 * @return bool
		 */
		public static function isFormTypeGuests() {
			return static::getFormType() === self::FORM_OPTION_GUEST_ONLY;
		}

		/**
		 * Check whether copyright should on or not.
		 *
		 * @return bool
		 */
		public static function isCopyrightOn() {
			return static::instance()->getOption( self::OPTION_COPYRIGHT_TOGGLE ) !== null;
		}
	}
endif;

