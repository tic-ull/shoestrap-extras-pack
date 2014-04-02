<?php

if( ! class_exists( 'EDD_SL_Plugin_Updater' ) ) {
	// load our custom updater
	include( dirname( __FILE__ ) . '/EDD_SL_Plugin_Updater.php' );
}

function ssp_plugin_updater() {

	// setup the updater
	$edd_updater = new EDD_SL_Plugin_Updater( 'http://shoestrap.org', SSEP_FILE_FATH, array( 
			'version'   => '0.4',
			'license'   => '33d741209efc16e45237d3840800f958',
			'item_name' => 'Shoestrap Extras Pack',
			'author'    => 'aristath'
		)
	);

}
add_action( 'admin_init', 'ssp_plugin_updater' );


function ssp_plugin_updater_activate_license() {

	// Do not continue processing if the license is already active.
	if ( 'valid' == get_transient( 'ssp_license_status' ) ) {
		return;
	}

	// data to send in our API request
	$api_params = array( 
		'edd_action' => 'activate_license', 
		'license'    => '33d741209efc16e45237d3840800f958', 
		'item_name'  => urlencode( 'Shoestrap Extras Pack' )
	);
		
	// Call the custom API.
	$response = wp_remote_get( add_query_arg( $api_params, 'http://shoestrap.org' ), array( 'timeout' => 15, 'sslverify' => false ) );

	// make sure the response came back okay
	if ( is_wp_error( $response ) ) {
		return false;
	}

	// decode the license data
	$license_data = json_decode( wp_remote_retrieve_body( $response ) );

	if ( 'valid' == $license_data->license ) {
		// If the license is valid, cache th response for 3 days
		set_transient( 'ssp_license_status', $license_data->license, 72 * 60 * 60 );
	} else {
		// If the license is NOT valid, cache the response for 3 hours.
		set_transient( 'ssp_license_status', $license_data->license, 3 * 60 * 60 );
	}
}
add_action('admin_init', 'ssp_plugin_updater_activate_license');