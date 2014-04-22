/**
 * @author Chris Baldelomar
 * @website http://wordpresscanvas.com/
 */


( function( $ ) {
	"use strict";

	var body = $( 'body' ),
		_window = $( window );

	var calculateGrid = function($container) {
		var columns = parseInt( $container.data('columns') );
		var gutterWidth = $container.data('gutterWidth');
		var containerWidth = $container.width();

		if ( isNaN( gutterWidth ) ) {
			gutterWidth = .020;
		}
		else if ( gutterWidth > 0.05 || gutterWidth < 0 ) {
			gutterWidth = .020;
		}

		if ( columns > 1 ) {
			if ( containerWidth < 568 ) {
				columns -= 2;
				if ( columns > 4 ) {
					columns = 4;
				}
			}
			/* else if ( containerWidth < 768 ) { 
				columns -= 1;
			} */

			if ( columns < 2 ) {
				columns = 2;
			}
		}

		var gutterWidth = Math.floor( containerWidth * gutterWidth );

		var allGutters = gutterWidth * ( columns - 1 );
		var contentWidth = containerWidth - allGutters;

		var columnWidth = Math.floor( contentWidth / columns );

		return {columnWidth: columnWidth, gutterWidth: gutterWidth, columns: columns};
	}

	var runMasonry = function( duration, $container) {
		var $postBox = $container.children('.gallery-item');

		var o = calculateGrid($container);

		$postBox.css({'width':o.columnWidth+'px', 'margin-bottom':o.gutterWidth+'px', 'padding':'0'});

		$container.masonry( {
			itemSelector: '.gallery-item',
			columnWidth: o.columnWidth,
			gutter: o.gutterWidth,
			transitionDuration: duration 
		} );
	}


	$(document).ready(function(){
		$('.gallery-masonry').each( function() {
			var $container = $(this);

			imagesLoaded( $container, function() {
				runMasonry(0, $container);

				$container.css('visibility', 'visible');
			});

			$(window).resize(function() {
				runMasonry(0, $container);
			});
		});


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
