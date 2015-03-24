<?php
/**
 * WPC Settings Framework.
 *
 * @package   WPC_Settings_Framework
 * @author    Chris Baldelomar <chris@webplantmedia.com>
 * @license   GPL-2.0+
 * @link      http://webplantmedia.com
 * @copyright 2014 Chris Baldelomar
 */

/**
 * Settings framework class.
 *
 * @package WPC_Settings_Framework
 * @author  Chris Baldelomar <chris@webplantmedia.com>
 */
class WC_Gallery_Settings_Framework {

	protected $version = '1.0.0';

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;
	protected $sanitize = null;
	protected $display = null;

	/**
	 * Slug of the plugin screen.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $views = array();

	/**
	 * The variable name is used as the text domain when internationalizing strings
	 * of text. Its value should match the Text Domain file header in the main
	 * plugin file.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_slug = null;
	protected $plugin_prefix = null;
	protected $plugin_version = 0;
	protected $options = array();
	protected $wp_settings_tabs = array();
	protected $tabs = array();


	/**
	 * Initialize the plugin by loading admin scripts & styles and adding a
	 * settings page and menu.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {

		$this->set_slug_prefix();

		add_action( 'admin_init', array( $this, 'set_plugin_info' ) );

		add_action( 'init', array( $this, 'set_options' ), 100 );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_action( 'admin_init', array( $this, 'options_activation' ), 200 );
		add_action( 'admin_menu', array( $this, 'options_admin_menu' ) );

		// Load admin style sheet and JavaScript.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	public function set_slug_prefix() {
		$plugin_basename = plugin_basename( __FILE__ );
		$plugin_name = substr( $plugin_basename, 0, strpos( $plugin_basename, '/' ) );

		if ( empty( $plugin_name ) ) {
			return;
		}

		$this->plugin_slug = $this->sanitize_id( $plugin_name );
		$this->plugin_prefix = $this->sanitize_key( $plugin_name ) . '_';
	}
	
	public function set_plugin_info() {
		$this->plugin_current_version = get_option( $this->plugin_prefix . 'current_version' );

		$plugin = get_plugins( '/' . $this->plugin_slug );
		if ( ! empty( $plugin ) ) {
			$plugin = array_shift( $plugin );
			$this->plugin_version = $plugin['Version'];
		}
	}

	public function set_options() {
		$this->options = apply_filters ( $this->plugin_prefix . 'wpcsf_options' , $this->options );
	}

	public function options_activation() {

		$initialize = false;

		if ( ! isset( $this->plugin_current_version ) || empty( $this->plugin_current_version ) ) {
			$initialize = true;
		}
		else if ( version_compare( $this->plugin_version, $this->plugin_current_version ) > 0 ) {
			$initialize = true;
		}

		if ( $initialize ) {
			update_option( $this->plugin_prefix . 'current_version', $this->plugin_version );

			foreach ( $this->options as $menu_slug => $o ) {
				if ( isset( $o['option_group'] ) ) {
					if ( isset( $o['tabs'] ) &&
					is_array( $o['tabs'] ) ) {
						foreach( $o['tabs'] as $key => $oo ) {
							if ( isset( $oo['sections'] ) &&
							is_array( $oo['sections'] ) ) {
								$this->loop_and_init_options( $oo['sections'] );
							}
						}
					}
					else if ( isset( $o['sections'] ) &&
					is_array( $o['sections'] ) ) {
						$this->loop_and_init_options( $o['sections'] );
					}
				}
			}
		} 
	}

	public function loop_and_init_options( $sections ) {
		foreach( $sections as $o ) {
			if ( isset( $o['id'] ) &&
			isset( $o['title'] ) &&
			isset( $o['options'] ) &&
			is_array( $o['options'] ) ) {
				foreach( $o['options'] as $oo ) {
					if ( isset( $oo['group'] ) && is_array( $oo['group'] ) ) {
						foreach ( $oo['group'] as $key => $ooo ) {
							if ( isset( $ooo['option_name'] ) ) {
								$ooo['option_name'] = $this->plugin_prefix . $ooo['option_name'];
								$this->add_option( $ooo['option_name'], $ooo['default'] );
							}
						}
					}
					else {
						if ( isset( $oo['option_name'] ) ) {
							$oo['option_name'] = $this->plugin_prefix . $oo['option_name'];
							$this->add_option( $oo['option_name'], $oo['default'] );
						}
					}
				}
			}
		}
	}

	public function add_option( $option_name, $default ) {
		add_option( $option_name, $default );
	}

	/**
	 * Register theme options from user defined options array
	 *
	 * @since 3.5.2
	 * @access public
	 *
	 * @return void
	 */
	public function register_settings() {
		register_setting( $this->plugin_slug . '-wpcsf-current-version', $this->plugin_prefix . 'current_version' );

		foreach ( $this->options as $menu_slug => $o ) {
			if ( isset( $o['option_group'] ) ) {
				if ( isset( $o['tabs'] ) &&
				is_array( $o['tabs'] ) ) {
					foreach( $o['tabs'] as $key => $oo ) {
						if ( isset( $oo['sections'] ) &&
						is_array( $oo['sections'] ) ) {
							$this->loop_sections( $menu_slug, $o['option_group'], $oo['sections'], $oo['id'], $oo['title'] );
						}
					}
				}
				else if ( isset( $o['sections'] ) &&
				is_array( $o['sections'] ) ) {
					$this->loop_sections( $menu_slug, $o['option_group'], $o['sections'] );
				}
			}
		}
	}

	public function loop_sections( $menu_slug, $option_group, &$sections, $tab_id = null, $tab_title = null ) {
		foreach( $sections as $o ) {
			if ( isset( $o['id'] ) &&
			isset( $o['title'] ) &&
			isset( $o['options'] ) &&
			is_array( $o['options'] ) ) {
				// add_settings_section( $id, $title, $callback, $page );
				// @page should match @menu_slug from add_theme_page
				if ( isset( $o['add_section'] ) && $o['add_section'] ) {
					if ( ! empty( $tab_id ) && ! empty( $tab_title ) ) {
						$this->add_settings_tabs( $tab_id, $tab_title, $o['id'], $o['title'], $menu_slug );
					}
					add_settings_section( $o['id'], $o['title'], '', $menu_slug );
				}

				foreach( $o['options'] as $oo ) {
					if ( isset( $oo['group'] ) && is_array( $oo['group'] ) ) {
						foreach ( $oo['group'] as $key => $ooo ) {
							if ( isset( $ooo['option_name'] ) ) {
								$ooo['option_name'] = $this->plugin_prefix . $ooo['option_name'];
								$oo['group'][ $key ]['option_name'] = $ooo['option_name'];

								$callback = $this->get_callback( $ooo );

								// register_setting( $option_group, $option_name, $callback );
								register_setting( $option_group, $ooo['option_name'], $callback );
							}
						}
						if ( isset( $oo['id'] ) && isset( $oo['title'] ) ) {
							// add_settings_field( $id, $title, $callback, $page, $section, $args );
							// @page should match @menu_slug from add_theme_page
							// @section the section you added with add_settings_section
							add_settings_field($oo['id'], $oo['title'], array( $this, 'display_group' ), $menu_slug, $o['id'], $oo );
						}
					}
					else {
						if ( isset( $oo['option_name'] ) ) {
							$oo['option_name'] = $this->plugin_prefix . $oo['option_name'];

							$callback = $this->get_callback( $oo );

							// register_setting( $option_group, $option_name, $callback );
							register_setting( $option_group, $oo['option_name'], $callback );

							// add_settings_field( $id, $title, $callback, $page, $section, $args );
							// @page should match @menu_slug from add_theme_page
							// @section the section you added with add_settings_section
							add_settings_field( $oo['option_name'], '<label for="'.$oo['option_name'].'">'.$oo['title'].'</label>' , array( $this, 'display_setting' ), $menu_slug, $o['id'], $oo );
						}
					}
				}
			}
		}
	}

	public function get_callback( &$o ) {
		if ( isset( $o['callback'] ) && ! empty( $o['callback'] ) ) {
			if ( function_exists( $o['callback'] ) ) {
				return $o['callback'];
			}
		}

		$callback = array(
			$this,
			$this->sanitize_callback( $o['type'] ),
		);

		return $callback;
	}

	/**
	 * Add submenu pages from user defined options array
	 *
	 * @since 3.5.2
	 * @access public
	 *
	 * @return void
	 */
	public function options_admin_menu() {
		if ( ! empty( $this->options ) ) {
			foreach ( $this->options as $menu_slug => $v ) {
				if ( isset( $v['parent_slug'] ) &&
				isset( $v['page_title'] ) &&
				isset( $v['menu_title'] ) &&
				isset( $v['capability'] ) &&
				isset( $v['option_group'] ) ) {
					// add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function );
					$view_hook_name = add_submenu_page( $v['parent_slug'], $v['page_title'], $v['menu_title'], $v['capability'], $menu_slug, array( $this, 'display_page' ) );
					$this->views[ $view_hook_name ] = $menu_slug;
				}
				else if ( isset( $v['parent_slug'] ) ) {
					$this->views[ $v['parent_slug'] ] = $menu_slug;
				}
			}
		}
	}

	public function add_settings_tabs( $id, $title, $section_id, $section_title, $menu_slug ) {
		$this->wp_settings_tabs[ $menu_slug ][ $id ][ $section_id ] = array(
			'id' => $section_id,
			'title' => $section_title
		);

		$this->tabs[ $menu_slug ][ $id ] = array(
			'id' => $id,
			'title' => $title,
		);
	}

	public function fetch_proper_hook_name( $hook ) {
		switch( $hook ) {
			case 'options-general.php' :
				return 'settings_page_general';
			case 'options-writing.php' :
				return 'settings_page_writing';
			case 'options-reading.php' :
				return 'settings_page_reading';
			case 'options-discussion.php' :
				return 'settings_page_discussion';
			case 'options-media.php' :
				return 'settings_page_media';
			case 'options-permalink.php' :
				return 'settings_page_permalink';
		}

		return $hook;
	}

	/**
	 * Register and enqueue admin-specific JavaScript.
	 *
	 * @TODO:
	 *
	 * @since     1.0.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_scripts( $hook ) {

		$hook = $this->fetch_proper_hook_name( $hook );
		// pr($hook);
		// pr($this->views);

		if ( ! isset( $this->views ) || empty( $this->views ) ) {
			return;
		}

		if ( array_key_exists( $hook, $this->views ) ) {
			// CSS
			wp_enqueue_style( $this->plugin_slug .'-options-styles', plugins_url( 'css/options.css', __FILE__ ), array(), $this->version );
			wp_enqueue_style( $this->plugin_slug .'-media-uploader-styles', plugins_url( 'css/media-uploader.css', __FILE__ ), array(), $this->version );
			wp_enqueue_style( 'wp-color-picker' );

			// JS
			wp_enqueue_script( $this->plugin_slug . '-options-script', plugins_url( 'js/options.js', __FILE__ ), array( 'jquery' ), $this->version, true );
			wp_enqueue_media();
			wp_enqueue_script( $this->plugin_slug . '-media-uploader-script', plugins_url( 'js/media-uploader.js', __FILE__ ), array( 'jquery', 'wp-color-picker' ), $this->version, true );
		}

	}

	/**
	 * Get current filter, check with available views
	 * created when adding sub_pages, and return
	 * menu slug
	 *
	 * @since 3.6.1
	 * @access public
	 *
	 * @return void
	 */
	function get_current_view() {
		$current_filter = current_filter();
		if ( ! isset ( $this->views[ $current_filter ] ) )
			return false;

		$menu_slug = $this->views[ $current_filter ];

		return $menu_slug;
	}

	/**
	 * Display default settings page.
	 *
	 * @since 3.5.2
	 * @access public
	 *
	 * @return void
	 */
	function display_page() {
		if ( ! $menu_slug = $this->get_current_view() )
			return;

		$o = $this->options[$menu_slug];
		if ( ! empty( $this->wp_settings_tabs ) ) {
			require( 'views/tabs.php' );
		}
		else {
			require( 'views/page.php' );
		}
	}

	/**
	 * Call all the options displays in a given option
	 * group
	 *
	 * @since 3.5.2
	 * @access public
	 *
	 * @param array $args 
	 * @return void
	 */
	function display_group( $args ) {
		require( 'views/group.php' );
	}

	/**
	 * Controls which display function should be called
	 * given a option type passed.
	 *
	 * @since 3.5.2
	 * @access public
	 *
	 * @param array $args 
	 * @return void
	 */
	function display_setting( $args ) {
		if ( ! isset( $args['type'] ) )
			return;

		if ( ! isset( $args['option_name'] ) )
			return;

		if ( ! isset( $args['default'] ) )
			return;

		if ( ! isset( $args['display'] ) || empty( $args['display'] ) )
			$args['display'] = 'default';

		extract( $args );
		$val = get_option( $option_name, $default );

		switch ( $args['type'] ) {
			case 'image' :
				require( 'views/settings/image-field.php' );
				break;
			case 'positive_pixel' :
				require( 'views/settings/positive-pixel-input-field.php' );
				break;
			case 'positive_number' :
				require( 'views/settings/positive-number-input-field.php' );
				break;
			case 'pixel' :
				require( 'views/settings/pixel-input-field.php' );
				break;
			case 'number' :
				require( 'views/settings/number-input-field.php' );
				break;
			case 'decimal' :
				require( 'views/settings/decimal-input-field.php' );
				break;
			case 'radio' :
				require( 'views/settings/custom-radio.php' );
				break;
			case 'checkboxes' :
				require( 'views/settings/custom-checkboxes.php' );
				break;
			case 'dropdown' :
				require( 'views/settings/custom-dropdown.php' );
				break;
			case 'background' :
				require( 'views/settings/background-options.php' );
				break;
			case 'color' :
				require( 'views/settings/color-field.php' );
				break;
			case 'checkbox' :
				require( 'views/settings/checkbox-field.php' );
				break;
			case 'border' :
				require( 'views/settings/border-fields.php' );
				break;
			case 'gallery' :
				require( 'views/settings/gallery-fields.php' );
				break;
			case 'textarea' :
				require( 'views/settings/textarea-field.php' );
				break;
			case 'wp_editor' :
				require( 'views/settings/wp-editor.php' );
				break;
			case 'order_show_hide' :
				require( 'views/settings/order-show-hide.php' );
				break;
			case 'emails' :
			default :
				require( 'views/settings/input-field.php' );
				break;
		}
	}

	/************
	 * Sanitize *
	 ************/

	/**
	 * Given an option type, we will return a string
	 * of the callback function used to sanitize
	 * the option value
	 *
	 * @since 3.5.2
	 * @access public
	 *
	 * @param string $type 
	 * @return string
	 */
	public function sanitize_callback( $type ) {
		switch ( $type ) {
			case 'color' :
				return 'sanitize_hex_color';
			case 'image' :
				return 'sanitize_esc_url_raw';
			case 'positive_pixel' :
				return 'sanitize_positive_pixel';
			case 'positive_number' :
				return 'sanitize_positive_number';
			case 'pixel' :
				return 'sanitize_pixel';
			case 'number' :
				return 'sanitize_number';
			case 'decimal' :
				return 'sanitize_decimal';
			case 'border' :
				return 'sanitize_border';
			case 'background' :
				return 'sanitize_background_css';
			case 'checkbox' :
				return 'sanitize_checkbox';
			case 'gallery' :
				return 'sanitize_gallery';
			case 'emails' :
				return 'sanitize_emails';
		}

		return 'sanitize_none';
	}

	/**
	 * Validate slideshow data saved to database.
	 *
	 * @since 3.6
	 * @access public
	 *
	 * @param array $value 
	 * @return array
	 */
	public function sanitize_gallery( $value ) {
		if ( empty( $value ) )
			return null;

		$ids = explode( ',', $value );

		foreach ( $ids as $key => $id ) {
			if ( ! is_numeric( $id ) ) {
				unset( $ids[ $key ] );
			}
		}

		$value = implode( ',', $ids );

		return $value;
	}

	/**
	 * Sanitize border values. Border consists
	 * of pixel value, border style, and color.
	 *
	 * @since 3.6
	 * @access public
	 *
	 * @param array $value 
	 * @return array
	 */
	public function sanitize_border( $value ) {
		$border = array(
			'width' => '0px',
			'style' => 'none',
			'color' => '#ffffff',
		);

		if ( ! is_array( $value ) )
			return $border;

		foreach ( $value as $k => $v ) {
			switch ( $k ) {
				case 'width' :
					$v = $this->sanitize_positive_pixel( $v );
					$border['width'] = $v;
					break;
				case 'style' :
					$v = $this->sanitize_border_style( $v );
					$border['style'] = $v;
					break;
				case 'color' :
					$v = $this->sanitize_hex_color( $v );
					$border['color'] = $v;
					break;
			}
		}

		return $border;
	}

	/**
	 * Strips all non numerica characters and returns
	 * intval() of string. Only allows for positive values.
	 *
	 * @since 3.6
	 * @access public
	 *
	 * @param string $value 
	 * @return void
	 */
	public function sanitize_positive_pixel( $value ) {
		$value = preg_replace("/[^0-9]/", "",$value);
		$value = intval( $value );

		if ( empty( $value ) )
			$value = '0';

		return $value."px";
	}

	public function sanitize_positive_number( $value ) {
		$value = preg_replace("/[^0-9]/", "",$value);
		$value = intval( $value );

		if ( empty( $value ) )
			$value = '0';

		return $value;
	}

	/**
	 * Strips all non numerica characters and returns
	 * intval() of string. Allows both negative and
	 * positive values.
	 *
	 * @since 3.6
	 * @access public
	 *
	 * @param string $value 
	 * @return void
	 */
	public function sanitize_pixel( $value ) {
		$value = preg_replace("/[^0-9\-]/", "",$value);
		$value = intval( $value );

		if ( empty( $value ) )
			$value = '0';

		return $value."px";
	}

	public function sanitize_font( $value ) {
		$font = array(
			'font_family' => '',
			'font_size' => '',
			'text_transform' => '',
			'font_style' => '',
			'font_weight' => '',
			'color' => '',
		);

		if ( !is_array( $value ) )
			return $font;

		foreach ( $value as $k => $v ) {
			switch ( $k ) {
				case 'font_family' :
					$font['font_family'] = $v;
					break;
				case 'font_size' :
					$v = $this->sanitize_pixel( $value['font_size'] );
					$font['font_size'] = $v;
					break;
				case 'text_transform' :
					$font['text_transform'] = $v;
					break;
				case 'font_style' :
					$v = $this->sanitize_font_style( $v );
					$font['font_style'] = $v;
					break;
				case 'font_weight' :
					$v = $this->sanitize_font_weight( $v );
					$font['font_weight'] = $v;
					break;
				case 'color' :
					$v = $this->sanitize_hex_color( $v );
					$font['color'] = $v;
					break;
			}
		}

		return $font;
	}

	public function sanitize_font_hover( $value ) {
		$font = array(
			'text_decoration' => '',
			'color' => '',
		);

		if ( !is_array( $value ) )
			return $font;

		foreach ( $value as $k => $v ) {
			switch ( $k ) {
				case 'text_decoration' :
					$v = $this->sanitize_text_decoration( $v );
					$font['text_decoration'] = $v;
					break;
				case 'color' :
					$v = $this->sanitize_hex_color( $v );
					$font['color'] = $v;
					break;
			}
		}

		return $font;
	}

	public function sanitize_font_appearance( $value ) {
		$font = array(
			'text_decoration' => '',
			'font_style' => '',
			'font_weight' => '',
			'color' => '',
		);

		if ( !is_array( $value ) )
			return $font;

		foreach ( $value as $k => $v ) {
			switch ( $k ) {
				case 'text_decoration' :
					$v = $this->sanitize_text_decoration( $v );
					$font['text_decoration'] = $v;
					break;
				case 'font_style' :
					$v = $this->sanitize_font_style( $v );
					$font['font_style'] = $v;
					break;
				case 'font_weight' :
					$v = $this->sanitize_font_weight( $v );
					$font['font_weight'] = $v;
					break;
				case 'color' :
					$v = $this->sanitize_hex_color( $v );
					$font['color'] = $v;
					break;
			}
		}

		return $font;
	}

	public function sanitize_text_decoration( $value ) {
		$whitelist = array(
			'none',
			'underline',
			'overline',
			'line-through',
		);

		if ( in_array( $value, $whitelist ) )
			return $value;

		return '';
	}

	public function sanitize_text_transform( $value ) {
		$whitelist = array(
			'none',
			'capitalize',
			'uppercase',
			'lowercase',
		);

		if ( in_array( $value, $whitelist ) )
			return $value;

		return '';
	}

	public function sanitize_font_style( $value ) {
		$whitelist = array(
			'normal',
			'italic',
			'oblique',
		);

		if ( in_array( $value, $whitelist ) )
			return $value;

		return '';
	}

	public function sanitize_font_weight( $value ) {
		$whitelist = array(
			'normal',
			'bold',
			'bolder',
			'lighter',
			'100',
			'200',
			'300',
			'400',
			'500',
			'600',
			'700',
			'800',
			'900',
		);

		if ( in_array( $value, $whitelist ) )
			return $value;

		return '';
	}

	public function sanitize_background_css( $value ) {
		$background = array(
			'color' => '',
			'image' => '',
			'repeat' => '',
			'position' => '',
			'attachment' => '',
		);

		if ( !is_array( $value ) )
			return $background;

		foreach ( $value as $k => $v ) {
			switch ( $k ) {
				case 'color' :
					$v = $this->sanitize_hex_color( $v );
					$background['color'] = $v;
					break;
				case 'image' :
					$v = esc_url_raw( $v );
					$background['image'] = $v;
					break;
				case 'repeat' :
					$v = $this->sanitize_background_repeat( $v );
					$background['repeat'] = $v;
					break;
				case 'position' :
					$v = $this->sanitize_background_position( $v );
					$background['position'] = $v;
					break;
				case 'attachment' :
					$v = $this->sanitize_background_attachment( $v );
					$background['attachment'] = $v;
					break;
			}
		}

		return $background;
	}

	public function sanitize_background_repeat( $value ) {
		$whitelist = array( 'repeat', 'no-repeat', 'repeat-x', 'repeat-y' );

		if ( in_array( $value, $whitelist ) )
			return $value;

		return '';
	}

	public function sanitize_border_style( $value ) {
		$whitelist = array(
			'none',
			'hidden',
			'dotted',
			'dashed',
			'solid',
			'double',
			'groove',
			'ridge',
			'inset',
			'outset',
			'inherit',
		);

		if ( in_array( $value, $whitelist ) )
			return $value;

		return 'none';
	}

	public function sanitize_background_position( $value ) {
		$whitelist = array(
			'left top',
			'left center',
			'left bottom',
			'right top',
			'right center',
			'right bottom',
			'center top',
			'center center',
			'center bottom',
		);

		if ( in_array( $value, $whitelist ) )
			return $value;

		return '';
	}

	public function sanitize_background_attachment( $value ) {
		$whitelist = array( 'fixed', 'scroll' );

		if ( in_array( $value, $whitelist ) )
			return $value;

		return '';
	}

	public function sanitize_hex_color( $color ) {
		if ( '' === $color )
			return '';

		if ( preg_match('|^#([A-Fa-f0-9]{3}){1,2}$|', $color ) )
			return $color;

		return null;
	}

	/**
	 * replace nonalphannumeric charachers with underscore
	 * should be safe enought to use as array key
	 *
	 * @since 3.5.2
	 * @access public
	 *
	 * @param string $key 
	 * @return string
	 */
	public function sanitize_key( $key ) {
		$key = strtolower( preg_replace( '/[^a-zA-Z0-9]/', '_', $key ) );

		return $key;
	}

	/**
	 * replace nonalphannumeric charachers with hyphen
	 * should be safe enough to use as a CSS id
	 *
	 * @since 3.5.2
	 * @access public
	 *
	 * @param string $key 
	 * @return string
	 */
	public function sanitize_id( $id ) {
		$id = strtolower( preg_replace( '/[^a-zA-Z0-9]/', '-', $id ) );

		return $id;
	}

	/**
	 * return numeric values only
	 *
	 * @since 3.6
	 * @access public
	 *
	 * @param string $number 
	 * @return int
	 */
	public function sanitize_number( $number ) {
		$number = (int) preg_replace( "/[^0-9\-]/", "", $number );

		return $number;
	}

	/**
	 * return decimal number
	 *
	 * @since 3.6.1
	 * @access public
	 *
	 * @param mixed $number
	 * @return void
	 */
	public function sanitize_decimal( $number ) {
		$number = preg_replace( "/[^0-9\.\-]/", "", $number );

		return $number;
	}

	/**
	 * replace space with plus sign. Should be safe enough 
	 * to use in Google Font stylesheet link
	 *
	 * @since 3.5.2
	 * @access public
	 *
	 * @param string $code 
	 * @return string
	 */
	public function sanitize_google_code( $code ) {
		$code = preg_replace( '/\s/', '+', $code );

		return $code;
	}

	/**
	 * Parse only friendly characters to use in family name
	 * inside css file.
	 *
	 * @since 3.5.2
	 * @access public
	 *
	 * @param string $name 
	 * @return string
	 */
	public function sanitize_font_family_name( $name ) {
		$name = preg_replace( '/[^a-zA-Z0-9\-_]/', '', $name );

		return $name;
	}

	/**
	 * Checkbox should only return 1 or 0
	 *
	 * @since 3.5.2
	 * @access public
	 *
	 * @param string $val 
	 * @return void
	 */
	public function sanitize_checkbox( $val ) {
		if ( $val )
			return 1;
		else
			return 0;
	}

	/**
	 * Make sure sidebar is valid
	 *
	 * @since 3.6.1
	 * @access public
	 *
	 * @param mixed $value
	 * @return void
	 */
	public function sanitize_sidebar( $value ) {
		global $wp_registered_sidebars;

		if ( 'none' == $value )
			return $value;

		if ( array_key_exists( $value, $wp_registered_sidebars ) )
			return $value;

		return 'none';
	}

	/**
	 * Sanitize multiple emails
	 *
	 * @since 3.7.1
	 * @access public
	 *
	 * @param mixed $email
	 * @return void
	 */
	public function sanitize_emails( $email ) {
		$valid = array();

		$email = explode( ',', $email );

		foreach ( $email as $e ) {
			$e = trim( $e );
			if ( is_email( $e ) )
				$valid[] = $e;
		}

		if ( ! empty( $valid ) )
			return implode( ',', $valid );

		return null;
	}

	public function sanitize_esc_url_raw( $value ) {
		return esc_url_raw( $value );
	}
	
	public function sanitize_none( $value ) {
		return $value;
	}
}
