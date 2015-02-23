<?php
/*----------------------------------------------------------------------------*
 * Dashboard and Administrative Functionality
 *
 * TODO: You must change the namespace here, and in the two class files.
 *----------------------------------------------------------------------------*/

if ( is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {

	require_once( 'class-wpc-settings-framework.php' );

	add_action( 'plugins_loaded', array( 'WC_Gallery_Settings_Framework', 'get_instance' ) );
}
