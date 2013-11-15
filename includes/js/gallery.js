/**
 * @author Chris Baldelomar
 * @website http://wordpresscanvas.com/
 */


( function( $ ) {
	"use strict";

	var body = $( 'body' ),
		_window = $( window );

	$(document).ready(function(){
		if ( $.isFunction( $.fn.masonry ) ) {
			var $gallery = $('.gallery-masonry').masonry( {
				columnWidth: '.gallery-item',
				itemSelector: '.gallery-item',
				gutter: 0
			} );

			_window.load(function() {
				$gallery.masonry();
			});
		}

		if( jQuery().magnificPopup) {
			$('.gallery-link-file').each( function() {
				$(this).magnificPopup({
					delegate: '.gallery-icon a',
					gallery: {
						enabled: true
					},
					type:'image',
					image: {
						titleSrc: function(item) {
							return $(item.el).parent().next('.gallery-caption').html();
						}
					}
				});
			});
		}

		if( jQuery().wcflexslider) {
			$(window).load(function() {
				$('.gallery.wcslider.wcflexslider').wcflexslider({
					smoothHeight: false,
					slideshow: false,
					animation:"fade"
				});
				$('.gallery.wccarousel.wcflexslider').wcflexslider({
					smoothHeight: false,
					animation: "slide",
					slideshow: false
				});
			});
		}
	});
} )( jQuery );
