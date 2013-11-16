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
					prevText: "",
					nextText: "",
					smoothHeight: false,
					slideshow: false,
					animation:"fade"
				});
				$('.gallery.wcslider2.wcflexslider').wcflexslider({
					prevText: "",
					nextText: "",
					smoothHeight: true,
					slideshow: false,
					animation:"slide"
				});
				$('.gallery.wccarousel.wcflexslider').wcflexslider({
					prevText: "",
					nextText: "",
					smoothHeight: false,
					slideshow: false,
					animation: "slide",
					animationLoop: false,
					itemWidth: 270,
					itemMargin: 5
				});
			});
		}
	});
} )( jQuery );
