/**
 * @author Chris Baldelomar
 * @website http://wordpresscanvas.com/
 */

( function( $ ) {
	"use strict";

	var body = $( 'body' ),
		_window = $( window );

	var galleryPopup = function() {
		if( jQuery().magnificPopup) {
			$('.gallery-link-file').each( function() {
				var $this = $(this);

				if ( $this.hasClass( 'magnificpopup-is-active' ) ) {
					return;
				}

				$this.magnificPopup({
					delegate: '.gallery-item:not(.clone) .gallery-icon a',
					gallery: {
						enabled: true
					},
					type:'image',
					image: {
						titleSrc: function(item) {
							var caption = $(item.el).parent().next('.gallery-caption').html();
							if ( 'string' == typeof caption && caption.length > 0 )
								return caption;

							return '';
						}
					}
				});

				$this.addClass( 'magnificpopup-is-active' );
			});
		}

	};

	galleryPopup();

	// Triggers re-layout on infinite scroll
	$( document.body ).on( 'post-load', function () {
		galleryPopup();
	}); 

} )( jQuery );
