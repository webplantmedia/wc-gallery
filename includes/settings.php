<?php
function wc_gallery_options_init() {
	global $wc_gallery_options;

	foreach ( $wc_gallery_options as $tab => $o ) {
		foreach ( $o['sections'] as $oo ) {
			add_settings_section( $oo['section'], $oo['title'], '', 'wc-gallery-options' . $tab );
			foreach( $oo['options'] as $ooo ) {
				if ( isset( $ooo['group'] ) && is_array( $ooo['group'] ) ) {
					foreach ( $ooo['group'] as $key => $oooo ) {
						$oooo['option_name'] = WC_GALLERY_PREFIX . $oooo['id'];
						$ooo['group'][ $key ]['option_name'] = WC_GALLERY_PREFIX . $oooo['id'];

						if ( isset( $oooo['option_name'] ) ) {
							$callback = wc_gallery_options_find_sanitize_callback( $oooo['type'] );
							register_setting( 'wc-gallery-options-'.$tab.'group', $oooo['option_name'], $callback );
						}
					}
					if ( isset( $ooo['id'] ) && isset( $ooo['title'] ) ) {
						add_settings_field('wc_gallery_'.$ooo['id'].'', '<label for="wc_gallery_'.$ooo['id'].'">'.__($ooo['title'] , 'wc_gallery' ).'</label>' , 'wc_gallery_options_display_group', 'wc-gallery-options'.$tab, $oo['section'], $ooo );
					}
				}
				else {
					$ooo['option_name'] = WC_GALLERY_PREFIX . $ooo['id'];

					if ( isset( $ooo['option_name'] ) ) {
						$callback = wc_gallery_options_find_sanitize_callback( $ooo['type'] );
						register_setting( 'wc-gallery-options-'.$tab.'group', $ooo['option_name'], $callback );
						add_settings_field('wc_gallery_'.$ooo['option_name'].'', '<label for="wc_gallery_'.$ooo['option_name'].'">'.__($ooo['title'] , 'wc_gallery' ).'</label>' , 'wc_gallery_options_display_setting', 'wc-gallery-options'.$tab, $oo['section'], $ooo );
					}
				}
			}
		}
	}
}
add_action( 'admin_init', 'wc_gallery_options_init' );

function wc_gallery_options_admin_menu() {
	global $wc_gallery_options;

	foreach ( $wc_gallery_options as $tab => $o ) {
		$view_hook_name = add_submenu_page( 'options.php', $o['title'], $o['title'], 'manage_options', 'wc-gallery-options-' . $tab, 'wc_gallery_options_display_page' );
	}

	// add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function );
	$view_hook_name = add_submenu_page( 'themes.php', 'Gallery', 'Gallery', 'manage_options', 'wc-gallery-options', 'wc_gallery_options_display_page' );
}
add_action( 'admin_menu', 'wc_gallery_options_admin_menu' );

function wc_gallery_options_display_page() {
	global $wc_gallery_options, $tab;
	wp_reset_vars( array( 'tab' ) );

	// restore last tab visited
	if ( empty( $tab ) && isset( $_COOKIE[ WC_GALLERY_PREFIX . 'last_tab_visited'] ) ) {
		$last_tab = $_COOKIE[ WC_GALLERY_PREFIX . 'last_tab_visited'];
		if ( isset( $wc_gallery_options[ $last_tab ] ) ) {
			$tab = $last_tab;
		}
	}

	?>
	<div class="wrap">
		<?php screen_icon(); ?>
		<?php
			$links = array();
			foreach( $wc_gallery_options as $id => $page ) :
				if ( empty( $tab ) || $id == $tab ) {
					$tab = $id;
					$links[] = "<a class='nav-tab nav-tab-active' href='themes.php?page=wc-gallery-options&tab=".$tab."'>".$page['title']."</a>";
				}
				else {
					$links[] = "<a class='nav-tab' href='themes.php?page=wc-gallery-options&tab=".$id."'>".$page['title']."</a>";
				}
			endforeach;
		?>
		<h2 class="nav-tab-wrapper">
		<?php echo implode( '', $links ); ?>
		</h2>

		<?php if ( isset( $_GET['settings-updated'] ) ) : ?>
			<div id="message" class="updated"><p><strong><?php _e( 'Settings saved.' ) ?></strong></p></div>
		<?php endif; ?>

		<form id="compile-less-css" method="post" action="options.php">
			<?php
			// settings_fields( $option_group )
			// @option_group A settings group name. This should match the group name used in register_setting()
			settings_fields( 'wc-gallery-options-'.$tab.'group' );

			// do_settings_sections( $page ) 
			// The slug name of the page whose settings sections you want to output. This should match the page name used in add_settings_section()
			do_settings_sections( 'wc-gallery-options'.$tab );
			?>

			<p class="submit">
				<?php submit_button( null, 'primary', 'submit', false ); ?>
			</p>
		</form>
	</div>
	<?php
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
function wc_gallery_options_display_group( $args ) {
	foreach ( $args['group'] as $g ) {
		wc_gallery_options_display_setting( $g );
	}
	?>

	<?php if ( isset( $args['description'] ) ) : ?>
		<p class="description"><?php echo $args['description']; ?></p>
	<?php endif; ?>

	<?php
}

/*
 * Display Options 
 */
function wc_gallery_options_display_setting( $args ) {
	if ( !isset( $args['type'] ) )
		return;

	if ( !isset( $args['option_name'] ) )
		return;

	if ( !isset( $args['default'] ) )
		return;

	switch ( $args['type'] ) {
		case 'image' :
			wc_gallery_options_display_image_field( $args );
			break;
		case 'checkbox' :
			wc_gallery_options_display_checkbox_field( $args );
			break;
		case 'positive_number' :
			wc_gallery_options_display_positive_number_input_field( $args );
			break;
		default :
			wc_gallery_options_input_field( $args );
			break;
	}
}

function wc_gallery_options_input_field( $args ) {
	extract( $args );

	$val = get_option( $option_name, $default );
	?>

	<?php if ( isset( $label ) ) : ?>
		<label for="<?php echo esc_attr($option_name); ?>"><?php echo $label; ?></label>&nbsp;
	<?php endif; ?>

	<input name="<?php echo $option_name; ?>" id="<?php echo $option_name; ?>" type="text" value="<?php echo esc_attr($val); ?>" class="regular-text" />
	<?php if ( isset( $description ) && !empty( $description ) ) : ?>
		<p class="description"><?php echo $description; ?></p>
	<?php endif; ?>
	<?php
}
function wc_gallery_options_display_image_field( $args ) {
	extract( $args );

	$val = get_option( $option_name, $default );

	// preview image default style
	$style = '';
	if ( empty( $val['image'] ) )
		$style = ' style="display:none"';
	?>

	<div class="wc-gallery-image-field">
		<input name="<?php echo $option_name; ?>" id="<?php echo $option_name; ?>" class="regular-text ltr upload-input" type="text" value="<?php echo esc_attr($val); ?>" />
		<br />
		<a class="button wc-gallery-image-upload" data-target="#<?php echo $option_name; ?>" data-preview=".wc-gallery-preview-image" data-frame="select" data-state="wordpresscanvas_insert_single" data-fetch="url" data-title="Insert Image" data-button="Insert" data-class="media-frame wc-gallery-custom-uploader" title="Add Media"><span class="wp-media-buttons-icon"></span> Add Media</a>
		<a class="button wc-gallery-restore-image" data-restore="<?php echo $default; ?>" data-target="#<?php echo $option_name; ?>" data-preview=".wc-gallery-preview-image">Default</a>
		<a class="button wc-gallery-delete-image" data-target="#<?php echo $option_name; ?>" data-preview=".wc-gallery-preview-image">Delete</a>
		<p class="wc-gallery-preview-image"<?php echo $style; ?>><img src="<?php echo esc_attr($val); ?>" /></p>
		<?php if ( isset( $description ) && !empty( $description ) ) : ?>
			<p class="description"><?php echo $description; ?></p>
		<?php endif; ?>
	</div>
	<?php
}

function wc_gallery_options_display_checkbox_field( $args ) {
	extract( $args );

	$val = get_option( $option_name, $default );
	?>

	<?php if ( isset( $label ) ) : ?>
		<label for="<?php echo esc_attr($option_name); ?>">
	<?php endif; ?>

	<input name="<?php echo $option_name; ?>" id="<?php echo $option_name; ?>" type="checkbox" value="1" <?php checked( true, $val ); ?> />

	<?php if ( isset( $label ) ) : ?>
		&nbsp;<?php echo $label; ?></label>&nbsp;
	<?php endif; ?>

	<?php if ( isset( $description ) && !empty( $description ) ) : ?>
		<p class="description"><?php echo $description; ?></p>
	<?php endif; ?>
	<?php
}

/**
 * Display positive pixel input field.
 *
 * @since 3.5.2
 * @access public
 *
 * @param array $args 
 * @return void
 */
function wc_gallery_options_display_positive_number_input_field( $args ) {
	extract( $args );

	$val = get_option( $option_name, $default );
	$val = preg_replace("/[^0-9]/", "",$val);
	?>

	<?php if ( isset( $label ) ) : ?>
		<label for="<?php echo $option_name; ?>"><?php echo $label; ?></label>&nbsp;
	<?php endif; ?>

	<input type="number" min="0" class="small-text" name="<?php echo esc_attr($option_name); ?>" id="<?php echo $option_name; ?>" value="<?php echo esc_attr($val); ?>" />&nbsp;

	<?php if ( isset( $description ) && !empty( $description ) ) : ?>
		<p class="description"><?php echo $description; ?></p>
	<?php endif; ?>

	<?php
}

/*
 * Sanitize Options
 */
function wc_gallery_options_find_sanitize_callback( $type ) {
	switch ( $type ) {
		case 'color' :
			return 'wc_gallery_options_sanitize_hex_color';
		case 'image' :
			return 'esc_url_raw';
		case 'checkbox' :
			return 'wc_gallery_options_sanitize_checkbox';
		case 'positive_number' :
			return 'wc_gallery_options_sanitize_positive_number';
	}

	return '';
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
function wc_gallery_options_sanitize_positive_number( $value ) {
	$value = preg_replace("/[^0-9]/", "",$value);
	$value = intval( $value );

	if ( empty( $value ) )
		$value = '0';

	return $value;
}

function wc_gallery_options_sanitize_checkbox( $val ) {
	if ( $val )
		return 1;
	else
		return 0;
}

function wc_gallery_options_sanitize_hex_color( $color ) {
	if ( '' === $color )
		return '';

	if ( preg_match('|^#([A-Fa-f0-9]{3}){1,2}$|', $color ) )
		return $color;

	return null;
}

/*
 * Misc
 */
function wc_gallery_remember_last_options_tab() {
	global $page;

	if ( isset( $_GET['page'] ) && $_GET['page'] == 'wc-gallery-options' ) {
		if ( isset( $_GET['tab'] ) && ! empty( $_GET['tab'] ) ) {
			setcookie(WC_GALLERY_PREFIX . 'last_tab_visited', $_GET['tab'], time() + ( 2 * DAY_IN_SECONDS ) );
		}
	}
}
add_action( 'admin_init', 'wc_gallery_remember_last_options_tab' );
