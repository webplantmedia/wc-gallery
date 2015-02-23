<?php
if ( isset( $options_callback ) && ! empty( $options_callback ) )
	$options = call_user_func( $options_callback );
?>

<?php foreach ( $options as $key => $name ) : ?>
	<label>
		<input name="<?php echo $option_name; ?>" id="<?php echo $option_name; ?>" type="radio" value="<?php echo $key; ?>" <?php checked( $val, $key ); ?> />
	<?php echo $name; ?></label><br />
<?php endforeach; ?>

<?php // Description ?>
<?php if ( isset( $description ) && !empty( $description ) ) : ?>
	<p class="description"><?php echo $description; ?></p>
<?php endif; ?>
