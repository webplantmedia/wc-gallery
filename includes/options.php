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
								'default' => $wc_gallery_theme_support['icon']['size_w'],
								'type' => 'positive_number',
							),
							array(
								'id' => 'icon_size_h',
								'label' => 'Max Height',
								'default' => $wc_gallery_theme_support['icon']['size_h'],
								'type' => 'positive_number',
							),
							array(
								'id' => 'icon_crop',
								'label' => 'Crop to exact dimensions',
								'default' => $wc_gallery_theme_support['icon']['crop'],
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
								'default' => $wc_gallery_theme_support['square']['size_w'],
								'type' => 'positive_number',
								'less' => true,
							),
							array(
								'id' => 'square_size_h',
								'label' => 'Max Height',
								'default' => $wc_gallery_theme_support['square']['size_h'],
								'type' => 'positive_number',
								'less' => true,
							),
							array(
								'id' => 'square_crop',
								'label' => 'Crop to exact dimensions',
								'default' => $wc_gallery_theme_support['square']['crop'],
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
								'default' => $wc_gallery_theme_support['small']['size_w'],
								'type' => 'positive_number',
							),
							array(
								'id' => 'small_size_h',
								'label' => 'Max Height',
								'default' => $wc_gallery_theme_support['small']['size_h'],
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
								'default' => $wc_gallery_theme_support['standard']['size_w'],
								'type' => 'positive_number',
							),
							array(
								'id' => 'standard_size_h',
								'label' => 'Max Height',
								'default' => $wc_gallery_theme_support['standard']['size_h'],
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
								'default' => $wc_gallery_theme_support['big']['size_w'],
								'type' => 'positive_number',
							),
							array(
								'id' => 'big_size_h',
								'label' => 'Max Height',
								'default' => $wc_gallery_theme_support['big']['size_h'],
								'type' => 'positive_number',
							),
						),
					),
					array(
						'id' => 'fixedheightsmall_size',
						'title' => 'Fixed Height Small Size',
						'description' => '',
						'group' => array(
							array(
								'id' => 'fixedheightsmall_size_w',
								'label' => 'Max Width',
								'default' => $wc_gallery_theme_support['fixedheightsmall']['size_w'],
								'type' => 'positive_number',
							),
							array(
								'id' => 'fixedheightsmall_size_h',
								'label' => 'Max Height',
								'default' => $wc_gallery_theme_support['fixedheightsmall']['size_h'],
								'type' => 'positive_number',
							),
						),
					),
					array(
						'id' => 'fixedheightmedium_size',
						'title' => 'Fixed Height Medium Size',
						'description' => '',
						'group' => array(
							array(
								'id' => 'fixedheightmedium_size_w',
								'label' => 'Max Width',
								'default' => $wc_gallery_theme_support['fixedheightmedium']['size_w'],
								'type' => 'positive_number',
							),
							array(
								'id' => 'fixedheightmedium_size_h',
								'label' => 'Max Height',
								'default' => $wc_gallery_theme_support['fixedheightmedium']['size_h'],
								'type' => 'positive_number',
							),
						),
					),
					array(
						'id' => 'fixedheight_size',
						'title' => 'Fixed Height Large Size',
						'description' => '',
						'group' => array(
							array(
								'id' => 'fixedheight_size_w',
								'label' => 'Max Width',
								'default' => $wc_gallery_theme_support['fixedheight']['size_w'],
								'type' => 'positive_number',
							),
							array(
								'id' => 'fixedheight_size_h',
								'label' => 'Max Height',
								'default' => $wc_gallery_theme_support['fixedheight']['size_h'],
								'type' => 'positive_number',
							),
						),
					),
					array(
						'id' => 'carouselsmall_size',
						'title' => 'Carousel Small Size',
						'description' => '',
						'group' => array(
							array(
								'id' => 'carouselsmall_size_w',
								'label' => 'Max Width',
								'default' => $wc_gallery_theme_support['carouselsmall']['size_w'],
								'type' => 'positive_number',
							),
							array(
								'id' => 'carouselsmall_size_h',
								'label' => 'Max Height',
								'default' => $wc_gallery_theme_support['carouselsmall']['size_h'],
								'type' => 'positive_number',
							),
							array(
								'id' => 'carouselsmall_crop',
								'label' => 'Crop to exact dimensions',
								'default' => $wc_gallery_theme_support['carouselsmall']['crop'],
								'description' => '',
								'type' => 'checkbox',
							),
						),
					),
					array(
						'id' => 'carousel_size',
						'title' => 'Carousel Large Size',
						'description' => '',
						'group' => array(
							array(
								'id' => 'carousel_size_w',
								'label' => 'Max Width',
								'default' => $wc_gallery_theme_support['carousel']['size_w'],
								'type' => 'positive_number',
							),
							array(
								'id' => 'carousel_size_h',
								'label' => 'Max Height',
								'default' => $wc_gallery_theme_support['carousel']['size_h'],
								'type' => 'positive_number',
							),
							array(
								'id' => 'carousel_crop',
								'label' => 'Crop to exact dimensions',
								'default' => $wc_gallery_theme_support['carousel']['crop'],
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
								'default' => $wc_gallery_theme_support['slider']['size_w'],
								'type' => 'positive_number',
							),
							array(
								'id' => 'slider_size_h',
								'label' => 'Max Height',
								'default' => $wc_gallery_theme_support['slider']['size_h'],
								'type' => 'positive_number',
							),
							array(
								'id' => 'slider_crop',
								'label' => 'Crop to exact dimensions',
								'default' => $wc_gallery_theme_support['slider']['crop'],
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
add_action( 'init', 'wc_gallery_set_options', 100 );
