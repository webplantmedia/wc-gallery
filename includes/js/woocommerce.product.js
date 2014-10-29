/**
 * @author Chris Baldelomar
 * @website http://wordpresscanvas.com/
 */


( function( $ ) {
	"use strict";

	$(document).ready(function(){
		if( jQuery().magnificPopup) {
			$("a[data-rel^='prettyPhoto']").magnificPopup({
				gallery: {
					enabled: true
				},
				type:'image',
			});
			$("a[rel^='prettyPhoto']").magnificPopup({
				gallery: {
					enabled: true
				},
				type:'image',
			});
		}
	});
} )( jQuery );
