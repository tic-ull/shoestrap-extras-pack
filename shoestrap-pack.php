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

// Add the Jumbotron Custom post type + fields
require_once( plugin_dir_path(__FILE__) . 'includes/jumbotron.php' );
// Add Custom layouts per post
require_once( plugin_dir_path(__FILE__) . 'includes/layouts.php' );
