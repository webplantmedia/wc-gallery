/**
 * @author Chris Baldelomar
 * @website http://wordpresscanvas.com/
 */


( function( $ ) {
	"use strict";

	$(document).ready(function(){
		if( jQuery().fancybox) {
			$("a[rel^='prettyPhoto']").fancybox({
				openEffect  : 'none',
				closeEffect : 'none',
				prevEffect : 'none',
				nextEffect : 'none',
				afterShow : function() {
					if ( parseInt( Local.pinitHoverOnImage ) ) {
						var $fancybox = $('.fancybox-type-image .fancybox-outer');
						$fancybox.pinitImageHover({
							imgSelector: '.fancybox-image',
							popupImage: true
						});
						// sometimes the old position will be present on the next image.
						// so lets recalculate position
						$fancybox.pinitImageHover('refresh');
					}
				}
			});

		}
	});
} )( jQuery );
