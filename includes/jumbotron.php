<?php

/*
 * Create the "Jumbotron" custom post type
 */
add_action( 'init', 'ss_jumbotron_post_type' );
function ss_jumbotron_post_type() {
	register_post_type(
		'ss_jumbotron',
		array(
			'labels'            => array(
				'name'          => __( 'Jumbotron' ),
				'singular_name' => __( 'Jumbotron' )
			),
			'public'            => true,
			'has_archive'       => false,
		)
	);
}

/**
 * Define the metabox and field configurations.
 *
 * @param  array $meta_boxes
 * @return array
 */
function ss_jumbotron_metabox( $fields ) {

	// Get the list of jumbotron posts.
	$args = array(
		'posts_per_page'   => -1,
		'offset'           => 0,
		'orderby'          => 'post_date',
		'order'            => 'DESC',
		'post_type'        => 'ss_jumbotron',
		'post_status'      => 'publish',
		'suppress_filters' => true
	);
	$jumbotrons = get_posts( $args );

	$jumbos_array = array();
	$jumbos_array[0] = __( 'None', 'shoestrap' );
	foreach ( $jumbotrons as $jumbotron ) {
		$jumbos_array[$jumbotron->ID] = get_the_title( $jumbotron->ID );
	}

	$fields[] = array(
		'id'      => '_ss_assign_jumbotron',
		'name'    => __( 'Assign Jumbotron' ),
		'type'    => 'select',
		'options' => $jumbos_array,
		'desc'    => __( 'Assign a custom Jumbotron to this post', 'shoestrap' )
	);

	return $fields;
}
add_filter( 'ssp_metabox_fields', 'ss_jumbotron_metabox' );

/*
 * Checks if a custom Jumbotron is assigned to the current post.
 * Returns the post ID of the Jumbotron assigned (or false if none).
 */
function ssp_check_jumbotron( $id ) {
	$data  = get_post_meta( $id, '_ss_assign_jumbotron', true );

	if ( isset( $data ) && 0 != $data ) {
		$value = $data;
	} else {
		$value = false;
	}

	return $value;
}


/*
 * Render the jumbotron.
 */
function ssppj_jumbotron_content() {
	global $post, $ss_settings, $ss_framework;

	// Check if a custom Jumbotron content exists
	if ( ! ssp_check_jumbotron( $post->ID ) ) {
		return;
	}

	// Get the assigned Jumbotron
	$jumbotron_obj = get_post( ssp_check_jumbotron( $post->ID ) );
	// Get the content of the assigned Jumbotron
	$content       = apply_filters( 'the_content', $jumbotron_obj->post_content );

	$site_style   = $ss_settings['site_style'];
	$nocontainer  = $ss_settings['jumbotron_nocontainer'];
	?>

	<div class="clearfix"></div>
	<?php if ( $site_style == 'boxed' && $nocontainer != 1 ) : ?>
		<?php echo $ss_framework->open_container( 'div' ); ?>
	<?php endif; ?>

		<div class="jumbotron">
			<?php if ( $nocontainer != 1 && $site_style == 'wide' || $site_style == 'boxed' ) : ?>
				<?php echo $ss_framework->open_container( 'div' ); ?>
			<?php endif; ?>

			<?php echo $content; ?>

			<?php if ( $nocontainer != 1 && $site_style == 'wide' || $site_style == 'boxed' ) : ?>
				<?php echo $ss_framework->close_container( 'div' ); ?>
			<?php endif; ?>
		</div>

	<?php if ( $site_style == 'boxed' && $nocontainer != 1 ) : ?>
		<?php echo $ss_framework->open_container( 'div' ); ?>
	<?php endif;

}
add_action( 'shoestrap_pre_main', 'ssppj_jumbotron_content', 10 );