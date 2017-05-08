/**
 * @author Chris Baldelomar
 * @website http://wordpresscanvas.com/
 */

( function( $ ) {
	"use strict";

	var body = $( 'body' ),
		_window = $( window );

	var calculateGrid = function($container) {
		var windowWidth = _window.width();
		var columns = parseInt( $container.data('columns') );
		var gutterWidth = $container.data('gutterWidth');
		// need to return exact decimal width
		var containerWidth = Math.floor($container[0].getBoundingClientRect().width);

		if ( isNaN( gutterWidth ) ) {
			gutterWidth = 5;
		}
		else if ( gutterWidth > 30 || gutterWidth < 0 ) {
			gutterWidth = 5;
		}

		if ( windowWidth <= 568 ) {
			columns = 1
		}

		gutterWidth = parseInt( gutterWidth );

		var allGutters = gutterWidth * ( columns - 1 );
		var contentWidth = containerWidth - allGutters;

		var columnWidth = Math.floor( contentWidth / columns );

		return {columnWidth: columnWidth, gutterWidth: gutterWidth, columns: columns};
	}

	var runMasonry = function( duration, $container, $posts, method ) {
		var o = calculateGrid($container);

		if ( o.columns == 1 ) {
			if ( $container.hasClass('masonry') ) {
				$container.masonry('destroy');
			}

			$container.removeAttr("style");
			$container.children().removeAttr("style");
			$container.css('height', 'auto');

			return;
		}
		else if ( 'layout' == method ) {
			$container.masonry('layout');

			return;
		}
		else {
			$posts.css({'width':o.columnWidth+'px', 'margin-bottom':o.gutterWidth+'px', 'padding':'0'});
			$container = $container.masonry( {
				itemSelector: '.gallery-item',
				columnWidth: o.columnWidth,
				gutter: o.gutterWidth,
				transitionDuration: duration
			} );

			return;
		}
	}

	var initGallery = function() {
		$('.gallery-masonry').each( function() {
			var $container = $(this);
			var $posts = $container.children('.gallery-item');

			$posts.css({'visibility':'hidden','position':'relative'}).addClass('wc-gallery-loading');

			$.each( $posts, function( index, value ) {
				var $post = $(this);
				var $imgs = $post.find('img');

				if ( $imgs.length ) {
					$post.imagesLoaded()
						.always( function( instance ) {
							$post.css('visibility', 'visible').removeClass('wc-gallery-loading');
							runMasonry(0, $container, $posts, 'layout');
						});
				}
				else {
					$post.css('visibility', 'visible').removeClass('wc-gallery-loading');
				}
			});

			runMasonry(0, $container, $posts, 'masonry');

			$(window).resize(function() {
				runMasonry(0, $container, $posts, 'masonry');
			}); 
		});

	};

	initGallery();

	// Triggers re-layout on infinite scroll
	$( document.body ).on( 'post-load', function () {
		initGallery();
	}); 

} )( jQuery );
