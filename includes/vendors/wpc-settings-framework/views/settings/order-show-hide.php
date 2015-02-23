<?php
$not_selected = $default;
?>

<?php if ( isset( $label ) ) : ?>
	<label for="<?php echo esc_attr($option_name); ?>"><?php echo $label; ?></label>&nbsp;
<?php endif; ?>

<ul class="wpcsf-clearfix wpcsf-order-show-hide">
	<?php if ( is_array( $val ) && ! empty( $val ) ) : ?>
		<?php foreach ( $val as $key => $name ) : ?>
			<li>
				<p style="width:300px;background-color:#f7f7f7;border:1px solid #dfdfdf;padding:5px 5px;line-height:1;margin:0;text-align:left;cursor:move;">
					<input type="checkbox" name="<?php echo $option_name; ?>[<?php echo $key; ?>]" value="<?php echo $name; ?>" <?php checked( true, true ); ?> />
					<?php echo $name; ?>
				</p>
			</li>
			<?php unset( $not_selected[ $key ] ); ?>
		<?php endforeach; ?>
	<?php endif; ?>

	<?php foreach ( $not_selected as $key => $name ) : ?>
		<li>
			<p style="width:300px;background-color:#f7f7f7;border:1px solid #dfdfdf;padding:5px 5px;line-height:1;margin:0;text-align:left;cursor:move;">
				<input type="checkbox" name="<?php echo $option_name; ?>[<?php echo $key; ?>]" value="<?php echo $name; ?>" <?php checked( true, false ); ?> />
				<?php echo $name; ?>
			</p>
		</li>
		<?php unset( $not_selected[ $key ] ); ?>
	<?php endforeach; ?>
</ul>

<?php if ( isset( $description ) && !empty( $description ) ) : ?>
	<p class="description"><?php echo $description; ?></p>
<?php endif; ?>
