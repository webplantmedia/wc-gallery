<?php
function wc_gallery_options( $options ) {
	global $wc_gallery_theme_support;

	// page
	$menu_slug = 'wc-gallery';

	// Option
	$options[ $menu_slug ] = array(
		'parent_slug' => 'themes.php',
		'page_title' => 'Gallery',
		'menu_title' => 'Gallery',
		'capability' => 'manage_options',
		'option_group' => 'wc-gallery-options-group',
		'tabs' => array(
			array(
				'id' => 'wc-gallery-image-sizes-tab',
				'title' => 'Image Sizes',
				'sections' => array(
					array(
						'id' => 'wc-gallery-image-sizes-section',
						'add_section' => true,
						'title' => 'Additional Image Sizes',
						'description' => '',
						'options' => array(
							array(
								'id' => 'icon_size',
								'title' => 'Icon Size',
								'description' => 'Image size identifier: <code>wcicon</code>',
								'group' => array(
									array(
										'option_name' => 'icon_size_w',
										'label' => 'Max Width',
										'default' => $wc_gallery_theme_support['icon']['size_w'],
										'type' => 'positive_number',
										'theme_reset' => true,
									),
									array(
										'option_name' => 'icon_size_h',
										'label' => 'Max Height',
										'default' => $wc_gallery_theme_support['icon']['size_h'],
										'type' => 'positive_number',
										'newline' => true,
										'theme_reset' => true,
									),
									array(
										'option_name' => 'icon_crop',
										'label' => 'Crop to exact dimensions',
										'default' => $wc_gallery_theme_support['icon']['crop'],
										'description' => '',
										'type' => 'checkbox',
										'newline' => true,
										'theme_reset' => true,
									),
									array(
										'option_name' => 'icon_enable',
										'label' => 'Enable Thumbnail Generation',
										'default' => $wc_gallery_theme_support['icon']['enable'],
										'description' => '',
										'type' => 'checkbox',
										'theme_reset' => true,
									),
								),
							),
							array(
								'id' => 'square_size',
								'title' => 'Square Size',
								'description' => 'Image size identifier: <code>wcsquare</code>',
								'group' => array(
									array(
										'option_name' => 'square_size_w',
										'label' => 'Max Width',
										'default' => $wc_gallery_theme_support['square']['size_w'],
										'type' => 'positive_number',
										'less' => true,
										'theme_reset' => true,
									),
									array(
										'option_name' => 'square_size_h',
										'label' => 'Max Height',
										'default' => $wc_gallery_theme_support['square']['size_h'],
										'type' => 'positive_number',
										'less' => true,
										'newline' => true,
										'theme_reset' => true,
									),
									array(
										'option_name' => 'square_crop',
										'label' => 'Crop to exact dimensions',
										'default' => $wc_gallery_theme_support['square']['crop'],
										'description' => '',
										'type' => 'checkbox',
										'newline' => true,
										'theme_reset' => true,
									),
									array(
										'option_name' => 'square_enable',
										'label' => 'Enable Thumbnail Generation',
										'default' => $wc_gallery_theme_support['square']['enable'],
										'description' => '',
										'type' => 'checkbox',
										'theme_reset' => true,
									),
								),
							),
							array(
								'id' => 'small_size',
								'title' => 'Small Size',
								'description' => 'Image size identifier: <code>wcsmall</code>',
								'group' => array(
									array(
										'option_name' => 'small_size_w',
										'label' => 'Max Width',
										'default' => $wc_gallery_theme_support['small']['size_w'],
										'type' => 'positive_number',
										'theme_reset' => true,
									),
									array(
										'option_name' => 'small_size_h',
										'label' => 'Max Height',
										'default' => $wc_gallery_theme_support['small']['size_h'],
										'type' => 'positive_number',
										'newline' => true,
										'theme_reset' => true,
									),
									array(
										'option_name' => 'small_enable',
										'label' => 'Enable Thumbnail Generation',
										'default' => $wc_gallery_theme_support['small']['enable'],
										'description' => '',
										'type' => 'checkbox',
										'theme_reset' => true,
									),
								),
							),
							array(
								'id' => 'standard_size',
								'title' => 'Standard Size',
								'description' => 'Image size identifier: <code>wcstandard</code>',
								'group' => array(
									array(
										'option_name' => 'standard_size_w',
										'label' => 'Max Width',
										'default' => $wc_gallery_theme_support['standard']['size_w'],
										'type' => 'positive_number',
										'theme_reset' => true,
									),
									array(
										'option_name' => 'standard_size_h',
										'label' => 'Max Height',
										'default' => $wc_gallery_theme_support['standard']['size_h'],
										'type' => 'positive_number',
										'newline' => true,
										'theme_reset' => true,
									),
									array(
										'option_name' => 'standard_enable',
										'label' => 'Enable Thumbnail Generation',
										'default' => $wc_gallery_theme_support['standard']['enable'],
										'description' => '',
										'type' => 'checkbox',
										'theme_reset' => true,
									),
								),
							),
							array(
								'id' => 'big_size',
								'title' => 'Big Size',
								'description' => 'Image size identifier: <code>wcbig</code>',
								'group' => array(
									array(
										'option_name' => 'big_size_w',
										'label' => 'Max Width',
										'default' => $wc_gallery_theme_support['big']['size_w'],
										'type' => 'positive_number',
										'theme_reset' => true,
									),
									array(
										'option_name' => 'big_size_h',
										'label' => 'Max Height',
										'default' => $wc_gallery_theme_support['big']['size_h'],
										'type' => 'positive_number',
										'newline' => true,
										'theme_reset' => true,
									),
									array(
										'option_name' => 'big_enable',
										'label' => 'Enable Thumbnail Generation',
										'default' => $wc_gallery_theme_support['big']['enable'],
										'description' => '',
										'type' => 'checkbox',
										'theme_reset' => true,
									),
								),
							),
							array(
								'id' => 'fixedheightsmall_size',
								'title' => 'Fixed Height Small Size',
								'description' => 'Image size identifier: <code>wcfixedheightsmall</code>',
								'group' => array(
									array(
										'option_name' => 'fixedheightsmall_size_w',
										'label' => 'Max Width',
										'default' => $wc_gallery_theme_support['fixedheightsmall']['size_w'],
										'type' => 'positive_number',
										'theme_reset' => true,
									),
									array(
										'option_name' => 'fixedheightsmall_size_h',
										'label' => 'Max Height',
										'default' => $wc_gallery_theme_support['fixedheightsmall']['size_h'],
										'type' => 'positive_number',
										'newline' => true,
										'theme_reset' => true,
									),
									array(
										'option_name' => 'fixedheightsmall_enable',
										'label' => 'Enable Thumbnail Generation',
										'default' => $wc_gallery_theme_support['fixedheightsmall']['enable'],
										'description' => '',
										'type' => 'checkbox',
										'theme_reset' => true,
									),
								),
							),
							array(
								'id' => 'fixedheightmedium_size',
								'title' => 'Fixed Height Medium Size',
								'description' => 'Image size identifier: <code>wcfixedheightmedium</code>',
								'group' => array(
									array(
										'option_name' => 'fixedheightmedium_size_w',
										'label' => 'Max Width',
										'default' => $wc_gallery_theme_support['fixedheightmedium']['size_w'],
										'type' => 'positive_number',
										'theme_reset' => true,
									),
									array(
										'option_name' => 'fixedheightmedium_size_h',
										'label' => 'Max Height',
										'default' => $wc_gallery_theme_support['fixedheightmedium']['size_h'],
										'type' => 'positive_number',
										'newline' => true,
										'theme_reset' => true,
									),
									array(
										'option_name' => 'fixedheightmedium_enable',
										'label' => 'Enable Thumbnail Generation',
										'default' => $wc_gallery_theme_support['fixedheightmedium']['enable'],
										'description' => '',
										'type' => 'checkbox',
										'theme_reset' => true,
									),
								),
							),
							array(
								'id' => 'fixedheight_size',
								'title' => 'Fixed Height Large Size',
								'description' => 'Image size identifier: <code>wcfixedheight</code>',
								'group' => array(
									array(
										'option_name' => 'fixedheight_size_w',
										'label' => 'Max Width',
										'default' => $wc_gallery_theme_support['fixedheight']['size_w'],
										'type' => 'positive_number',
										'theme_reset' => true,
									),
									array(
										'option_name' => 'fixedheight_size_h',
										'label' => 'Max Height',
										'default' => $wc_gallery_theme_support['fixedheight']['size_h'],
										'type' => 'positive_number',
										'newline' => true,
										'theme_reset' => true,
									),
									array(
										'option_name' => 'fixedheight_enable',
										'label' => 'Enable Thumbnail Generation',
										'default' => $wc_gallery_theme_support['fixedheight']['enable'],
										'description' => '',
										'type' => 'checkbox',
										'theme_reset' => true,
									),
								),
							),
							array(
								'id' => 'carouselsmall_size',
								'title' => 'Carousel Small Size',
								'description' => 'Image size identifier: <code>wccarouselsmall</code>',
								'group' => array(
									array(
										'option_name' => 'carouselsmall_size_w',
										'label' => 'Max Width',
										'default' => $wc_gallery_theme_support['carouselsmall']['size_w'],
										'type' => 'positive_number',
										'theme_reset' => true,
									),
									array(
										'option_name' => 'carouselsmall_size_h',
										'label' => 'Max Height',
										'default' => $wc_gallery_theme_support['carouselsmall']['size_h'],
										'type' => 'positive_number',
										'newline' => true,
										'theme_reset' => true,
									),
									array(
										'option_name' => 'carouselsmall_crop',
										'label' => 'Crop to exact dimensions',
										'default' => $wc_gallery_theme_support['carouselsmall']['crop'],
										'description' => '',
										'type' => 'checkbox',
										'newline' => true,
										'theme_reset' => true,
									),
									array(
										'option_name' => 'carouselsmall_enable',
										'label' => 'Enable Thumbnail Generation',
										'default' => $wc_gallery_theme_support['carouselsmall']['enable'],
										'description' => '',
										'type' => 'checkbox',
										'theme_reset' => true,
									),
								),
							),
							array(
								'id' => 'carousel_size',
								'title' => 'Carousel Large Size',
								'description' => 'Image size identifier: <code>wccarousel</code>',
								'group' => array(
									array(
										'option_name' => 'carousel_size_w',
										'label' => 'Max Width',
										'default' => $wc_gallery_theme_support['carousel']['size_w'],
										'type' => 'positive_number',
										'theme_reset' => true,
									),
									array(
										'option_name' => 'carousel_size_h',
										'label' => 'Max Height',
										'default' => $wc_gallery_theme_support['carousel']['size_h'],
										'type' => 'positive_number',
										'newline' => true,
										'theme_reset' => true,
									),
									array(
										'option_name' => 'carousel_crop',
										'label' => 'Crop to exact dimensions',
										'default' => $wc_gallery_theme_support['carousel']['crop'],
										'description' => '',
										'type' => 'checkbox',
										'newline' => true,
										'theme_reset' => true,
									),
									array(
										'option_name' => 'carousel_enable',
										'label' => 'Enable Thumbnail Generation',
										'default' => $wc_gallery_theme_support['carousel']['enable'],
										'description' => '',
										'type' => 'checkbox',
										'theme_reset' => true,
									),
								),
							),
							array(
								'id' => 'slider_size',
								'title' => 'Slider Size',
								'description' => 'Image size identifier: <code>wcslider</code>',
								'group' => array(
									array(
										'option_name' => 'slider_size_w',
										'label' => 'Max Width',
										'default' => $wc_gallery_theme_support['slider']['size_w'],
										'type' => 'positive_number',
										'theme_reset' => true,
									),
									array(
										'option_name' => 'slider_size_h',
										'label' => 'Max Height',
										'default' => $wc_gallery_theme_support['slider']['size_h'],
										'type' => 'positive_number',
										'newline' => true,
										'theme_reset' => true,
									),
									array(
										'option_name' => 'slider_crop',
										'label' => 'Crop to exact dimensions',
										'default' => $wc_gallery_theme_support['slider']['crop'],
										'description' => '',
										'type' => 'checkbox',
										'newline' => true,
										'theme_reset' => true,
									),
									array(
										'option_name' => 'slider_enable',
										'label' => 'Enable Thumbnail Generation',
										'default' => $wc_gallery_theme_support['slider']['enable'],
										'description' => '',
										'type' => 'checkbox',
										'theme_reset' => true,
									),
								),
							),
						),
					),
				),
			),
			array(
				'id' => 'wc-gallery-misc-tab',
				'title' => 'Misc',
				'sections' => array(
					array(
						'id' => 'wc-gallery-misc-section',
						'add_section' => true, // Add a new section? Or does it already exists?
						'title' => 'Miscellaneous Options',
						'options' => array(
							array(
								'option_name' => 'enable_gallery_css',
								'title' => 'Gallery CSS',
								'default' => true,
								'description' => '',
								'label' => 'Use gallery CSS provided by plugin',
								'type' => 'checkbox',
								'theme_reset' => true,
							),
							array(
								'option_name' => 'enable_image_popup',
								'title' => 'Image Popup',
								'default' => true,
								'description' => '',
								'label' => 'Use <a target="_blank" href="http://dimsemenov.com/plugins/magnific-popup/">Magnific Popup</a> to showcase your images?',
								'type' => 'checkbox',
								'theme_reset' => true,
							),
						),
					),
				),
			),
		),
	);

	return $options;
}
add_filter( 'wc_gallery_wpcsf_options', 'wc_gallery_options', 10, 1 );

function wc_gallery_theme_support() {
	global $wc_gallery_theme_support;

	return $wc_gallery_theme_support;
}
add_filter( 'wc_gallery_wpcsf_theme_support', 'wc_gallery_theme_support', 10, 1 );
