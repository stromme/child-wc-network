<?php
/**
 * Name: WC Network Theme Functions
 * Description: 
 *
 * @package Playground
 * @author Hatch
 */
 
/**
 * Register bulletin custom post type
 * Bulletins are used in a feed on the Toolbox Dashboard
 *
 * @since 0.0.1
 */

function tb_register_bulletin() {
	register_post_type('bulletin',
		array(
			'labels' => array(
				'name' => __('Bulletins'),
				'singular_name' => __('Bulletin'),
			),
			// 'menu_position' => 5,
			'public' => false,
			'show_ui' => true,
			'show_in_menu' => true,
			'publicly_queryable' => true,
			'rewrite' => array('slug' => 'toolbox'),
			'supports' => array('title', 'editor', 'thumbnail'),
			'hierarchical' => true,
			'map_meta_cap' => true,
			'capability_type' => 'post'
		)
	);
}

add_action('init', 'tb_register_bulletin');


?>