<?php
$val = preg_replace("/[^0-9]/", "",$val);
?>

<?php if ( isset( $label ) ) : ?>
	<label for="<?php echo $option_name; ?>"><?php echo $label; ?></label>&nbsp;
<?php endif; ?>

<input type="number" min="0" class="small-text" name="<?php echo esc_attr($option_name); ?>" id="<?php echo $option_name; ?>" value="<?php echo esc_attr($val); ?>" />&nbsp;

<?php if ( isset( $description ) && !empty( $description ) ) : ?>
	<p class="description"><?php echo $description; ?></p>
<?php endif; ?>
