<div id="wpcsf-<?php echo $args['id']; ?>-group" class="wpcsf-group">

	<?php foreach ( $args['group'] as $g ) : ?>
		<?php $this->display_setting( $g ); ?>
	<?php endforeach; ?>

	<?php if ( isset( $args['description'] ) ) : ?>
		<p class="description"><?php echo $args['description']; ?></p>
	<?php endif; ?>

</div>
