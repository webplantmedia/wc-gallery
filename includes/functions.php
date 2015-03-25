<?php
function wc_gallery_check_supports() {
	global $wc_gallery_theme_support;

	if ( current_theme_supports( 'wpc-gallery' ) ) {
		$supports = get_theme_support( 'wpc-gallery' );

		if ( isset( $supports[0] ) && is_array( $supports[0] ) ) {
			foreach ( $supports[0] as $key => $value ) {
				$wc_gallery_theme_support[ $key ] = $value;
			}
		}
	}
}
add_action( 'init', 'wc_gallery_check_supports' );

/**
 * The Gallery shortcode.
 *
 * This implements the functionality of the Gallery Shortcode for displaying
 * WordPress images on a post.
 *
 * @since 2.5.0
 *
 * @param array $attr Attributes of the shortcode.
 * @return string HTML content to display gallery.
 */
function wc_gallery_shortcode($blank, $attr) {
	$post = get_post();

	static $instance = 0;
	$instance++;

	// We're trusting author input, so let's at least make sure it looks like a valid orderby statement
	if ( isset( $attr['orderby'] ) ) {
		$attr['orderby'] = sanitize_sql_orderby( $attr['orderby'] );
		if ( !$attr['orderby'] )
			unset( $attr['orderby'] );
	}

	extract(shortcode_atts(array(
		'order'      => 'ASC',
		'orderby'    => 'menu_order ID',
		'id'         => $post ? $post->ID : 0,
		'captions'   => 'onhover',
		'captiontype' => 'p',
		'columns'    => 3,
		'gutterwidth' => '5',
		'link'       => 'post',
		'size'       => 'thumbnail',
		'targetsize' => 'large',
		'display'    => 'masonry',
		'customlink' => 'false',
		'bottomspace' => 'default',
		'hidecontrols' => 'false',
		'newtab' => 'false',
		'class'	     => '',
		'include'    => '',
		'exclude'    => ''
	), $attr, 'gallery'));

	$custom_class = trim( $class );
	$valid_caption_types = array( 'p', 'h2', 'h3', 'h4', 'h5', 'h6' );
	$captiontype = in_array( $captiontype, $valid_caption_types ) ? $captiontype : 'p';
	$captiontype = tag_escape($captiontype);

	$id = intval($id);
	if ( 'RAND' == $order )
		$orderby = 'none';

	if ( !empty($include) ) {
		$_attachments = get_posts( array('include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );

		$attachments = array();
		foreach ( $_attachments as $key => $val ) {
			$attachments[$val->ID] = $_attachments[$key];
		}
	} elseif ( !empty($exclude) ) {
		$attachments = get_children( array('post_parent' => $id, 'exclude' => $exclude, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
	} else {
		$attachments = get_children( array('post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
	}

	if ( empty($attachments) )
		return '';

	if ( is_feed() ) {
		$output = "\n";
		foreach ( $attachments as $att_id => $attachment )
			$output .= wp_get_attachment_link($att_id, $size, true) . "\n";
		return $output;
	}

	$columns = intval($columns);

	if ( ! is_numeric( $gutterwidth ) ) {
		$gutterwidth = 5;
	}
	$gutterwidth = (int) $gutterwidth;
	if ( $gutterwidth > 30 || $gutterwidth < 0 ) {
		$gutterwidth = 5;
	}

	$itemwidth = $columns > 0 ? floor(100/$columns) : 100;
	$float = is_rtl() ? 'right' : 'left';

	$selector = "gallery-{$instance}";

	$size_class = sanitize_html_class( $size );

	$showcaptions = 'hide' == $captions ? false : true;
	$customlink = 'true' == $customlink ? true : false;
	$newtab = 'true' == $newtab ? true : false;
	$link_target = '_self';
	if ( $newtab ) {
		$link_target = '_blank';
	}

	$class = array();
	$class[] = 'gallery';
	$class[] = 'wc-gallery-captions-' . $captions;
	if ( ! empty( $custom_class ) )
		$class[] = esc_attr( $custom_class );
	// custom links should not call popup
	if ( ! $customlink )
		$class[] = "gallery-link-{$link}";

	$sliders = array( 'slider', 'slider2', 'sliderauto', 'carousel', 'slider3bottomlinks', 'slider4bottomlinks' );
	$owlcarousel = array( 'owlautowidth', 'owlcolumns', 'owlslider' );

	if ( get_option( WC_GALLERY_PREFIX . 'enable_image_popup', true ) && 'file' == $link ) {
		wp_enqueue_script( 'wc-gallery-popup' );
	}

	if ( in_array( $display, $sliders ) ) {
		wp_enqueue_script( 'wc-gallery-flexslider' );
		wp_enqueue_script( 'wc-gallery' );

		$class[] = 'wc' . $display;
		$class[] = 'wcflexslider';
		if ( 'true' == $hidecontrols ) {
			$class[] = 'wcflexslider-hidecontrols';
		}

		$wrap_class = array();
		$wrap_class[] = 'wcflexslider-container';
		$wrap_class[] = 'wc-gallery-bottomspace-' . $bottomspace;
		$wrap_class[] = 'wc-gallery-clear';

		$output = "";

		$output .= "<div class='".implode( ' ', $wrap_class )."'>";
		$output .= "<div id='$selector' class='".implode( ' ', $class )."' data-gutter-width='".$gutterwidth."' data-columns='".$columns."' data-hide-controls='".$hidecontrols."'>";
		$output .= "<ul class='slides'>";

		list( $attachments, $links ) = wc_gallery_seperate_attachments_links( $attachments, $display );

		$pos = 1;

		foreach ( $attachments as $id => $attachment ) {
			if ( ! $img = wp_get_attachment_image_src( $id, $size ) )
				continue;

			list($src, $width, $height) = $img;
			$alt = trim( strip_tags( get_post_meta($id, '_wp_attachment_image_alt', true) ) ); // Use Alt field first
			$image_output = "<img src='{$src}' width='{$width}' height='{$height}' alt='{$alt}' />";

			if ( ! empty( $link ) ) {
				if ( $customlink ) {
					$url = get_post_meta( $id, _WC_GALLERY_PREFIX . 'custom_image_link', true );
					$image_output = '<a href="'.$url.'" target="'.$link_target.'">' . $image_output . '</a>';
				}
				else if ( 'post' === $link ) {
					$url = get_attachment_link( $id );
					$image_output = '<a href="'.$url.'" target="'.$link_target.'">' . $image_output . '</a>';
				}
				else if ( 'file' === $link ) {
					$url = wp_get_attachment_url( $id );
					$image_output = '<a href="'.$url.'" target="'.$link_target.'">' . $image_output . '</a>';
				}
			}

			$image_meta  = wp_get_attachment_metadata( $id );

			$orientation = '';
			if ( isset( $image_meta['height'], $image_meta['width'] ) )
				$orientation = ( $image_meta['height'] > $image_meta['width'] ) ? 'portrait' : 'landscape';

			$output .= "
				<li class='gallery-item gallery-item-position-".$pos." gallery-item-attachment-".$id." wcflex-slide-item'>
					<div class='gallery-icon {$orientation}'>
						$image_output
					</div>";
			if ( $showcaptions && trim($attachment->post_excerpt) ) {
				$output .= "
					<div class='wp-caption-text gallery-caption' style='width:{$width}px;'>
					<{$captiontype}>
					" . wptexturize($attachment->post_excerpt) . "
					</{$captiontype}>
					</div>";
			}
			$output .= "</li>";

			$pos++;
		}
		$output .= "</ul></div>\n";
		// End of Flex Slider

		if ( ! empty( $links ) ) {
			// Begin Links
			$size = 'wccarousel';
			$size_class = sanitize_html_class( $size );

			$class = array();
			$class[] = 'wc-image-links';
			$class[] = 'wc-gallery-clear';
			$class[] = 'wc-image-links-' . str_replace( array( 'slider3', 'slider4' ), '', $display );
			$class[] = 'wc-image-links-' . $display;
			$class[] = 'wc-image-links-gutter-space-' . $gutterwidth;

			$output .= "<div class='".implode( ' ', $class )."'>";

			$i = 1;
			foreach ( $links as $key => $attachment ) {
				$id = $attachment->ID;
				$image_output = wc_gallery_get_attachment_link( $id, $size, false, false, false, $targetsize, true, $link_target );

				$image_meta  = wp_get_attachment_metadata( $id );

				$orientation = '';
				if ( isset( $image_meta['height'], $image_meta['width'] ) )
					$orientation = ( $image_meta['height'] > $image_meta['width'] ) ? 'portrait' : 'landscape';

				$output .= "<div class='gallery-item gallery-item-".$i." gallery-item-position-".$pos." gallery-item-attachment-".$id."'>";
					$output .= "<div class='gallery-block'>";
						$output .= "
							<div class='gallery-icon {$orientation}'>
								$image_output
							</div>";
						$caption_text = trim($attachment->post_excerpt);

						if ( ! empty( $caption_text ) ) {
							$output .= "
								<div class='wp-caption-text gallery-caption'>
									<h3>
									" . wptexturize($caption_text) . "
									</h3>
								</div>";
						}
					$output .= "</div>";
				$output .= "</div>";
				$i++;
				$pos++;
			}

			$output .= "</div>\n";
			// End of Links
		}

		$output .= "</div>\n";
	}
	else if ( in_array( $display, $owlcarousel ) ) {
		wp_enqueue_script( 'wc-gallery-owlcarousel' );
		wp_enqueue_script( 'wc-gallery' );

		$class[] = 'wc' . $display;
		$class[] = 'wcowlcarousel';
		$class[] = 'wc-gallery-bottomspace-' . $bottomspace;
		$class[] = 'wc-gallery-clear';

		$output = "<div class='".implode( ' ', $class )."'>";
		$output .= "<div id='$selector' class='owl-carousel' data-gutter-width='".$gutterwidth."' data-columns='".$columns."' data-hide-controls='".$hidecontrols."'>";

		$i = 0;
		$pos = 1;
		foreach ( $attachments as $id => $attachment ) {
			if ( ! $img = wp_get_attachment_image_src( $id, $size ) )
				continue;

			list($src, $width, $height) = $img;
			$alt = trim( strip_tags( get_post_meta($id, '_wp_attachment_image_alt', true) ) ); // Use Alt field first
			$image_output = "<img src='{$src}' width='{$width}' height='{$height}' alt='{$alt}' />";

			if ( ! empty( $link ) ) {
				if ( $customlink ) {
					$url = get_post_meta( $id, _WC_GALLERY_PREFIX . 'custom_image_link', true );
					$image_output = '<a href="'.$url.'" target="'.$link_target.'">' . $image_output . '</a>';
				}
				else if ( 'post' === $link ) {
					$url = get_attachment_link( $id );
					$image_output = '<a href="'.$url.'" target="'.$link_target.'">' . $image_output . '</a>';
				}
				else if ( 'file' === $link ) {
					$url = wp_get_attachment_url( $id );
					$image_output = '<a href="'.$url.'" target="'.$link_target.'">' . $image_output . '</a>';
				}
			}

			$image_meta  = wp_get_attachment_metadata( $id );

			$orientation = '';
			if ( isset( $image_meta['height'], $image_meta['width'] ) )
				$orientation = ( $image_meta['height'] > $image_meta['width'] ) ? 'portrait' : 'landscape';

			if ( 'owlautowidth' == $display ) {
				$output .= "<div class='gallery-item item gallery-item-position-".$pos." gallery-item-attachment-".$id."' style='width:".$width."px'>";
			}
			else {
				$output .= "<div class='gallery-item item gallery-item-position-".$pos." gallery-item-attachment-".$id."'>";
			}

			$output .= "<div class='gallery-icon {$orientation}'>$image_output</div>";

			if ( $showcaptions && trim($attachment->post_excerpt) ) {
				$output .= "
					<div class='wp-caption-text gallery-caption'>
					<{$captiontype}>
					" . wptexturize($attachment->post_excerpt) . "
					</{$captiontype}>
					</div>";
			}
			$output .= "</div>";

			$pos++;
		}

		$output .= "</div></div>\n";
	}
	else {
		wp_enqueue_script( 'wc-gallery' );

		// getting rid of float
		$display = 'float' == $display ? 'masonry' : $display;

		$class[] = "gallery-{$display}";
		$class[] = "galleryid-{$id}";
		$class[] = "gallery-columns-{$columns}";
		$class[] = "gallery-size-{$size_class}";
		$class[] = 'wc-gallery-bottomspace-' . $bottomspace;
		$class[] = 'wc-gallery-clear';

		$class = implode( ' ', $class );

		$output = "<div id='$selector' data-gutter-width='".$gutterwidth."' data-columns='".$columns."' class='{$class}'>";

		$i = 0;
		$pos = 1;
		foreach ( $attachments as $id => $attachment ) {
			if ( $customlink ) {
				$image_output = wc_gallery_get_attachment_link( $id, $size, false, false, false, $targetsize, $customlink, $link_target );
			}
			else if ( ! empty( $link ) && 'file' === $link ) {
				$image_output = wc_gallery_get_attachment_link( $id, $size, false, false, false, $targetsize, $customlink, $link_target );
			}
			else if ( ! empty( $link ) && 'none' === $link ) {
				$image_output = wp_get_attachment_image( $id, $size, false );
			}
			else {
				$image_output = wc_gallery_get_attachment_link( $id, $size, true, false, false, 'large', false, $link_target );
			}

			$image_meta  = wp_get_attachment_metadata( $id );

			$orientation = '';
			if ( isset( $image_meta['height'], $image_meta['width'] ) )
				$orientation = ( $image_meta['height'] > $image_meta['width'] ) ? 'portrait' : 'landscape';

			$output .= "<div class='gallery-item gallery-item-position-".$pos." gallery-item-attachment-".$id."'>";
			$output .= "
				<div class='gallery-icon {$orientation}'>
					$image_output
				</div>";
			if ( $showcaptions && trim($attachment->post_excerpt) ) {
				$output .= "
					<div class='wp-caption-text gallery-caption'>
						<{$captiontype}>
						" . wptexturize($attachment->post_excerpt) . "
						</{$captiontype}>
					</div>";
			}
			$output .= "</div>";

			$pos++;
		}

		$output .= "</div>\n";
	}

	return '<div class="wc-gallery">' . $output . '</div>';
}
add_filter( 'post_gallery', 'wc_gallery_shortcode', 10, 2 );

function wc_gallery_seperate_attachments_links( $attachments, $display ) {
	$links = array();

	switch ( $display ) {
		case 'slider3rightlinks' :
		case 'slider3bottomlinks' :
			$i = 3;
			$links[] = array_pop( $attachments );
			$links[] = array_pop( $attachments );
			$links[] = array_pop( $attachments );
			break;
		case 'slider4rightlinks' :
		case 'slider4bottomlinks' :
			$links[] = array_pop( $attachments );
			$links[] = array_pop( $attachments );
			$links[] = array_pop( $attachments );
			$links[] = array_pop( $attachments );
			break;
	}

	if ( empty( $links ) ) {
		return array( $attachments, $links );
	}

	$links = array_reverse( $links );

	return array( $attachments, $links );
}

/**
 * Retrieve an attachment page link using an image or icon, if possible.
 *
 * @since 2.5.0
 * @uses apply_filters() Calls 'wp_get_attachment_link' filter on HTML content with same parameters as function.
 *
 * @param int $id Optional. Post ID.
 * @param string $size Optional, default is 'thumbnail'. Size of image, either array or string.
 * @param bool $permalink Optional, default is false. Whether to add permalink to image.
 * @param bool $icon Optional, default is false. Whether to include icon.
 * @param string|bool $text Optional, default is false. If string, then will be link text.
 * @return string HTML content.
 */
function wc_gallery_get_attachment_link( $id = 0, $size = 'thumbnail', $permalink = false, $icon = false, $text = false, $targetsize = 'large', $customlink = false, $link_target = '_self' ) {
	$id = intval( $id );
	$_post = get_post( $id );

	if ( empty( $_post ) || ( 'attachment' != $_post->post_type ) || ! $url = wp_get_attachment_url( $_post->ID ) )
		return __( 'Missing Attachment' );

	if ( $customlink ) {
		$url = get_post_meta( $_post->ID, _WC_GALLERY_PREFIX . 'custom_image_link', true );
	}
	else if ( $permalink ) {
		$url = get_attachment_link( $_post->ID );
	}
	else if ( $targetsize ) {
		if ( $img = wp_get_attachment_image_src( $_post->ID, $targetsize ) )
			$url = $img[0];
	}

	$post_title = esc_attr( $_post->post_title );

	if ( $text )
		$link_text = $text;
	elseif ( $size && 'none' != $size )
		$link_text = wp_get_attachment_image( $id, $size, $icon );
	else
		$link_text = '';

	if ( trim( $link_text ) == '' )
		$link_text = $_post->post_title;

	return apply_filters( 'wp_get_attachment_link', "<a href='$url' title='$post_title' target='$link_target'>$link_text</a>", $id, $size, $permalink, $icon, $text );
}


/**
 * Outputs a view template which can be used with wp.media.template
 */
function wc_gallery_print_media_templates() {
	$display_types = array( 
		'masonry' => __( 'Masonry', 'wc_gallery' ),
		'slider' => __( 'Slider (Fade)', 'wc_gallery' ),
		'slider2' => __( 'Slider (Slide)', 'wc_gallery' ),
		'sliderauto' => __( 'Slider (Auto Start)', 'wc_gallery' ),
		'owlautowidth' => __( 'Owl Carousel (Auto Width)', 'wc_gallery' ),
		'owlcolumns' => __( 'Owl Carousel (Columns)', 'wc_gallery' ),
		'carousel' => __( 'Carousel (Deprecated)', 'wc_gallery' ),
		'slider3bottomlinks' => __( 'Slider + 3 Bottom Links', 'wc_gallery' ),
		'slider4bottomlinks' => __( 'Slider + 4 Bottom Links', 'wc_gallery' ),
	);
	?>
	<script type="text/html" id="tmpl-wc-gallery-settings">
		<label class="setting">
			<span><?php _e( 'Popup Size', 'wc_gallery' ); ?></span>
			<select class="targetsize" name="targetsize" data-setting="targetsize">
				<?php
				$sizes = apply_filters( 'image_size_names_choose', array(
					'thumbnail' => __('Thumbnail'),
					'medium'    => __('Medium'),
					'large'     => __('Large'),
					'full'      => __('Full Size'),
				) );
				?>

				<?php foreach ( $sizes as $key => $name ) : ?>
					<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $key, 'large' ); ?>><?php echo esc_html( $name ); ?></option>
				<?php endforeach; ?>
			</select>
		</label>

		<label class="setting">
			<span><?php _e( 'Display', 'wc_gallery' ); ?></span>
			<select class="display" name="display" data-setting="display">
				<?php foreach ( $display_types as $key => $value ) : ?>
					<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $key, 'default' ); ?>><?php echo esc_html( $value ); ?></option>
				<?php endforeach; ?>
			</select>
		</label>

		<?php
		$captions = array( 
			'onhover' => __( 'On Image Hover', 'wc_gallery' ),
			'show' => __( 'Show Below Image', 'wc_gallery' ),
			'showon' => __( 'Show On Image', 'wc_gallery' ),
			'hide' => __( 'Hide', 'wc_gallery' )
		);
		?>
		<label class="setting">
			<span><?php _e( 'Captions', 'wc_gallery' ); ?></span>
			<select class="captions" name="captions" data-setting="captions">
				<?php foreach ( $captions as $key => $value ) : ?>
					<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $key, 'onhover' ); ?>><?php echo esc_html( $value ); ?></option>
				<?php endforeach; ?>
			</select>
		</label>

		<?php
		$caption_tags = array( 
			'p' => __( 'p', 'wc_gallery' ),
			'h2' => __( 'h2', 'wc_gallery' ),
			'h3' => __( 'h3', 'wc_gallery' ),
			'h4' => __( 'h4', 'wc_gallery' ),
			'h5' => __( 'h5', 'wc_gallery' ),
			'h6' => __( 'h6', 'wc_gallery' ),
		);
		?>
		<label class="setting">
			<span><?php _e( 'Caption Type', 'wc_gallery' ); ?></span>
			<select class="captiontype" name="captiontype" data-setting="captiontype">
				<?php foreach ( $caption_tags as $key => $value ) : ?>
					<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $key, 'p' ); ?>><?php echo esc_html( $value ); ?></option>
				<?php endforeach; ?>
			</select>
		</label>

		<label class="setting">
			<span><?php _e( 'Custom Link', 'wc_gallery' ); ?></span>
			<input class="customlink" type="checkbox" name="customlink" data-setting="customlink" />
		</label>

		<?php
		$gutterwidth = array();
		for ( $i = 0; $i <= 30; $i++ ) {
			$gutterwidth[ $i ] = $i;
		}
		?>
		<label class="setting">
			<span><?php _e( 'Gutter Width', 'wc_gallery' ); ?></span>
			<select class="gutterwidth" name="gutterwidth" data-setting="gutterwidth">
				<?php foreach ( $gutterwidth as $key => $value ) : ?>
					<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $key, '5' ); ?>><?php echo esc_html( $value ); ?>px</option>
				<?php endforeach; ?>
			</select>
		</label>

		<?php
		$space = array( 
			'default' => __( '20px', 'wc_gallery' ),
			'ten' => __( '10px', 'wc_gallery' ),
			'five' => __( '5px', 'wc_gallery' ),
			'none' => __( '0px', 'wc_gallery' ),
		);
		?>
		<label class="setting">
			<span><?php _e( 'Bottom Space', 'wc_gallery' ); ?></span>
			<select class="bottomspace" name="bottomspace" data-setting="bottomspace">
				<?php foreach ( $space as $key => $value ) : ?>
					<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $key, 'default' ); ?>><?php echo esc_html( $value ); ?></option>
				<?php endforeach; ?>
			</select>
		</label>

		<label class="setting">
			<span><?php _e( 'Hide Controls', 'wc_gallery' ); ?></span>
			<input class="hidecontrols" type="checkbox" name="hidecontrols" data-setting="hidecontrols" />
		</label>

		<label class="setting">
			<span><?php _e( 'New Tab', 'wc_gallery' ); ?></span>
			<input class="newtab" type="checkbox" name="newtab" data-setting="newtab" />
		</label>

		<label class="setting">
			<span><?php _e( 'Class', 'wc_gallery' ); ?></span>
			<input class="class" type="text" name="class" style="float:left;" data-setting="class" />
		</label>
	</script>
	<?php
}
add_action( 'print_media_templates', 'wc_gallery_print_media_templates' );

/**
 * Adds custom fields to attachment page 
 * http://wpengineer.com/2076/add-custom-field-attachment-in-wordpress/ 
 *
 * @param mixed $form_fields 
 * @param mixed $post 
 * @access public
 * @return void
 */
function wc_gallery_attachment_fields_to_edit( $form_fields, $post) {
    $form_fields['wc_gallery_custom_image_link'] = array(  
        "label" => __( "Link To" ),  
        "input" => "text",
        "value" => get_post_meta( $post->ID, _WC_GALLERY_PREFIX . "custom_image_link", true )  
    );        
    return $form_fields;  
}  
add_filter( "attachment_fields_to_edit", "wc_gallery_attachment_fields_to_edit", null, 2 ); 

/**
 * Save custom input in media panel to custom field
 * and validate hyperlink inserted.
 * 
 * @param mixed $post 
 * @param mixed $attachment 
 * @access public
 * @return void
 */
function wc_gallery_attachment_fields_to_save( $post, $attachment) {
    if( isset( $attachment['wc_gallery_custom_image_link'] ) ){  
        update_post_meta( $post['ID'], _WC_GALLERY_PREFIX . 'custom_image_link', esc_url_raw( $attachment['wc_gallery_custom_image_link'] ) );  
    }  
    return $post;  
}
add_filter( "attachment_fields_to_save", "wc_gallery_attachment_fields_to_save", null, 2 );

// This theme uses its own gallery styles.
add_filter( 'use_default_gallery_style', '__return_false' );

function wc_gallery_after_setup_theme() {
	global $wc_gallery_theme_support;

	$defined_sizes = get_intermediate_image_sizes();

	foreach ( $wc_gallery_theme_support as $size => $value ) {
		if ( in_array( 'wc' . $size, $defined_sizes ) ) {
			continue;
		}
		$name_w = $size . '_size_w';
		$name_h = $size . '_size_h';
		$name_crop = $size . '_crop';

		$width = get_option( WC_GALLERY_PREFIX . $name_w );
		$height = get_option( WC_GALLERY_PREFIX . $name_h );
		$crop = get_option( WC_GALLERY_PREFIX . $name_crop );
		if ( $width && $height ) {
			$crop = $crop ? true : false;
			add_image_size( 'wc' . $size, $width, $height, $crop );
		}
	}
}
add_action( 'after_setup_theme', 'wc_gallery_after_setup_theme', 99 );

/**
 * Allow users to select our custom image sizes
 *
 * @since 3.6.1
 * @access public
 *
 * @param array $sizes
 * @return array
 */
function wc_gallery_image_size_names_choose( $sizes ) {
	global $wc_gallery_theme_support;

	foreach ( $wc_gallery_theme_support as $size => $value ) {
		$name_w = $size . '_size_w';
		$name_h = $size . '_size_h';

		$width = get_option( WC_GALLERY_PREFIX . $name_w );
		$height = get_option( WC_GALLERY_PREFIX . $name_h );
		if ( $width && $height ) {
			$name = 'wc' . $size;
			if ( ! array_key_exists( $name, $sizes ) ) {
				$sizes[ $name ] = wc_gallery_return_proper_size_name( $size );
			}
		}
	}
 
	return $sizes;
}
add_filter( 'image_size_names_choose', 'wc_gallery_image_size_names_choose', 99 );

function wc_gallery_return_proper_size_name( $key ) {
	switch ( $key ) {
		case 'fixedheightsmall' :
			return 'Fixed Height (Small)';
		case 'fixedheightmedium' :
			return 'Fixed Height (Medium)';
		case 'fixedheight' :
			return 'Fixed Height (Large)';
		case 'carouselsmall' :
			return 'Carousel (Small)';
		case 'carousel' :
			return 'Carousel (Large)';
	}

	return ucwords( $key );
}
