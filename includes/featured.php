<?php

function shoestrap_init_featured_content_support() {

	// Add support for featured content.
	add_theme_support( 'featured-content', array(
		'featured_content_filter' => 'shoestrap_get_featured_posts',
		'max_posts' => 6,
	) );

}
add_action( 'after_setup_theme', 'shoestrap_init_featured_content_support' );

/**
 * Getter function for Featured Content Plugin.
 *
 * @return array An array of WP_Post objects.
 */
function shoestrap_get_featured_posts() {
	return apply_filters( 'shoestrap_get_featured_posts', array() );
}

/**
 * A helper conditional function that returns a boolean value.
 *
 * @return bool Whether there are featured posts.
 */
function shoestrap_has_featured_posts() {
	return ! is_paged() && (bool) shoestrap_get_featured_posts();
}


function ssep_featured_content_template() {
	global $ss_framework, $ss_settings, $post;

	$featured_posts = shoestrap_get_featured_posts();

	if ( empty( $featured_posts ) ) {
		return;
	}

	// Build the image arguments that we'll need
	$data = array();

	// Get the image width
	if ( 'fluid' == $ss_settings['site_style'] ) {
		// On fluid layouts set the image width to the 'screen_tablet' width (normally 768px).
		$data['width']  = $ss_settings['screen_tablet'];
	} else {
		// On non-fluid layouts, set the width of the image to 1/3 of the total site width.
		$data['width']  = $ss_settings['screen_large_desktop'] / 3;
	}

	// Calculate the image height using the golden ratio analogy.
	$data['height'] = intval( $data['width'] / 1.61803398875 );

	$data['crop']   = true; // Should we crop? (boolean)
	$data['retina'] = true; // Enable or disable retina images (boolean)
	$data['resize'] = true; // Should we resize the image (boolean)

	$column_class = $ss_framework->column_classes( array( 'tablet' => 4 ), 'string' );

	$i = 0;

	echo '<div id="featured-content" class="featured-content jumbotron">';
	echo $ss_framework->open_container();

	foreach ( (array) $featured_posts as $order => $post ) { $i++;

		setup_postdata( $post ); ?>

		<article <?php post_class( $column_class ); ?>>
			<?php if ( has_post_thumbnail( $post->ID ) ) {
				echo '<a class="post-thumbnail" href="' . get_permalink( $post->ID ) . '">';

					// The URL to the image
					$data['url'] =  wp_get_attachment_url( get_post_thumbnail_id( $post->ID ) );

					// Resize the image
					$image = Shoestrap_Image::image_resize( $data );

					// Echo the image
					echo '<img src="' . $image['url'] . '" class="featured-post-image">';

				echo '</a>';

			} ?>

			<header class="entry-header">
				<h3 class="featured entry-title">
					<a href="<?php echo esc_url( get_permalink( $post->ID ) ); ?>" rel="bookmark"><?php echo get_the_title( $post->ID ); ?></a>
				</h3>
			</header><!-- .entry-header -->
		</article><!-- #post-## -->
		<?php

	}

	do_action( 'shoestrap_featured_posts_after' );

	wp_reset_postdata();

	echo $ss_framework->close_container();
	echo '</div>';
	echo $ss_framework->clearfix();
}
add_action( 'shoestrap_pre_wrap', 'ssep_featured_content_template', 150 );

/**
 * Add some LESS styles to the compiler.
 */
function ssep_featured_content_styles( $less ) {
	return $less . '
	#featured-content.featured-content {
		padding: 0;
		article {
			padding: 0;
			a {
				color: @body-bg;
				text-decoration: none;
			}
			.entry-header {
				position: absolute;
				top: 0;
				background: rgba(0, 0, 0, 0.5);
				width: 100%;
				padding-left: 10px;
			}
		}
	}';
}
add_filter( 'shoestrap_compiler', 'ssep_featured_content_styles', 87 );