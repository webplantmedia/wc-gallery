/**
 * @author Chris Baldelomar
 * @website http://wordpresscanvas.com/
 */


(function ($) {
	"use strict";

	$(document).ready(function() {
		$('.wpcsf-color-field').wpColorPicker();

		$('.wpcsf-order-show-hide').sortable({ axis: "y" });

		var $nav = $('.wpcsf-navigation');
		if ( $nav.length > 0 ) {
			var $navTab = $nav.find('.nav-tab');
			var $pageTab = $('.wpcsf-tab');

			var cookieName = $nav.data('cookieName');

			$navTab.click( function( event ) {
				event.preventDefault();

				var $this = $(this);

				var target = $this.data('target');
				if ( 'string' == typeof target ) {
					$navTab.removeClass('nav-tab-active');
					$pageTab.removeClass('wpcsf-active-tab');

					$navTab.filter('#nav-'+target).addClass('nav-tab-active');
					$pageTab.filter('#'+target).addClass('wpcsf-active-tab');

					if ( 'string' == typeof cookieName ) {
						wpCookies.set( cookieName, target, 7 * 24 * 60 * 60 );
					}
				}
				
				$('.wpcsf-tab-wrap .updated.settings-error').hide();

				return false;
			});
		}
	});
}(jQuery));
