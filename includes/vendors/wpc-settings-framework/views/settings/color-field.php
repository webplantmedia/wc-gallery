<?php if ( isset( $label ) ) : ?>
	<label for="<?php echo $option_name; ?>"><?php echo $label; ?></label>&nbsp;
<?php endif; ?>

<input name="<?php echo $option_name; ?>" id="<?php echo $option_name; ?>" type="text" value="<?php echo esc_attr( $val ); ?>" class="wpcsf-color-field" data-default-color="<?php echo $default; ?>" /><br />

<?php // Description ?>
<?php if ( isset( $description ) && !empty( $description ) ) : ?>
	<p class="description"><?php echo $description; ?></p>
<?php endif; ?>
