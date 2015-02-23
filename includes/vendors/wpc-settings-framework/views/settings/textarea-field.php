<?php if ( isset( $label ) ) : ?>
	<label for="<?php echo esc_attr($option_name); ?>"><?php echo $label; ?></label>&nbsp;
<?php endif; ?>

<textarea name="<?php echo $option_name; ?>" class="wpcsf-textarea" id="<?php echo $option_name; ?>"><?php echo esc_textarea($val); ?></textarea>
<?php if ( isset( $description ) && !empty( $description ) ) : ?>
	<p class="description"><?php echo $description; ?></p>
<?php endif; ?>
