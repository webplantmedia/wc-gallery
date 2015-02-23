<?php
wp_reset_vars( array( 'action' ) );

if ( isset( $_GET['updated'] ) && isset( $_GET['page'] ) ) {
	// For backwards compat with plugins that don't use the Settings API and just set updated=1 in the redirect
	add_settings_error( $menu_slug, 'settings_updated', __('Settings saved.'), 'updated' );
}

settings_errors();
?>
