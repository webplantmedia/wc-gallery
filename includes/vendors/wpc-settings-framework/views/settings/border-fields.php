<?php
$val = wp_parse_args( $val, $default );
$val['width'] = preg_replace("/[^0-9]/", "",$val['width']);
?>

<div class="wpcsf-border-fields">
	<?php // Border Width ?>
	<label for="<?php echo $option_name; ?>_width">Width</label>&nbsp;
	<input type="number" min="0" class="wpcsf-border-width small-text" name="<?php echo $option_name; ?>[width]" id="<?php echo $option_name; ?>_width" value="<?php echo esc_attr($val['width']); ?>" />&nbsp;

	<?php // Border Style ?>
	<label for="<?php echo $option_name; ?>_style">Style</label>&nbsp;
	<select name="<?php echo $option_name; ?>[style]" class="wpcsf-border-style" id="<?php echo $option_name; ?>_style" >
		<option value="none" <?php selected( $val['style'], 'none'); ?>>None</option>
		<option value="dotted" <?php selected( $val['style'], 'dotted'); ?>>Dotted</option>
		<option value="dashed" <?php selected( $val['style'], 'dashed'); ?>>Dashed</option>
		<option value="solid" <?php selected( $val['style'], 'solid'); ?>>Solid</option>
		<option value="double" <?php selected( $val['style'], 'double'); ?>>Double</option>
	</select>&nbsp;
	<br />

	<input name="<?php echo $option_name; ?>[color]" type="text" value="<?php echo esc_attr( $val['color'] ); ?>" class="wpcsf-color-field" data-default-color="<?php echo $default['color']; ?>" />

	<?php // Description ?>
	<?php if ( isset( $description ) && !empty( $description ) ) : ?>
		<p class="description"><?php echo $description; ?></p>
	<?php endif; ?>
</div>
