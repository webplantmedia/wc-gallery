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
		'itemtag'    => 'dl',
		'icontag'    => 'dt',
		'captiontag' => 'dd',
		'captions'   => 'show',
		'columns'    => 3,
		'link'       => 'post',
		'size'       => 'thumbnail',
		'targetsize' => 'large',
		'display'    => 'masonry',
		'customlink' => 'false',
		'class'	     => '',
		'include'    => '',
		'exclude'    => ''
	), $attr, 'gallery'));

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

	$itemtag = tag_escape($itemtag);
	$captiontag = tag_escape($captiontag);
	$icontag = tag_escape($icontag);
	$valid_tags = wp_kses_allowed_html( 'post' );
	if ( ! isset( $valid_tags[ $itemtag ] ) )
		$itemtag = 'dl';
	if ( ! isset( $valid_tags[ $captiontag ] ) )
		$captiontag = 'dd';
	if ( ! isset( $valid_tags[ $icontag ] ) )
		$icontag = 'dt';

	$columns = intval($columns);
	$itemwidth = $columns > 0 ? floor(100/$columns) : 100;
	$float = is_rtl() ? 'right' : 'left';

	$selector = "gallery-{$instance}";

	$gallery_style = $gallery_div = '';
	if ( apply_filters( 'use_default_gallery_style', true ) )
		$gallery_style = "
		<style type='text/css'>
			#{$selector} {
				margin: auto;
			}
			#{$selector} .gallery-item {
				float: {$float};
				margin-top: 10px;
				text-align: center;
				width: {$itemwidth}%;
			}
			#{$selector} img {
				border: 2px solid #cfcfcf;
			}
			#{$selector} .gallery-caption {
				margin-left: 0;
			}
			/* see gallery_shortcode() in wp-includes/media.php */
		</style>";
	$size_class = sanitize_html_class( $size );

	$showcaptions = 'hide' == $captions ? false : true;
	$customlink = 'true' == $customlink ? true : false;
	$class = explode( ' ', $class );

	if ( 'slider' == $display ) {
		wp_enqueue_script( 'wc-gallery-flexslider' );
		wp_enqueue_script( 'wc-gallery' );

		$class[] = 'wc-gallery';
		$class[] = 'gallery';
		$class[] = 'wcslider';
		$class[] = 'wcflexslider';
		$gallery_div = "<div class='".implode( ' ', $class )."'>";
		$gallery_div .= "<ul id='$selector' class='slides'>";
		$output = apply_filters( 'gallery_style', $gallery_style . "\n\t\t" . $gallery_div );

		$i = 0;
		foreach ( $attachments as $id => $attachment ) {
			if ( ! $img = wp_get_attachment_image_src( $id, $size ) )
				continue;
			list($src, $width, $height) = $img;
			$image_output = "<img src='{$src}' width='{$width}' height='{$height}' />";
			$image_meta  = wp_get_attachment_metadata( $id );

			$orientation = '';
			if ( isset( $image_meta['height'], $image_meta['width'] ) )
				$orientation = ( $image_meta['height'] > $image_meta['width'] ) ? 'portrait' : 'landscape';

			$output .= "
				<li class='wcflex-slide-item'>
					$image_output";
			if ( $showcaptions && $captiontag && trim($attachment->post_excerpt) ) {
				$output .= "
					<div class='wp-caption-text gallery-caption'>
					" . wptexturize($attachment->post_excerpt) . "
					</div>";
			}
			$output .= "</li>";
		}

		$output .= "</ul></div>\n";
	}
	else if ( 'carousel' == $display ) {
		wp_enqueue_script( 'wc-gallery-flexslider' );
		wp_enqueue_script( 'wc-gallery' );

		$class[] = 'wc-gallery';
		$class[] = 'gallery';
		$class[] = 'wccarousel';
		$class[] = 'wcflexslider';
		$gallery_div = "<div class='".implode( ' ', $class )."'>";
		$gallery_div .= "<ul id='$selector' class='slides'>";
		$output = apply_filters( 'gallery_style', $gallery_style . "\n\t\t" . $gallery_div );

		$i = 0;
		foreach ( $attachments as $id => $attachment ) {
			if ( ! $img = wp_get_attachment_image_src( $id, $size ) )
				continue;
			list($src, $width, $height) = $img;
			$image_output = "<img src='{$src}' width='{$width}' height='{$height}' />";
			$image_meta  = wp_get_attachment_metadata( $id );

			$orientation = '';
			if ( isset( $image_meta['height'], $image_meta['width'] ) )
				$orientation = ( $image_meta['height'] > $image_meta['width'] ) ? 'portrait' : 'landscape';

			$output .= "
				<li class='wcflex-slide-item'>
				<div class='wcflex-center-slide' style='width:{$width}px'>
					$image_output";
			if ( $showcaptions && $captiontag && trim($attachment->post_excerpt) ) {
				$output .= "
					<div class='wp-caption-text gallery-caption'>
					" . wptexturize($attachment->post_excerpt) . "
					</div>";
			}
			$output .= "</div></li>";
		}

		$output .= "</ul></div>\n";
	}
	else {
		wp_enqueue_script( 'wc-gallery-popup' );
		wp_enqueue_script( 'wc-gallery' );

		if ( in_array( $size, array( 'thumbnail', 'wcsquare' ) ) )
			$display = 'default';

		if ( 'large' == $size && 1 == $columns )
			$display = 'default';

		$class[] = "wc-gallery";
		$class[] = "gallery";
		$class[] = "gallery-{$display}";
		$class[] = "galleryid-{$id}";
		$class[] = "gallery-columns-{$columns}";
		$class[] = "gallery-size-{$size_class}";
		// custom links should not call popup
		if ( ! $customlink )
			$class[] = "gallery-link-{$link}";

		if ( 'onhover' == $captions )
			$class[] = 'gallery-captions-on-hover';

		$class = implode( ' ', $class );

		$gallery_div .= "<div id='$selector' class='{$class}'>";
		$output = apply_filters( 'gallery_style', $gallery_style . "\n\t\t" . $gallery_div );

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

			$output .= "<{$itemtag} class='gallery-item'>";
			$output .= "
				<{$icontag} class='gallery-icon {$orientation}'>
					$image_output
				</{$icontag}>";
			if ( $showcaptions && $captiontag && trim($attachment->post_excerpt) ) {
				$output .= "
					<{$captiontag} class='wp-caption-text gallery-caption'>
					" . wptexturize($attachment->post_excerpt) . "
					</{$captiontag}>";
			}
			$output .= "</{$itemtag}>";
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
		'masonry' => __( 'Gallery', 'wc_gallery' ),
		'carousel' => __( 'Carousel', 'wc_gallery' ),
		'slider' => __( 'Slider', 'wc_gallery' ),
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
			<span><?php _e( 'Size', 'wc_gallery' ); ?></span>
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
			<span><?php _e( 'Target Size', 'wc_gallery' ); ?></span>
			<select class="targetsize" name="targetsize" data-setting="targetsize">
				<?php foreach ( $sizes as $key => $name ) : ?>
					<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $key, 'large' ); ?>><?php echo esc_html( $name ); ?></option>
				<?php endforeach; ?>
			</select>
		</label>

		<?php
		$captions = array( 
			'show' => __( 'Show', 'wc_gallery' ),
			'onhover' => __( 'On Image Hover', 'wc_gallery' ),
			'hide' => __( 'Hide', 'wc_gallery' )
		);
		?>
		<label class="setting">
			<span><?php _e( 'Show Captions', 'wc_gallery' ); ?></span>
			<select class="captions" name="captions" data-setting="captions">
				<?php foreach ( $captions as $key => $value ) : ?>
					<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $key, 'show' ); ?>><?php echo esc_html( $value ); ?></option>
				<?php endforeach; ?>
			</select>
		</label>

		<label class="setting">
			<span><?php _e( 'Custom Link', 'wc_gallery' ); ?></span>
			<input type="checkbox" name="customlink" data-setting="customlink" />
		</label>

		<label class="setting">
			<span><?php _e( 'Class', 'wc_gallery' ); ?></span>
			<input type="text" name="class" data-setting="class" />
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
