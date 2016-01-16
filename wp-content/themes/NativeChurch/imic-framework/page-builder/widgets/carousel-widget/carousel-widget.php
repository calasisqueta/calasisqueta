<?php

/*
Widget Name: Image Carousel Widget
Description: A widget to show a carousel or list of images/logos.
Author: imithemes
Author URI: http://imithemes.com
*/

class Carousel_Widget extends SiteOrigin_Widget {
	function __construct() {

		parent::__construct(
			'carousel-widget',
			__('Carousel Widget', 'imic-framework'),
			array(
				'description' => __('A widget to show a carousel or list of images/logos.', 'imic-framework'),
				'panels_icon' => 'dashicons dashicons-list-view',
				'panels_groups' => array('framework')
			),
			array(

			),
			array(
				'title' => array(
					'type' => 'text',
					'label' => __('Title', 'siteorigin-widgets'),
				),

				'allpostsbtn' => array(
					'type' => 'text',
					'label' => __('Button Text', 'imic-framework'),
					'default' => __('Button', 'imic-framework'),
					'description' => __('This button will be displayed only if the widget has title.', 'imic-framework'),
				),

				'allpostsurl' => array(
					'type' => 'link',
					'label' => __('Button URL', 'imic-framework'),
					'description' => __('This button will be displayed only if the widget has title.', 'imic-framework'),
				),
				'images' => array(
					'type' => 'repeater',
					'label' => __('Images', 'imic-framework'),
					'item_name' => __('Image', 'imic-framework'),
					'item_label' => array(
						'selector' => "[id*='image-title']",
						'update_event' => 'change',
						'value_method' => 'val'
					),
					'fields' => array(

						'icon_image' => array(
							'type' => 'media',
							'library' => 'image',
							'label' => __('Upload image', 'imic-framework'),
						),

						'icon_size' => array(
							'type' => 'select',
							'label' => __('Image size', 'siteorigin-widgets'),
							'options' => array(
								'full' => __('Full', 'siteorigin-widgets'),
								'large' => __('Large', 'siteorigin-widgets'),
								'medium' => __('Medium', 'siteorigin-widgets'),
								'thumb' => __('Thumbnail', 'siteorigin-widgets'),
							),
						),

						'icon_title' => array(
							'type' => 'text',
							'label' => __('Title text to show under the image', 'imic-framework'),
						),

						'more_url' => array(
							'type' => 'link',
							'label' => __('Image URL', 'siteorigin-widgets'),
						),
						'new_window' => array(
							'type' => 'checkbox',
							'label' => __('Open URL in a new window', 'siteorigin-widgets'),
							'default' => false,
						),
					),
				),
				'number_of_posts' => array(
					'type' => 'slider',
					'label' => __( 'Number of images/logos to show in a row', 'imic-framework' ),
					'default' => 4,
					'min' => 1,
					'max' => 5,
					'integer' => true,
				),
				'display_type' => array(
					'type' => 'select',
					'state_name' => 'list',
					'label' => __( 'Choose View', 'imic-framework' ),
					'prompt' => __( 'Choose Display Style', 'framework' ),
					'options' => array(
						'list' => __( 'List', 'framework' ),
						'carousel' => __( 'Carousel', 'framework' ),
					)
				),
				'autoplay' => array(
					'type' => 'select',
					'state_name' => 'list',
					'label' => __( 'Autoplay Carousel', 'imic-framework' ),
					'prompt' => __( 'Autoplay Carousel', 'framework' ),
					'options' => array(
						'yes' => __( 'Yes', 'framework' ),
						'no' => __( 'No', 'framework' ),
					)
				),
				'navigation' => array(
					'type' => 'select',
					'state_name' => 'list',
					'label' => __( 'Carousel Navigation', 'imic-framework' ),
					'prompt' => __( 'Show Carousel Navigation', 'framework' ),
					'options' => array(
						'yes' => __( 'Yes', 'framework' ),
						'no' => __( 'No', 'framework' ),
					)
				),
				'pagination' => array(
					'type' => 'select',
					'state_name' => 'list',
					'label' => __( 'Carousel Pagination', 'imic-framework' ),
					'prompt' => __( 'Show Carousel Pagination', 'framework' ),
					'options' => array(
						'yes' => __( 'Yes', 'framework' ),
						'no' => __( 'No', 'framework' ),
					)
				),
			),
			plugin_dir_path(__FILE__)
		);
	}
	
	
	function get_template_name( $instance ) {
		return $instance['display_type'] == 'list' ? 'list-view' : 'carousel-view';
	}


	function get_style_name($instance) {
		return false;
	}

	function get_less_variables($instance){
		return array();
	}
	function modify_instance($instance){
		return $instance;
	}


}

siteorigin_widget_register('carousel-widget', __FILE__, 'Carousel_Widget');