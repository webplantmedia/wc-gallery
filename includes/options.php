<?php
$wc_gallery_options = array(
	'misc' => array(
		'title' => 'Misc',
		'sections' => array(
			array(
				'section' => 'wc-gallery-options-misc-section',
				'title' => 'Miscellaneous Options',
				'options' => array(
					array(
						'id' => 'enable_gallery_css',
						'title' => 'Gallery CSS',
						'default' => '1',
						'description' => '',
						'label' => 'Use gallery CSS provided by plugin',
						'type' => 'checkbox',
					),
					array(
						'id' => 'enable_image_popup',
						'title' => 'Image Popup',
						'default' => '1',
						'description' => '',
						'label' => 'Use <a target="_blank" href="http://dimsemenov.com/plugins/magnific-popup/">Magnific Popup</a> to showcase your images?',
						'type' => 'checkbox',
					),
				),
			),
		),
	),
);
