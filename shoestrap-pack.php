<?php

/*
Plugin Name: Shoestrap Extras Pack
Plugin URI: http://wpmu.io
Description: Shoestrap Customizations and additions
Version: 0.3
Author: Aristeides Stathopoulos
Author URI:  http://aristeides.com
*/


add_action( 'init', 'ssp_initialize_cmb_meta_boxes', 9999 );
/**
 * Initialize the metabox class.
 */
function ssp_initialize_cmb_meta_boxes() {

	if ( ! class_exists( 'cmb_Meta_Box' ) )
		require_once( plugin_dir_path(__FILE__) . 'includes/cmf/init.php' );

}

function ssp_include_files() {

	// Add the Jumbotron Custom post type + fields
	require_once( plugin_dir_path(__FILE__) . 'includes/jumbotron.php' );
	// Add Custom layouts per post
	require_once( plugin_dir_path(__FILE__) . 'includes/layouts.php' );
	// Hide the title.
	require_once( plugin_dir_path(__FILE__) . 'includes/hide-title.php' );
	// Hide meta info per-post
	require_once( plugin_dir_path(__FILE__) . 'includes/hide-meta.php' );

}
add_action( 'shoestrap_include_files', 'ssp_include_files' );

/**
 * Define the metabox and field configurations.
 *
 * @param  array $meta_boxes
 * @return array
 */
function ssp_metabox( array $meta_boxes ) {

	// Get an array of the available post types
	$post_types = get_post_types( array( 'public' => true ), 'objects' );

	$pt_array = array();
	foreach ( $post_types as $post_type ) {
		if ( 'ss_jumbotron' != $post_type->name ) {
			$pt_array[] = $post_type->name;
		}
	}

	$meta_boxes[] = array(
		'id'         => 'ssp_metabox',
		'title'      => 'Shoestrap Fields',
		'pages'      => $pt_array,
		'context'    => 'normal',
		'priority'   => 'high',
		'show_names' => true, // Show field names on the left
		'fields'     => ppj_metabox_fields(),
	);

	return $meta_boxes;
}
add_filter( 'cmb_meta_boxes', 'ssp_metabox' );

/**
 * Return an array of fields.
 * Uses the 'ssp_metabox_fields' filter.
 */
function ppj_metabox_fields() {

	return apply_filters( 'ssp_metabox_fields', array() );

}