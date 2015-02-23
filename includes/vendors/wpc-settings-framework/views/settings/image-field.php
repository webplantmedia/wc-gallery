<?php
// preview image default style
$style = '';
if ( empty( $val ) )
	$style = ' style="display:none"';
?>

<div class="wpcsf-image-field">
	<?php if ( isset( $label ) ) : ?>
		<label for="<?php echo esc_attr($option_name); ?>"><?php echo $label; ?></label>&nbsp;
	<?php endif; ?>

	<input name="<?php echo $option_name; ?>" id="<?php echo $option_name; ?>" class="regular-text ltr upload-input" type="text" value="<?php echo esc_attr($val); ?>" />
	<br />
	<a class="button wpcsf-image-upload" data-target="#<?php echo $option_name; ?>" data-preview=".wpcsf-preview-image" data-frame="select" data-state="wpc_settings_framework_insert_single" data-fetch="url" data-title="Insert Image" data-button="Insert" data-class="media-frame wpcsf-custom-uploader" title="Add Media"><span class="wp-media-buttons-icon"></span> Add Media</a>
	<a class="button wpcsf-restore-image" data-restore="<?php echo $default; ?>" data-target="#<?php echo $option_name; ?>" data-preview=".wpcsf-preview-image">Default</a>
	<a class="button wpcsf-delete-image" data-target="#<?php echo $option_name; ?>" data-preview=".wpcsf-preview-image">Delete</a>
	<p class="wpcsf-preview-image"<?php echo $style; ?>><img src="<?php echo esc_attr($val); ?>" /></p>
	<?php if ( isset( $description ) && !empty( $description ) ) : ?>
		<p class="description"><?php echo $description; ?></p>
	<?php endif; ?>
</div>
<?php
