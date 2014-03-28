<?php

/**
 * Define the metabox and field configurations.
 *
 * @param  array $meta_boxes
 * @return array
 */
function ss_layout_metabox( array $meta_boxes ) {

	// Start with an underscore to hide fields from custom fields list
	$prefix = '_ss_';

	// Get an array of the available post types
	$post_types = get_post_types( array( 'public' => true ), 'objects' );

	$pt_array = array();
	foreach ( $post_types as $post_type ) {
		if ( 'ss_layout' != $post_type->name ) {
			$pt_array[] = $post_type->name;
		}
	}

	$meta_boxes[] = array(
		'id'         => '_ss_layout_metabox',
		'title'      => 'Assign a Layout',
		'pages'      => $pt_array,
		'context'    => 'normal',
		'priority'   => 'high',
		'show_names' => false, // Show field names on the left
		'fields'     => array(
			array(
				'id'      => $prefix . 'assign_layout',
				'name'    => __( 'Assign Layout' ),
				'type'    => 'radio_inline',
				'options' => array(
					'default' => __( 'Default', 'shoestrap' ),
					0         => __( 'Full-Width', 'shoestrap' ),
					1         => __( 'Right Sidebar', 'shoestrap' ),
					2         => __( 'Left Sidebar', 'shoestrap' ),
					3         => __( '2 Left Sidebars', 'shoestrap' ),
					4         => __( '2 Right Sidebars', 'shoestrap' ),
					5         => __( '1 Left & 1 Right Sidebars', 'shoestrap' ),
				),
				'desc'    => __( 'Assign a custom Layout to this post', 'shoestrap' )
			)
		),
	);

	return $meta_boxes;
}
add_filter( 'cmb_meta_boxes', 'ss_layout_metabox' );

/*
 * Checks if a custom layout is assigned to the current post.
 * Returns the layout ID (or false if none).
 */
function ssp_check_layout( $id ) {
	$data  = get_post_meta( $id, '_ss_assign_layout', true );

	if ( isset( $data ) && 'default' != $data ) {
		$value = $data;
	} else {
		$value = false;
	}

	return $value;
}

/**
 * Force the selected layout
 */
function ssp_force_layout() {
	global $post, $ss_layout;

	$layout = ssp_check_layout( $post->ID );

	if ( $layout ) {
		$ss_layout->set_layout( $layout );
	}

	if ( 0 == $layout ) {
		add_filter( 'shoestrap_display_primary_sidebar', '__return_false', 999 );
		add_filter( 'shoestrap_display_secondary_sidebar', '__return_false', 999 );
	} elseif ( 2 <= $layout ) {
		add_filter( 'shoestrap_display_secondary_sidebar', '__return_false', 999 );
	}
}
add_action( 'wp', 'ssp_force_layout' );