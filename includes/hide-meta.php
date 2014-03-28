<?php

/**
 * Define the metabox and field configurations.
 *
 * @param  array $meta_boxes
 * @return array
 */
function ss_hide_meta_field( $fields ) {

	$fields[] = array(
		'id'      => '_ss_hide_meta',
		'name'    => __( 'Hide Meta' ),
		'type'    => 'checkbox',
		'desc'    => __( 'Hide meta info for this post.', 'shoestrap' )
	);

	return $fields;
}
add_filter( 'ssp_metabox_fields', 'ss_hide_meta_field' );

/*
 * Checks if we've selected to hide the post meta
 */
function ssp_hide_meta( $id ) {
	$data  = get_post_meta( $id, '_ss_hide_meta', true );

	if ( isset( $data ) && ( 1 == $data || 'on' == $data ) ) {
		$value = true;
	} else {
		$value = false;
	}

	return $value;
}

/**
 * Force-hide the post meta for this post.
 */
function ssp_force_hide_meta() {
	global $post, $ss_blog;

	$hide_meta = ssp_hide_meta( $post->ID );

	if ( $hide_meta ) {
		remove_action( 'shoestrap_entry_meta', array( $ss_blog, 'meta_custom_render' ) );
	}
}
add_action( 'wp', 'ssp_force_hide_meta' );