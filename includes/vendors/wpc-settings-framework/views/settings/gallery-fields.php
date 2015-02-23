<?php
// preview image default style
$style = '';
if ( empty( $val['image'] ) )
	$style = ' style="display:none"';
?>

<div class="wpcsf-image-field">
	<input name="<?php echo $option_name; ?>" id="<?php echo $option_name; ?>" class="regular-text ltr upload-input" type="hidden" value="<?php echo esc_attr($val); ?>" />
	<a class="button wpcsf-image-upload" data-target="#<?php echo $option_name; ?>" data-preview=".wpcsf-preview-image" data-frame="post" data-state="gallery-library" data-fetch="id" data-imgsize="thumbnail" data-title="Insert Gallery" data-button="Insert" data-class="media-frame wpcsf-custom-uploader-gallery-library" title="Add Media"><span class="wp-media-buttons-icon"></span> Add Media</a>
	<a class="button wpcsf-delete-image" data-target="#<?php echo $option_name; ?>" data-preview=".wpcsf-preview-image">Delete</a>
	<p class="wpcsf-preview-image"<?php echo $style; ?>>
		<?php $ids = explode( ',', $val ); ?>
		<?php foreach ( $ids as $id ) : ?>
			<?php $src = wp_get_attachment_image_src( $id, 'thumbnail', false ); ?>
			<img src="<?php echo $src[0]; ?>" />
		<?php endforeach; ?>
	</p>
	<?php if ( isset( $description ) && !empty( $description ) ) : ?>
		<p class="description"><?php echo $description; ?></p>
	<?php endif; ?>
</div>
