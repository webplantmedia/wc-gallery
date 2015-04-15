<?php
/*
Plugin Name: WP Canvas - Gallery
Plugin URI: http://wordpresscanvas.com/features/gallery/
Description: Extend WordPress galleries to display masonry gallery, carousel gallery, and slider gallery
Author: Chris Baldelomar
Author URI: http://webplantmedia.com/
Version: 1.39
License: GPLv2 or later
*/

function wc_gallery_using_woocommerce() {
	return in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) );
}

define( 'WC_GALLERY_VERSION', '1.39' );
define( 'WC_GALLERY_PREFIX', 'wc_gallery_' );
define( '_WC_GALLERY_PREFIX', '_wc_gallery_' );
define( 'WC_GALLERY_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'WC_GALLERY_USING_WOOCOMMERCE', wc_gallery_using_woocommerce() );
define( 'WC_GALLERY_CURRENT_VERSION', get_option( WC_GALLERY_PREFIX . 'current_version' ) );

global $wc_gallery_options;
global $wc_gallery_theme_support;

$wc_gallery_theme_support = array(
	'icon' => array(
		'size_w' => '48',
		'size_h' => '48',
		'crop' => true,
	),
	'square' => array(
		'size_w' => '300',
		'size_h' => '300',
		'crop' => true,
	),
	'small' => array(
		'size_w' => '250',
		'size_h' => '9999',
		'crop' => false,
	),
	'standard' => array(
		'size_w' => '550',
		'size_h' => '9999',
		'crop' => false,
	),
	'big' => array(
		'size_w' => '800',
		'size_h' => '9999',
		'crop' => false,
	),
	'fixedheightsmall' => array(
		'size_w' => '9999',
		'size_h' => '180',
		'crop' => false,
	),
	'fixedheightmedium' => array(
		'size_w' => '9999',
		'size_h' => '300',
		'crop' => false,
	),
	'fixedheight' => array(
		'size_w' => '9999',
		'size_h' => '500',
		'crop' => false,
	),
	'carouselsmall' => array(
		'size_w' => '210',
		'size_h' => '150',
		'crop' => true,
	),
	'carousel' => array(
		'size_w' => '400',
		'size_h' => '285',
		'crop' => true,
	),
	'slider' => array(
		'size_w' => '1100',
		'size_h' => '500',
		'crop' => true,
	),
);

require_once( plugin_dir_path( __FILE__ ) . 'includes/vendors/wpc-settings-framework/init.php' );
require_once( dirname(__FILE__) . '/includes/functions.php' ); // Adds basic filters and actions
require_once( dirname(__FILE__) . '/includes/options.php' ); // define options array
require_once( dirname(__FILE__) . '/includes/scripts.php' ); // Adds plugin JS and CSS
// require_once( dirname(__FILE__) . '/includes/widgets.php' ); // include any widgets
