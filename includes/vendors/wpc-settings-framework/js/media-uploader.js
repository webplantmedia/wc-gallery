/**
 * @author Chris Baldelomar
 * @website http://webplantmedia.com/
 */


(function($) {
	"use strict";

	var $body = $("body"), file_frame = [], media = wp.media;

	//fetch preExisting selection of galleries. change the gallery state based on wheter we got a selection or not to "Edit gallery" or "AAdd gallery"
	var fetchSelection = function(ids, options) {
		if(typeof ids === 'undefined') {
			return; //<--happens on multi_image insert for modal group
		}
	
		var id_array = ids.split(','),
			args = {orderby: "post__in", order: "ASC", type: "image", perPage: -1, post__in:id_array},
			attachments = wp.media.query( args ),
			selection = new wp.media.model.Selection( attachments.models, {
				props:    attachments.props.toJSON(),
				multiple: true
			});
			
			
		if(options.state === 'gallery-library' && id_array.length &&  !isNaN(parseInt(id_array[0],10))) {
			options.state = 'gallery-edit';
		}
		return selection;
	};

	$body.on('click', '.wpcsf-image-upload', function( event ) {
		event.preventDefault();
		
		var clicked = $(this),
			options = clicked.data(),
			parent = clicked.parent(),
			target = parent.find(options.target),
			preview = parent.find(options.preview), // will not find <div> tag inside of <p>
			prefill = fetchSelection(target.val(), options),
			frame_key = _.random(0, 999999999999999999);
			//set vars so we know that an editor is open

		// If the media frame already exists, reopen it.
		if ( file_frame[frame_key] ) {
			file_frame[frame_key].open();
			return;
		}
		
		// Create the media frame.
		file_frame[frame_key]  = wp.media({
			frame: options.frame,
			state: options.state,
			library: { type: 'image' },
			button: { text: options.button },
			className: options['class'],
			selection: prefill
		});

		if ( 'wpc_settings_framework_insert_single' === options.state ) {
			// add the single insert state
			file_frame[frame_key].states.add([
				// Main states.
				new media.controller.Library({
					id:         'wpc_settings_framework_insert_single',
					title: clicked.data( 'title' ),
					priority:   20,
					toolbar:    'select',
					filterable: 'uploaded',
					library:    media.query( file_frame[frame_key].options.library ),
					multiple:   false,
					editable:   true,
					displayUserSettings: false,
					displaySettings: true,
					allowLocalEdits: true
					// AttachmentView: media.view.Attachment.Library
				})
			]);
		}
		else if ( 'wpc_settings_framework_insert_multi' === options.state ) {
			// add the single insert state
			file_frame[frame_key].states.add([
				new media.controller.Library({
					id:         'wpc_settings_framework_insert_multi',
					title: clicked.data( 'title' ),
					priority:   20,
					toolbar:    'select',
					filterable: 'uploaded',
					library:    media.query( file_frame[frame_key].options.library ),
					multiple:   'add',
					editable:   true,
					displayUserSettings: false,
					displaySettings: false,
					allowLocalEdits: true
					// AttachmentView: media.view.Attachment.Library
				})
			]);
		}

		// When an image is selected, run a callback. 
		// Bind to various events since single insert and multiple trigger on different events and work with different data
		file_frame[frame_key].on( 'select update insert', function(e) {
			var selection, state = file_frame[frame_key].state();
			
			// multiple items
			if(typeof e !== 'undefined') {
				selection = e;
			}
			// single item
			else {
				selection = state.get('selection');
			}
			
			var values , display, element, preview_html= "", preview_img;
				
			values = selection.map( function( attachment ) {
				element = attachment.toJSON();
				
				if ( 'url' === options.fetch ) {
					display = state.display( attachment ).toJSON();
					
					if ( 'undefined' === typeof element.sizes ) {
						preview_img = element.url;
						preview_html += "<img src='"+preview_img+"' />";
					}
					else if ( ( 'string' === typeof options.imgsize ) && ( 'object' === typeof element.sizes[ options.imgsize ] ) ) {
						preview_img = element.sizes[ options.imgsize ].url;
						preview_html += "<img src='"+preview_img+"' />";
					}
					else {
						preview_img = element.sizes[display.size].url;
						preview_html += "<img src='"+preview_img+"' />";
					}
					
					return preview_img;
				}
				else if(options.fetch === 'id') {
					preview_img = typeof element.sizes.thumbnail !== 'undefined'  ? element.sizes.thumbnail.url : element.url ;
					preview_html += "<img src='"+preview_img+"' />";
					
					return element[options.fetch];
				}
				else {
					return element.url;
				}
			});
			
			if ( target.length ) {
				target.val( values.join(',') ).trigger('change');

				// triggers change in customizer
				target.keyup();
			}
			
			if ( preview.length ) {
				preview.html( preview_html ).show();
			}
		});

		// Finally, open the modal
		file_frame[frame_key].open();
	})
	.on('click', '.wpcsf-restore-image', function( e ) {
		e.preventDefault();

		var clicked = $(this),
			options = clicked.data(),
			parent  = clicked.parent(),
			target  = parent.find(options.target),
			preview = parent.find(options.preview);

		$(target).val(options.restore);

		if ( preview.length && options.restore.length ) {
			$(preview).html('<img src="'+options.restore+'" />').show();
		}
		else {
			$(preview).html("").hide();
		}

		$(target).keyup();
	})
	.on('click', '.wpcsf-delete-image', function( e ) {
		e.preventDefault();

		var clicked = $(this),
			options = clicked.data(),
			parent  = clicked.parent(),
			target  = parent.find(options.target),
			preview = parent.find(options.preview);

		$(target).val('');

		if ( preview.length ) {
			$(preview).html("").hide();
		}

		$(target).keyup();
	})
})(jQuery);
