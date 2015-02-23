<?php
// preview image default style
$style = '';
if ( empty( $val['image'] ) )
	$style = ' style="display:none"';
?>

<div class="wpcsf-background-options">
	<?php // Background Image ?>
	<input name="<?php echo $option_name; ?>[image]" id="<?php echo $option_name; ?>" class="regular-text ltr upload-input" type="text" value="<?php echo esc_attr( $val['image'] ); ?>" />
	<br />
	<a class="button wpcsf-image-upload" data-target="#<?php echo $option_name; ?>" data-preview=".wpcsf-preview-image" data-frame="select" data-state="wpc_settings_framework_insert_single" data-fetch="url" data-title="Insert Image" data-button="Insert" data-class="media-frame wpcsf-custom-uploader" title="Add Media"><span class="wp-media-buttons-icon"></span> Add Media</a>
	<a class="button wpcsf-restore-image" data-restore="<?php echo esc_attr( $default['image'] ); ?>" data-target="#<?php echo $option_name; ?>" data-preview=".wpcsf-preview-image">Default</a>
	<a class="button wpcsf-delete-image" data-target="#<?php echo $option_name; ?>" data-preview=".wpcsf-preview-image">Delete</a>
	<br />
	<p class="wpcsf-preview-image"<?php echo $style; ?>><img src="<?php echo esc_attr( $val['image'] ); ?>" /></p>

	<?php // Background Repeat ?>
	<select name="<?php echo $option_name; ?>[repeat]" >
		<option value="repeat" <?php selected( $val['repeat'], 'repeat'); ?>>Repeat</option>
		<option value="repeat-x" <?php echo selected( $val['repeat'], 'repeat-x', false ); ?>>Repeat Horizontal</option>
		<option value="repeat-y" <?php echo selected( $val['repeat'], 'repeat-y', false ); ?>>Repeat Vertical</option>
		<option value="no-repeat" <?php echo selected( $val['repeat'], 'no-repeat', false ); ?>>No Repeat</option>
		<option value="" <?php selected( $val['repeat'], ''); ?>>Inherit</option>
	</select>

	<?php // Background position ?>
	<select name="<?php echo $option_name; ?>[position]" >
		<option value="left top" <?php selected( $val['position'], 'left top'); ?>>Left Top</option>
		<option value="left center" <?php selected( $val['position'], 'left center'); ?>>Left Center</option>
		<option value="left bottom" <?php selected( $val['position'], 'left bottom'); ?>>Left Bottom</option>
		<option value="right top" <?php selected( $val['position'], 'right top'); ?>>Right Top</option>
		<option value="right center" <?php selected( $val['position'], 'right center'); ?>>Right Center</option>
		<option value="right bottom" <?php selected( $val['position'], 'right bottom'); ?>>Right Bottom</option>
		<option value="center top" <?php selected( $val['position'], 'center top'); ?>>Center Top</option>
		<option value="center center" <?php selected( $val['position'], 'center center'); ?>>Center Center</option>
		<option value="center bottom" <?php selected( $val['position'], 'center bottom'); ?>>Center Bottom</option>
		<option value="" <?php selected( $val['position'], ''); ?>>Inherit</option>
	</select>

	<?php // Background Attachment ?>
	<select name="<?php echo $option_name; ?>[attachment]" >
		<option value="scroll" <?php selected( $val['attachment'], 'scroll'); ?>>Scroll</option>
		<option value="fixed" <?php selected( $val['attachment'], 'fixed'); ?>>Fixed</option>
		<option value="" <?php selected( $val['attachment'], ''); ?>>Inherit</option>
	</select>
	<br />

	<?php // Background Color ?>
	<input name="<?php echo $option_name; ?>[color]" type="text" value="<?php echo $val['color']; ?>" class="wpcsf-color-field" data-default-color="<?php echo $default['color']; ?>" />

	<?php // Description ?>
	<?php if ( isset( $description ) && !empty( $description ) ) : ?>
		<p class="description"><?php echo $description; ?></p>
	<?php endif; ?>
</div>
