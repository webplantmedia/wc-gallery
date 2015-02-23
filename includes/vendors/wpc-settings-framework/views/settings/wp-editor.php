<?php if ( isset( $label ) ) : ?>
	<label for="<?php echo esc_attr($option_name); ?>"><?php echo $label; ?></label>&nbsp;
<?php endif; ?>

<div class="wpcsf-wp-editor">
	<?php wp_editor( $val, $option_name ); ?>
</div>

<?php if ( isset( $description ) && !empty( $description ) ) : ?>
	<p class="description"><?php echo $description; ?></p>
<?php endif; ?>
