<div class="wrap wpcsf-wrap">
	<?php screen_icon(); ?>
	<h2 id="theme-options-heading"><?php echo esc_html( $o['page_title'] ); ?></h2>

	<?php if ( isset( $_GET['settings-updated'] ) ) : ?>
		<div id="message" class="updated"><p><strong><?php _e( 'Settings saved.', 'wpc-settings-framework' ) ?></strong></p></div>
	<?php endif; ?>

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
		do_settings_sections( $menu_slug );
		?>

		<p class="submit">
			<?php submit_button( null, 'primary', 'submit', false ); ?>
			<?php //submit_button( 'Restore Default Settings', 'delete', 'submit', false ); ?>
		</p>
	</form>
</div>
