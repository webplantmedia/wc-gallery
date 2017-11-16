<?php
/*
Plugin Name: Galleries by Angie Makes
Plugin URI: http://angiemakes.com/feminine-wordpress-blog-themes-women/
Description: Extend WordPress galleries to display masonry gallery, carousel gallery, and slider gallery
Author: Chris Baldelomar
Author URI: http://angiemakes.com/
Version: 1.65
License: GPLv2 or later
*/

define( 'WC_GALLERY_VERSION', '1.65' );
define( 'WC_GALLERY_PREFIX', 'wc_gallery_' );
define( '_WC_GALLERY_PREFIX', '_wc_gallery_' );
define( 'WC_GALLERY_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'WC_GALLERY_CURRENT_VERSION', get_option( WC_GALLERY_PREFIX . 'current_version' ) );
define( 'WC_GALLERY_PLUGIN_BASENAME', plugin_basename( plugin_dir_path( realpath( __FILE__ ) ) . 'wc-gallery.php' ) );

global $wc_gallery_options;
global $wc_gallery_theme_support;

$wc_gallery_theme_support = array(
	'theme_reset' => true,
	'icon' => array(
		'size_w' => '48',
		'size_h' => '48',
		'crop' => true,
		'enable' => true,
	),
	'square' => array(
		'size_w' => '300',
		'size_h' => '300',
		'crop' => true,
		'enable' => true,
	),
	'small' => array(
		'size_w' => '250',
		'size_h' => '9999',
		'crop' => false,
		'enable' => true,
	),
	'standard' => array(
		'size_w' => '550',
		'size_h' => '9999',
		'crop' => false,
		'enable' => true,
	),
	'big' => array(
		'size_w' => '800',
		'size_h' => '9999',
		'crop' => false,
		'enable' => true,
	),
	'fixedheightsmall' => array(
		'size_w' => '9999',
		'size_h' => '180',
		'crop' => false,
		'enable' => true,
	),
	'fixedheightmedium' => array(
		'size_w' => '9999',
		'size_h' => '300',
		'crop' => false,
		'enable' => true,
	),
	'fixedheight' => array(
		'size_w' => '9999',
		'size_h' => '500',
		'crop' => false,
		'enable' => true,
	),
	'carouselsmall' => array(
		'size_w' => '210',
		'size_h' => '150',
		'crop' => true,
		'enable' => true,
	),
	'carousel' => array(
		'size_w' => '400',
		'size_h' => '285',
		'crop' => true,
		'enable' => true,
	),
	'slider' => array(
		'size_w' => '1100',
		'size_h' => '500',
		'crop' => true,
		'enable' => true,
	),
);

require_once( plugin_dir_path( __FILE__ ) . 'includes/vendors/wpc-settings-framework/init.php' );
require_once( dirname(__FILE__) . '/includes/functions.php' ); // Adds basic filters and actions
require_once( dirname(__FILE__) . '/includes/options.php' ); // define options array
require_once( dirname(__FILE__) . '/includes/scripts.php' ); // Adds plugin JS and CSS
// require_once( dirname(__FILE__) . '/includes/widgets.php' ); // include any widgets
