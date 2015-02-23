<?php if ( isset( $label ) ) : ?>
	<label for="<?php echo esc_attr($option_name); ?>">
<?php endif; ?>

<input name="<?php echo $option_name; ?>" id="<?php echo $option_name; ?>" type="checkbox" value="1" <?php checked( true, $val ); ?> />

<?php if ( isset( $label ) ) : ?>
	&nbsp;<?php echo $label; ?></label>&nbsp;
<?php endif; ?>

<?php if ( isset( $description ) && !empty( $description ) ) : ?>
	<p class="description"><?php echo $description; ?></p>
<?php endif; ?>
