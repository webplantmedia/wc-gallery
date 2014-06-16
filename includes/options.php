<?php
function wc_gallery_set_options() {
	global $wc_gallery_options;
	global $wc_gallery_theme_support;

	$wc_gallery_options['misc'] = array(
		'title' => 'Misc',
		'sections' => array(
			array(
				'section' => 'wc-gallery-options-misc-section',
				'title' => 'Miscellaneous Options',
				'options' => array(
					array(
						'id' => 'enable_gallery_css',
						'title' => 'Gallery CSS',
						'default' => true,
						'description' => '',
						'label' => 'Use gallery CSS provided by plugin',
						'type' => 'checkbox',
					),
					array(
						'id' => 'enable_image_popup',
						'title' => 'Image Popup',
						'default' => true,
						'description' => '',
						'label' => 'Use <a target="_blank" href="http://dimsemenov.com/plugins/magnific-popup/">Magnific Popup</a> to showcase your images?',
						'type' => 'checkbox',
					),
				),
			),
		),
	);

	$wc_gallery_options['wc-image-sizes'] = array(
		'title' => 'Image Sizes',
		'sections' => array(
			array(
				'section' => 'wc-gallery-options-image-sizes-section',
				'title' => 'Additional Image Sizes',
				'options' => array(
					array(
						'id' => 'icon_size',
						'title' => 'Icon Size',
						'description' => '',
						'group' => array(
							array(
								'id' => 'icon_size_w',
								'label' => 'Max Width',
								'default' => $wc_gallery_theme_support['wcicon']['size_w'],
								'type' => 'positive_number',
							),
							array(
								'id' => 'icon_size_h',
								'label' => 'Max Height',
								'default' => $wc_gallery_theme_support['wcicon']['size_h'],
								'type' => 'positive_number',
							),
							array(
								'id' => 'icon_crop',
								'label' => 'Crop to exact dimensions',
								'default' => $wc_gallery_theme_support['wcicon']['crop'],
								'description' => '',
								'type' => 'checkbox',
							),
						),
					),
					array(
						'id' => 'square_size',
						'title' => 'Square Size',
						'description' => '',
						'group' => array(
							array(
								'id' => 'square_size_w',
								'label' => 'Max Width',
								'default' => $wc_gallery_theme_support['wcsquare']['size_w'],
								'type' => 'positive_number',
								'less' => true,
							),
							array(
								'id' => 'square_size_h',
								'label' => 'Max Height',
								'default' => $wc_gallery_theme_support['wcsquare']['size_h'],
								'type' => 'positive_number',
								'less' => true,
							),
							array(
								'id' => 'square_crop',
								'label' => 'Crop to exact dimensions',
								'default' => $wc_gallery_theme_support['wcsquare']['crop'],
								'description' => '',
								'type' => 'checkbox',
							),
						),
					),
					array(
						'id' => 'small_size',
						'title' => 'Small Size',
						'description' => '',
						'group' => array(
							array(
								'id' => 'small_size_w',
								'label' => 'Max Width',
								'default' => $wc_gallery_theme_support['wcsmall']['size_w'],
								'type' => 'positive_number',
							),
							array(
								'id' => 'small_size_h',
								'label' => 'Max Height',
								'default' => $wc_gallery_theme_support['wcsmall']['size_h'],
								'type' => 'positive_number',
							),
						),
					),
					array(
						'id' => 'standard_size',
						'title' => 'Standard Size',
						'description' => '',
						'group' => array(
							array(
								'id' => 'standard_size_w',
								'label' => 'Max Width',
								'default' => $wc_gallery_theme_support['wcstandard']['size_w'],
								'type' => 'positive_number',
							),
							array(
								'id' => 'standard_size_h',
								'label' => 'Max Height',
								'default' => $wc_gallery_theme_support['wcstandard']['size_h'],
								'type' => 'positive_number',
							),
						),
					),
					array(
						'id' => 'big_size',
						'title' => 'Big Size',
						'description' => '',
						'group' => array(
							array(
								'id' => 'big_size_w',
								'label' => 'Max Width',
								'default' => $wc_gallery_theme_support['wcbig']['size_w'],
								'type' => 'positive_number',
							),
							array(
								'id' => 'big_size_h',
								'label' => 'Max Height',
								'default' => $wc_gallery_theme_support['wcbig']['size_h'],
								'type' => 'positive_number',
							),
						),
					),
					array(
						'id' => 'fixedheight_size',
						'title' => 'Fixed Height Size',
						'description' => '',
						'group' => array(
							array(
								'id' => 'fixedheight_size_w',
								'label' => 'Max Width',
								'default' => $wc_gallery_theme_support['wcfixedheight']['size_w'],
								'type' => 'positive_number',
							),
							array(
								'id' => 'fixedheight_size_h',
								'label' => 'Max Height',
								'default' => $wc_gallery_theme_support['wcfixedheight']['size_h'],
								'type' => 'positive_number',
							),
						),
					),
					array(
						'id' => 'carousel_size',
						'title' => 'Carousel Size',
						'description' => '',
						'group' => array(
							array(
								'id' => 'carousel_size_w',
								'label' => 'Max Width',
								'default' => $wc_gallery_theme_support['wccarousel']['size_w'],
								'type' => 'positive_number',
							),
							array(
								'id' => 'carousel_size_h',
								'label' => 'Max Height',
								'default' => $wc_gallery_theme_support['wccarousel']['size_h'],
								'type' => 'positive_number',
							),
							array(
								'id' => 'carousel_crop',
								'label' => 'Crop to exact dimensions',
								'default' => $wc_gallery_theme_support['wccarousel']['crop'],
								'description' => '',
								'type' => 'checkbox',
							),
						),
					),
					array(
						'id' => 'slider_size',
						'title' => 'Slider Size',
						'description' => '',
						'group' => array(
							array(
								'id' => 'slider_size_w',
								'label' => 'Max Width',
								'default' => $wc_gallery_theme_support['wcslider']['size_w'],
								'type' => 'positive_number',
							),
							array(
								'id' => 'slider_size_h',
								'label' => 'Max Height',
								'default' => $wc_gallery_theme_support['wcslider']['size_h'],
								'type' => 'positive_number',
							),
							array(
								'id' => 'slider_crop',
								'label' => 'Crop to exact dimensions',
								'default' => $wc_gallery_theme_support['wcslider']['crop'],
								'description' => '',
								'type' => 'checkbox',
							),
						),
					),
				),
			),
		),
	);
}
add_action( 'after_setup_theme', 'wc_gallery_set_options', 100 );
