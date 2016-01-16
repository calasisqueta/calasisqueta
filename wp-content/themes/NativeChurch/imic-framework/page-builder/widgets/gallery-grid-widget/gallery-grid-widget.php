<?php

/*
Widget Name: Gallery Grid Widget
Description: A widget to show Gallery items grid view.
Author: imithemes
Author URI: http://imithemes.com
*/

class Gallery_Grid_Widget extends SiteOrigin_Widget {
	function __construct() {

		parent::__construct(
			'gallery-grid-widget',
			__('Gallery Grid Widget', 'imic-framework'),
			array(
				'description' => __('A widget to show Gallery items grid view.', 'imic-framework'),
				'panels_icon' => 'dashicons dashicons-format-gallery',
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
					'label' => __('All galleries button text', 'imic-framework'),
					'default' => __('All Galleries', 'imic-framework'),
					'description' => __('This button will be displayed only if the widget has title.', 'imic-framework'),
				),

				'allpostsurl' => array(
					'type' => 'link',
					'label' => __('All galleries button URL', 'imic-framework'),
					'description' => __('This button will be displayed only if the widget has title.', 'imic-framework'),
				),

				'categories' => array(
					'type' => 'text',
					'label' => __('Categories (Enter comma separated sermon category slugs)', 'imic-framework'),
				),
				'number_of_posts' => array(
					'type' => 'slider',
					'label' => __( 'Number of Gallery Items to show', 'imic-framework' ),
					'default' => 4,
					'min' => 1,
					'max' => 50,
					'integer' => true,
				),
				'show_post_meta' => array(
					'type' => 'checkbox',
					'default' => false,
					'label' => __('Show gallery item titles', 'imic-framework'),
				),
				'filters' => array(
					'type' => 'checkbox',
					'default' => false,
					'label' => __('Show categories filter', 'imic-framework'),
				),
				'grid_column' => array(
					'type' => 'select',
					'state_name' => 'grid',
					'prompt' => __( 'Choose Grid Column', 'framework' ),
					'options' => array(
						'6' => __( 'Two', 'framework' ),
						'4' => __( 'Three', 'framework' ),
						'3' => __( 'Four', 'framework' ),
					)
				),
				
			),
			plugin_dir_path(__FILE__)
		);
	}


	
	function get_template_name( $instance ) {
		return 'grid-view';
	}

	function get_style_name($instance) {
		return false;
	}

	function get_less_variables($instance){
		return false;
	}


}

siteorigin_widget_register('gallery-grid-widget', __FILE__, 'Gallery_Grid_Widget');