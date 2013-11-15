/**
 * @author Chris Baldelomar
 * @website http://wordpresscanvas.com/
 */


( function( $ ) {
	"use strict";

	$(document).ready(function(){
		if( jQuery().magnificPopup) {
			$("a[rel^='prettyPhoto']").magnificPopup({
				gallery: {
					enabled: true
				},
				type:'image',
			});
		}
	});
} )( jQuery );
