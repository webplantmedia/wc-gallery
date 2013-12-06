<?php
/*
Plugin Name: WordPress Canvas Gallery
Plugin URI: http://wordpresscanvas.com/features/gallery/
Description: Extend WordPress galleries to display masonry gallery, carousel gallery, and slider gallery
Author: Chris Baldelomar
Author URI: http://webplantmedia.com/
Version: 1.7
License: GPLv2 or later
*/

function wc_gallery_using_woocommerce() {
	return in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) );
}

define( 'WC_GALLERY_VERSION', '1.7' );
define( 'WC_GALLERY_PREFIX', 'wc_gallery_' );
define( '_WC_GALLERY_PREFIX', '_wc_gallery_' );
define( 'WC_GALLERY_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'WC_GALLERY_USING_WOOCOMMERCE', wc_gallery_using_woocommerce() );

global $wc_gallery_options;

require_once( dirname(__FILE__) . '/includes/functions.php' ); // Adds basic filters and actions
require_once( dirname(__FILE__) . '/includes/options.php' ); // define options array
require_once( dirname(__FILE__) . '/includes/settings.php' ); // Adds settings
require_once( dirname(__FILE__) . '/includes/scripts.php' ); // Adds plugin JS and CSS
require_once( dirname(__FILE__) . '/includes/widgets.php' ); // include any widgets

register_activation_hook( __FILE__, 'wc_gallery_options_activation_hook' );
