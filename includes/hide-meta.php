<?php

/**
 * Define the metabox and field configurations.
 *
 * @param  array $meta_boxes
 * @return array
 */
function ssep_hide_meta_field( $fields ) {

	$fields[] = array(
		'id'      => '_ss_hide_meta',
		'name'    => __( 'Hide Meta' ),
		'type'    => 'checkbox',
		'desc'    => __( 'Hide meta info for this post.', 'shoestrap' )
	);

	return $fields;
}
add_filter( 'ssep_metabox_fields', 'ssep_hide_meta_field' );

/*
 * Checks if we've selected to hide the post meta
 */
function ssep_hide_meta( $id ) {
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
function ssep_force_hide_meta() {
	global $post, $ss_blog;

	$hide_meta = ssep_hide_meta( $post->ID );

	if ( $hide_meta ) {
		remove_action( 'shoestrap_entry_meta', array( $ss_blog, 'meta_custom_render' ) );
		add_filter( 'shoestrap_the_tags', '__return_null' );
		add_filter( 'shoestrap_the_cats', '__return_null' );
	}
}
add_action( 'wp', 'ssep_force_hide_meta' );