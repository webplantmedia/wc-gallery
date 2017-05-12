<?php
$active_tab = null;
$cookie_name = $this->plugin_prefix . 'wpcsf_active_tab';

// restore last tab visited
if ( isset( $_GET['wpcsf_active_tab'] ) && ! empty( $_GET['wpcsf_active_tab'] ) ) {
	$tab_check = $_GET['wpcsf_active_tab'];
	if ( isset( $this->tabs[ $menu_slug ][ $tab_check ] ) ) {
		$active_tab = $tab_check;
	}
}

if ( empty( $active_tab ) && isset( $_COOKIE[ $cookie_name ] ) && ! empty( $_COOKIE[ $cookie_name ] ) ) {
	$last_tab = $_COOKIE[ $cookie_name ];
	if ( isset( $this->tabs[ $menu_slug ][ $last_tab ] ) ) {
		$active_tab = $last_tab;
	}
}
?>
<div class="wrap wpcsf-wrap wpcsf-tab-wrap">
	<?php screen_icon(); ?>
	<?php
		$links = array();
		foreach( $this->wp_settings_tabs[ $menu_slug ] as $tab_id => $tab ) :
			$tab_title = 'No Title';
			if ( isset( $this->tabs[ $menu_slug ][ $tab_id ]['title'] ) ) {
				$tab_title = $this->tabs[ $menu_slug ][ $tab_id ]['title'];
			}
			if ( empty( $active_tab ) || $tab_id == $active_tab ) {
				$active_tab = $tab_id;

				$links[] = "<a id='nav-{$tab_id}' class='nav-tab nav-tab-active' data-target='{$tab_id}' href='#'>{$tab_title}</a>";
			}
			else {
				$links[] = "<a id='nav-{$tab_id}' class='nav-tab' data-target='{$tab_id}' href='#'>{$tab_title}</a>";
			}
		endforeach;
	?>
	<h2 class="wpcsf-navigation nav-tab-wrapper" data-cookie-name="<?php echo $cookie_name; ?>">
	<?php echo implode( '', $links ); ?>
	</h2>

	<?php require( 'settings-error.php' ); ?>

	<form id="wpcsf-options" method="post" action="options.php">
		<?php
		// settings_fields( $option_group )
		// @option_group A settings group name. This should match the group name used in register_setting()
		settings_fields( $o['option_group'] );

		// do_settings_fields( $page, $section )
		// @page Slug title of the admin page whose settings fields you want to show, should match the group name used in add_settings_section()
		// @section Slug title of the settings section whose fields you want to show. This should match the section ID used in add_settings_section()
		//do_settings_fields( 'webpm-select-template', 'webpm-template-section' );

		// do_settings_sections( $page ) 
		// The slug name of the page whose settings sections you want to output. This should match the page name used in add_settings_section()
		global $wp_settings_sections, $wp_settings_fields;

		if ( ! isset( $wp_settings_sections[$menu_slug] ) )
			return;

		foreach ( (array) $this->wp_settings_tabs[$menu_slug] as $tab_id => $tab ) {
			$class = array();
			$class[] = 'wpcsf-tab';
			if ( $active_tab == $tab_id ) {
				$class[] = 'wpcsf-active-tab';
			}
			echo "<div class='".implode( $class, ' ' )."' id='{$tab_id}'>\n";

			foreach( $tab as $section ) {
				if ( $section['title'] )
					echo "<h3>{$section['title']}</h3>\n";

				if ( ! isset( $wp_settings_fields ) || !isset( $wp_settings_fields[$menu_slug] ) || !isset( $wp_settings_fields[$menu_slug][$section['id']] ) )
					continue;

				echo '<table class="form-table">';
				do_settings_fields( $menu_slug, $section['id'] );
				echo '</table>';
			}

			echo '</div>';
		}
		?>

		<p class="submit">
			<?php submit_button( null, 'primary', 'submit', false ); ?>
			<?php submit_button( 'Restore Default Settings', 'delete', 'wpcsf_reset_options_' . $menu_slug, false, array( 'onclick' => 'return confirm("Are you sure you want to reset your settings?");' ) ); ?>
		</p>
	</form>
</div>
