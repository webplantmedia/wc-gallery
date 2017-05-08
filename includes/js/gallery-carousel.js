/**
 * @author Chris Baldelomar
 * @website http://wordpresscanvas.com/
 */

( function( $ ) {
	"use strict";

	var body = $( 'body' ),
		_window = $( window );

	var initGallery = function() {
		if( jQuery().owlCarousel) {
			$('.wcowlcarousel').each( function() {
				var $this = $(this);
				if ( $this.is(':hidden') ) {
					return;
				}

				if ( $this.hasClass( 'wcowlcarousel-is-active' ) ) {
					return;
				}

				var $owl = $this.children('.owl-carousel');
				var columns = parseInt( $owl.data('columns') );
				var columnsTablet = columns - 1;
				var columnsPhone = columns - 2;
				var gutterWidth = $owl.data('gutterWidth');
				var hideControls = $owl.data('hideControls');
				var showNav = hideControls ? false : true;
				var containerWidth = $this.width();

				$this.addClass('wcowlcarousel-is-active');

				gutterWidth = parseInt( gutterWidth );
				if ( 1 > columnsTablet ) {
					columnsTablet = 1;
				}
				if ( 1 > columnsPhone ) {
					columnsPhone = 1;
				}

				if ( $this.hasClass('wcowlautowidth') ) {
					$owl.owlCarousel({
						margin: gutterWidth,
						loop: true,
						dots: false,
						autoWidth: true,
						responsive: {
							0:{
								nav: false,
							},
							569:{
								nav: showNav,
							}
						}
					});
				}
				else if ( $this.hasClass('wcowlcolumns') ) {
					$owl.owlCarousel({
						margin: gutterWidth,
						loop: true,
						dots: false,
						items: columns,
						responsive: {
							0:{
								nav: false
							},
							569:{
								nav: showNav
							}
						}
					});
				}
				else if ( $this.hasClass('wcowlslider') ) {
					$owl.owlCarousel({
						margin: 0,
						loop: true,
						dots: false,
						items: 1,
						autoHeight:true,
						responsive: {
							0:{
								nav: false
							},
							569:{
								nav: showNav
							}
						}
					});
				}
			});
		}
	};

	initGallery();

	// Triggers re-layout on infinite scroll
	$( document.body ).on( 'post-load', function () {
		initGallery();
	}); 

} )( jQuery );
