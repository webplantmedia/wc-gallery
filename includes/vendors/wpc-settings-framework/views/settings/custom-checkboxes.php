<?php
$val = is_array( $val ) ? $val : array();

if ( isset( $options_callback ) && ! empty( $options_callback ) )
	$options = call_user_func( $options_callback );
?>

<?php foreach ( $options as $key => $name ) : ?>
	<label>
		<?php $checked = in_array( $key, $val ) ? ' checked="checked"' : ''; ?>
		<input name="<?php echo $option_name; ?>[]" type="checkbox" value="<?php echo $key; ?>" <?php echo $checked; ?> />
	<?php echo $name; ?></label><br />
<?php endforeach; ?>

<?php // Description ?>
<?php if ( isset( $description ) && !empty( $description ) ) : ?>
	<p class="description"><?php echo $description; ?></p>
<?php endif; ?>
