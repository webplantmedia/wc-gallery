<?php
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
		'captions'   => 'show',
		'captiontype' => 'p',
		'columns'    => 3,
		'link'       => 'post',
		'size'       => 'thumbnail',
		'targetsize' => 'large',
		'display'    => 'masonry',
		'customlink' => 'false',
		'bottomspace' => 'default',
		'hidecontrols' => 'false',
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
	$itemwidth = $columns > 0 ? floor(100/$columns) : 100;
	$float = is_rtl() ? 'right' : 'left';

	$selector = "gallery-{$instance}";

	$size_class = sanitize_html_class( $size );

	$showcaptions = 'hide' == $captions ? false : true;
	$customlink = 'true' == $customlink ? true : false;
	$class = array();
	$class[] = 'gallery';
	$class[] = 'wc-gallery-bottomspace-' . $bottomspace;
	$class[] = 'wc-gallery-captions-' . $captions;
	if ( ! empty( $custom_class ) )
		$class[] = esc_attr( $custom_class );

	$sliders = array( 'slider', 'slider2', 'carousel' );

	if ( in_array( $display, $sliders ) ) {
		wp_enqueue_script( 'wc-gallery-flexslider' );
		wp_enqueue_script( 'wc-gallery' );

		$class[] = 'wc' . $display;
		$class[] = 'wcflexslider';
		if ( 'true' == $hidecontrols )
			$class[] = 'wcflexslider-hidecontrols';

		$output = "<div class='".implode( ' ', $class )."'>";
		$output .= "<ul id='$selector' class='slides'>";

		$i = 0;
		foreach ( $attachments as $id => $attachment ) {
			if ( ! $img = wp_get_attachment_image_src( $id, $size ) )
				continue;

			list($src, $width, $height) = $img;
			$image_output = "<img src='{$src}' width='{$width}' height='{$height}' />";

			if ( ! empty( $link ) ) {
				if ( $customlink ) {
					$url = get_post_meta( $id, _WC_GALLERY_PREFIX . 'custom_image_link', true );
					$image_output = '<a href="'.$url.'">' . $image_output . '</a>';
				}
				else if ( 'post' === $link ) {
					$url = get_attachment_link( $id );
					$image_output = '<a href="'.$url.'">' . $image_output . '</a>';
				}
				else if ( 'file' === $link ) {
					$url = wp_get_attachment_url( $id );
					$image_output = '<a href="'.$url.'">' . $image_output . '</a>';
				}
			}

			$image_meta  = wp_get_attachment_metadata( $id );

			$orientation = '';
			if ( isset( $image_meta['height'], $image_meta['width'] ) )
				$orientation = ( $image_meta['height'] > $image_meta['width'] ) ? 'portrait' : 'landscape';

			$output .= "
				<li class='wcflex-slide-item'>
					$image_output";
			if ( $showcaptions && trim($attachment->post_excerpt) ) {
				$output .= "
					<div class='wp-caption-text gallery-caption'>
					<{$captiontype}>
					" . wptexturize($attachment->post_excerpt) . "
					</{$captiontype}>
					</div>";
			}
			$output .= "</li>";
		}

		$output .= "</ul></div>\n";
	}
	else {
		wp_enqueue_script( 'wc-gallery-popup' );
		wp_enqueue_script( 'wc-gallery' );

		$display = 'float' == $display ? 'default' : $display;

		$class[] = "gallery-{$display}";
		$class[] = "galleryid-{$id}";
		$class[] = "gallery-columns-{$columns}";
		$class[] = "gallery-size-{$size_class}";
		// custom links should not call popup
		if ( ! $customlink )
			$class[] = "gallery-link-{$link}";

		$class = implode( ' ', $class );

		$output = "<div id='$selector' class='{$class}'>";

		$i = 0;
		foreach ( $attachments as $id => $attachment ) {
			if ( ! empty( $link ) && 'file' === $link ) {
				$image_output = wc_gallery_get_attachment_link( $id, $size, false, false, false, $targetsize, $customlink );
				$image_output = preg_replace( '/^<a /', '<a rel="gallery-'.$instance.'" ', $image_output );
			}
			elseif ( ! empty( $link ) && 'none' === $link )
				$image_output = wp_get_attachment_image( $id, $size, false );
			else
				$image_output = wp_get_attachment_link( $id, $size, true, false );

			$image_meta  = wp_get_attachment_metadata( $id );

			$orientation = '';
			if ( isset( $image_meta['height'], $image_meta['width'] ) )
				$orientation = ( $image_meta['height'] > $image_meta['width'] ) ? 'portrait' : 'landscape';

			$output .= "<div class='gallery-item'>";
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
		}

		$output .= "</div>\n";
	}

	return '<div class="wc-gallery">' . $output . '</div>';
}
add_filter( 'post_gallery', 'wc_gallery_shortcode', 10, 2 );


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
function wc_gallery_get_attachment_link( $id = 0, $size = 'thumbnail', $permalink = false, $icon = false, $text = false, $targetsize = 'large', $customlink = false ) {
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

	return apply_filters( 'wp_get_attachment_link', "<a href='$url' title='$post_title'>$link_text</a>", $id, $size, $permalink, $icon, $text );
}


/**
 * Outputs a view template which can be used with wp.media.template
 */
function wc_gallery_print_media_templates() {
	$display_types = array( 
		'masonry' => __( 'Masonry', 'wc_gallery' ),
		'slider' => __( 'Slider (Fade)', 'wc_gallery' ),
		'slider2' => __( 'Slider (Slide)', 'wc_gallery' ),
		'carousel' => __( 'Carousel', 'wc_gallery' ),
		'float' => __( 'Float', 'wc_gallery' ),
	);
	?>
	<script type="text/html" id="tmpl-wc-gallery-settings">
		<label class="setting">
			<span><?php _e( 'Display', 'wc_gallery' ); ?></span>
			<select class="display" name="display" data-setting="display">
				<?php foreach ( $display_types as $key => $value ) : ?>
					<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $key, 'default' ); ?>><?php echo esc_html( $value ); ?></option>
				<?php endforeach; ?>
			</select>
		</label>

		<label class="setting">
			<span><?php _e( 'Image Size', 'wc_gallery' ); ?></span>
			<select class="size" name="size" data-setting="size">
				<?
				$sizes = apply_filters( 'image_size_names_choose', array(
					'thumbnail' => __('Thumbnail'),
					'medium'    => __('Medium'),
					'large'     => __('Large'),
					'full'      => __('Full Size'),
				) );
				?>

				<?php foreach ( $sizes as $key => $name ) : ?>
					<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $key, 'thumbnail' ); ?>><?php echo esc_html( $name ); ?></option>
				<?php endforeach; ?>
			</select>
		</label>

		<label class="setting">
			<span><?php _e( 'Popup Size', 'wc_gallery' ); ?></span>
			<select class="targetsize" name="targetsize" data-setting="targetsize">
				<?php foreach ( $sizes as $key => $name ) : ?>
					<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $key, 'large' ); ?>><?php echo esc_html( $name ); ?></option>
				<?php endforeach; ?>
			</select>
		</label>

		<?php
		$captions = array( 
			'show' => __( 'Show Below Image', 'wc_gallery' ),
			'showon' => __( 'Show On Image', 'wc_gallery' ),
			'onhover' => __( 'On Image Hover', 'wc_gallery' ),
			'hide' => __( 'Hide', 'wc_gallery' )
		);
		?>
		<label class="setting">
			<span><?php _e( 'Captions', 'wc_gallery' ); ?></span>
			<select class="captions" name="captions" data-setting="captions">
				<?php foreach ( $captions as $key => $value ) : ?>
					<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $key, 'show' ); ?>><?php echo esc_html( $value ); ?></option>
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
			<span><?php _e( 'Class', 'wc_gallery' ); ?></span>
			<input class="class" type="text" name="class" data-setting="class" />
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
