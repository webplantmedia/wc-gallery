<?php
/**
 * This file loads the CSS and JS necessary for your shortcodes display
 * @package WC Shortcodes Plugin
 * @since 1.0
 * @author Chris Baldelomar : http://webplantmedia.com
 * @copyright Copyright (c) 2012, AJ Clarke
 * @link http://wpexplorer.com
 * @License: GNU General Public License version 2.0
 * @License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */
if( !function_exists ('wc_gallery_scripts') ) :
	function wc_gallery_scripts() {
		$ver = WC_GALLERY_VERSION;

		if ( get_option( WC_GALLERY_PREFIX . 'enable_gallery_css', true ) ) {
			wp_enqueue_style( 'wc-gallery-style', plugin_dir_url( __FILE__ ) . 'css/style.css', array( ), $ver );
		}

		// jQuery
		wp_enqueue_script('jquery');

		// images loaded
		wp_register_script( 'wordpresscanvas-imagesloaded', plugin_dir_url( __FILE__ ) . 'js/imagesloaded.pkgd.min.js', array (), '4.1.1', true );

		if ( get_option( WC_GALLERY_PREFIX . 'enable_image_popup', true ) ) {
			wp_enqueue_style( 'wc-gallery-popup-style', plugin_dir_url( __FILE__ ) . 'css/magnific-popup.css', array( ), '1.1.0' );
			wp_register_script( 'wc-gallery-magnific', plugin_dir_url( __FILE__ ) . 'js/jquery.magnific-popup.min.js', array ( 'jquery' ), '1.1.0', true );
			wp_register_script( 'wc-gallery-popup', plugin_dir_url( __FILE__ ) . 'js/gallery-popup.js', array ( 'jquery', 'wc-gallery-magnific' ), $ver, true );
		}

		// Gallery Shortcode
		wp_enqueue_style( 'wc-gallery-flexslider-style', plugin_dir_url( __FILE__ ) . 'vendors/flexslider/flexslider.css', array( ), '2.6.1' );
		wp_enqueue_style( 'wc-gallery-owlcarousel-style', plugin_dir_url( __FILE__ ) . 'vendors/owlcarousel/assets/owl.carousel.css', array( ), '2.1.4' );
		wp_enqueue_style( 'wc-gallery-owlcarousel-theme-style', plugin_dir_url( __FILE__ ) . 'vendors/owlcarousel/assets/owl.theme.default.css', array( ), '2.1.4' );
		wp_register_script( 'wc-gallery-flexslider', plugin_dir_url( __FILE__ ) . 'vendors/flexslider/jquery.flexslider-min.js', array ( 'jquery' ), '2.6.1', true );
		wp_register_script( 'wc-gallery-owlcarousel', plugin_dir_url( __FILE__ ) . 'vendors/owlcarousel/owl.carousel.min.js', array ( 'jquery' ), '2.1.4', true );
		wp_register_script( 'wc-gallery-masonry', plugin_dir_url( __FILE__ ) . 'js/gallery-masonry.js', array ( 'jquery', 'wordpresscanvas-imagesloaded', 'jquery-masonry' ), $ver, true );
		wp_register_script( 'wc-gallery-slider', plugin_dir_url( __FILE__ ) . 'js/gallery-slider.js', array ( 'jquery', 'wordpresscanvas-imagesloaded', 'wc-gallery-flexslider' ), $ver, true );
		wp_register_script( 'wc-gallery-carousel', plugin_dir_url( __FILE__ ) . 'js/gallery-carousel.js', array ( 'jquery', 'wordpresscanvas-imagesloaded', 'wc-gallery-owlcarousel' ), $ver, true );
	}
	add_action('wp_enqueue_scripts', 'wc_gallery_scripts');
endif;

function wc_gallery_enqueue_admin_scripts() {
	$screen = get_current_screen();

	if ( 'post' == $screen->base ) {
		wp_register_script( 'wc-gallery-admin-js', plugin_dir_url( __FILE__ ) . 'js/admin.js', array ( 'jquery' ), WC_GALLERY_VERSION, true );
		wp_enqueue_script( 'wc-gallery-admin-js' );
	}
}
add_action('admin_enqueue_scripts', 'wc_gallery_enqueue_admin_scripts' );
