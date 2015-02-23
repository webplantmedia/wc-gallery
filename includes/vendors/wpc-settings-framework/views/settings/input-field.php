<div class="wpcsf-input-field">
	<?php if ( isset( $label ) ) : ?>
		<label for="<?php echo esc_attr($option_name); ?>"><?php echo $label; ?></label>&nbsp;
	<?php endif; ?>

	<input name="<?php echo $option_name; ?>" id="<?php echo $option_name; ?>" type="text" value="<?php echo esc_attr($val); ?>" class="regular-text" />
	<?php if ( isset( $description ) && !empty( $description ) ) : ?>
		<p class="description"><?php echo $description; ?></p>
	<?php endif; ?>
</div>
