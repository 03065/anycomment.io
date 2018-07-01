<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'AnyCommentGenericSettings' ) ) :
	/**
	 * AC_AdminSettingPage helps to process generic plugin settings.
	 */
	class AnyCommentGenericSettings extends AnyCommentOptions {
		const OPTION_THEME = 'option_theme_toggle';
		const OPTION_PLUGIN_TOGGLE = 'option_plugin_toggle';
		const OPTION_COUNT_PER_PAGE = 'option_comments_count_per_page';
		const OPTION_COPYRIGHT_TOGGLE = 'option_copyright_toggle';

		const THEME_DARK = 'dark';
		const THEME_LIGHT = 'light';

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
			self::OPTION_THEME            => self::THEME_DARK,
			self::OPTION_COPYRIGHT_TOGGLE => 'on',
			self::OPTION_COUNT_PER_PAGE   => 20
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
			add_action( 'admin_menu', [ $this, 'add_menu' ] );
			add_action( 'admin_init', [ $this, 'init_settings' ] );
		}

		/**
		 * Init admin menu.
		 */
		public function add_menu() {
			add_submenu_page(
				'anycomment-dashboard',
				__( 'Settings', "anycomment" ),
				__( 'Settings', "anycomment" ),
				'manage_options',
				$this->page_slug,
				[ $this, 'page_html' ]
			);
		}

		/**
		 * {@inheritdoc}
		 */
		public function init_settings() {
			add_settings_section(
				'section_generic',
				__( 'Generic', "anycomment" ),
				function () {
					echo '<p>' . __( 'Generic settings.', "anycomment" ) . '</p>';
				},
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
						'id'          => self::OPTION_COUNT_PER_PAGE,
						'title'       => __( 'Number of Comments Loaded', "anycomment" ),
						'callback'    => 'input_number',
						'description' => esc_html( __( 'Number of comments loaded on initial page load. For example, "20" will display 20 comments on the page and if you have 40 in total, at the very bottom you will see button to load more. Min 5, max as defined.', "anycomment" ) )
					],
					[
						'id'          => self::OPTION_THEME,
						'title'       => __( 'Theme', "anycomment" ),
						'callback'    => 'input_select',
						'args'        => [
							'options' => [
								self::THEME_DARK  => __( 'For Dark Websites', 'anycomment' ),
								self::THEME_LIGHT => __( 'For Light Websites', 'anycomment' ),
							]
						],
						'description' => esc_html( __( 'Choose theme of the comments.', "anycomment" ) )
					],
					[
						'id'          => self::OPTION_COPYRIGHT_TOGGLE,
						'title'       => __( 'Thanks', "anycomment" ),
						'callback'    => 'input_checkbox',
						'description' => esc_html( __( 'Show AnyComment\'s link in the footer of comments. Copyright helps to bring awareness of such plugin and bring people to allow us to understand that it is a wanted product and give more often updated.', "anycomment" ) )
					],
				]
			);
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
				return self::THEME_DARK;
			}

			return $value;
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

