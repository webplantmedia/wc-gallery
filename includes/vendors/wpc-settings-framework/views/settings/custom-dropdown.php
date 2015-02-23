<?php
if ( isset( $options_callback ) && ! empty( $options_callback ) )
	$options = call_user_func( $options_callback );
?>

<?php if ( isset( $label ) ) : ?>
	<label for="<?php echo $option_name; ?>"><?php echo $label; ?></label>&nbsp;
<?php endif; ?>

<select name="<?php echo $option_name; ?>" id="<?php echo $option_name; ?>">
	<?php foreach ( $options as $key => $name ) : ?>
		<option value="<?php echo $key; ?>" <?php selected( $val, $key); ?>><?php echo $name; ?></option>
	<?php endforeach; ?>
</select>&nbsp;

<?php // Description ?>
<?php if ( isset( $description ) && !empty( $description ) ) : ?>
	<p class="description"><?php echo $description; ?></p>
<?php endif; ?>
