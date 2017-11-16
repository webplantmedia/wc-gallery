/**
 * @author Chris Baldelomar
 * @website http://wordpresscanvas.com/
 */

( function( $ ) {
	"use strict";

	var body = $( 'body' ),
		_window = $( window );

	var initGallery = function() {
		if( jQuery().wcflexslider) {
			$('.wcflexslider-container').each( function() {
				var $this = $(this);
				if ( $this.is(':hidden') ) {
					return;
				}

				if ( $this.hasClass( 'wcflexslider-is-active' ) ) {
					return;
				}

				var $flex = $(this).children('.gallery.wcflexslider');
				var columns = parseInt( $flex.data('columns') );
				var columnsTablet = columns - 1;
				var columnsPhone = columns - 2;
				var gutterWidth = $flex.data('gutterWidth');
				var hideControls = $flex.data('hideControls');
				var showNav = hideControls ? false : true;
				var containerWidth = $this.width();

				$this.addClass('wcflexslider-is-active');

				gutterWidth = parseInt( gutterWidth );

				imagesLoaded( $flex, function() {
					if ( $flex.hasClass('wcslider') ) {
						$flex.wcflexslider({
							prevText: "",
							nextText: "",
							smoothHeight: true,
							slideshow: false,
							directionNav: showNav,
							animation:"fade"
						});
					}
					else if ( $flex.hasClass('wcslider2') ) {
						$flex.wcflexslider({
							prevText: "",
							nextText: "",
							smoothHeight: true,
							slideshow: false,
							directionNav: showNav,
							animation:"slide"
						});
					}
					else if ( $flex.hasClass('wcsliderauto') ) {
						$flex.wcflexslider({
							prevText: "",
							nextText: "",
							smoothHeight: true,
							slideshow: true,
							directionNav: showNav,
							animation:"slide"
						});
					}
					else if ( $flex.hasClass('wccarousel') ) {
						$flex.wcflexslider({
							prevText: "",
							nextText: "",
							smoothHeight: false,
							slideshow: false,
							directionNav: showNav,
							animation: "slide",
							animationLoop: false,
							itemWidth: 270,
							itemMargin: gutterWidth
						});
					}
					else if ( $flex.hasClass('wcslider3bottomlinks') || $flex.hasClass('wcslider4bottomlinks') ) {
						$flex.wcflexslider({
							prevText: "",
							nextText: "",
							smoothHeight: true,
							slideshow: true,
							directionNav: showNav,
							animation:"slide"
						});
					}
				});
			});
		}
	};

	initGallery();

	// Triggers re-layout on infinite scroll
	$( document.body ).on( 'post-load', function () {
		initGallery();
	}); 

} )( jQuery );
