<?php

/**
 * Define the titlebox and field configurations.
 *
 * @param  array $title_boxes
 * @return array
 */
function ssep_hide_title_field( $fields ) {

	$fields[] = array(
		'id'      => '_ss_hide_title',
		'name'    => __( 'Hide title' ),
		'type'    => 'checkbox',
		'desc'    => __( 'Hide the title of this post.', 'shoestrap' )
	);

	return $fields;
}
add_filter( 'ssep_metabox_fields', 'ssep_hide_title_field' );

/*
 * Checks if we've selected to hide the post title
 */
function ssep_hide_title( $id ) {
	$data  = get_post_meta( $id, '_ss_hide_title', true );

	if ( isset( $data ) && ( 1 == $data || 'on' == $data ) ) {
		$value = true;
	} else {
		$value = false;
	}

	return $value;
}

/**
 * Force-hide the post title for this post.
 */
function ssep_force_hide_title() {
	global $post, $ss_blog;

	$hide_title = ssep_hide_title( $post->ID );

	if ( $hide_title ) {
		add_filter( 'shoestrap_title_section', '__return_null' );
	}
}
add_action( 'wp', 'ssep_force_hide_title' );

