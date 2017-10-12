<?php
/**
 * Post output templates
 *
 * @package ThemeTuxedo\Template
 * @since 1.0.0
 */

/**
 * Default content loop.
 *
 * Attached to "tux_loop" action.
 *
 * @since 1.0.0
 */
function tux_loop_default() {

	echo '<div' . tux_get_attr( 'post-loop' ) . '>';

	if ( have_posts() ) {

		/**
		 * Fires before post while loop.
		 *
		 * @since 1.0.0
		 */
		do_action( 'tux_post_loop_before' );

		while ( have_posts() ) {

			the_post();

			/**
			 * Fires before the opening article tag.
			 *
			 * @since 1.0.0
			 */
			do_action( 'tux_post_before' );

			echo '<article' . tux_get_attr( 'post' ) . '>';

			/**
			 * Fires the post header.
			 *
			 * @since 1.0.0
			 */
			do_action( 'tux_post_header' );

			/**
			 * Fires before "entry-content" div.
			 *
			 * @since 1.0.0
			 */
			do_action( 'tux_post_content_before' );

			echo '<div' . tux_get_attr( 'entry-content' ) . '>';

			/**
			 * Fires the post content.
			 *
			 * @since 1.0.0
			 */
			do_action( 'tux_post_content' );

			echo '</div>'; // "entry-content"

			/**
			 * Fires after "entry-content" div.
			 *
			 * @since 1.0.0
			 */
			do_action( 'tux_post_content_after' );

			/**
			 * Fires the post footer.
			 *
			 * @since 1.0.0
			 */
			do_action( 'tux_post_footer' );

			echo '</article>';

			/**
			 * Fires after the closing article tag.
			 *
			 * @since 1.0.0
			 */
			do_action( 'tux_post_after' );

		}

		/**
		 * Fires after post while loop.
		 *
		 * @since 1.0.0
		 */
		do_action( 'tux_post_loop_after' );

	} else {

		/**
		 * Fires if no posts are available for the loop.
		 *
		 * @since 1.0.0
		 */
		do_action( 'tux_loop_else' );

	}

	echo '</div>'; // "post-loop"

}
add_action( 'tux_loop', 'tux_loop_default' );

/**
 * Output the opening post "header" tag.
 *
 * Attached to the "tux_post_header" action.
 *
 * @since 1.0.0
 */
function tux_post_header_tag_open() {

	echo '<header' . tux_get_attr( 'entry-header' ) . '>';

}
add_action( 'tux_post_header', 'tux_post_header_tag_open', 5 );

/**
 * Ouput the closing post "header" tag.
 *
 * Attached to the "tux_post_header" action.
 *
 * @since 1.0.0
 */
function tux_post_header_tag_close() {

	echo '</header>';

}
add_action( 'tux_post_header', 'tux_post_header_tag_close', 15 );

/**
 * Output the post title.
 *
 * Attached to the "tux_post_header" action.
 *
 * @since 1.0.0
 */
function tux_post_title_output() {

	if ( is_single() ) {

		the_title( '<h1' . tux_get_attr( 'entry-title' ) . '>', '</h1>' );

	} else {

		the_title( sprintf( '<h2' . tux_get_attr( 'entry-title' ) . '><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' );

	}

}
add_action( 'tux_post_header', 'tux_post_title_output' );

/**
 * Output the post header meta (not on pages).
 *
 * Attached to the "tux_post_header" action.
 *
 * @since 1.0.0
 */
function tux_post_header_meta_output() {

	$post_type = get_post_type();

	if ( $post_type === 'page' ) return;

	/**
	 * Filters header meta. do_shortcode is attached at priority 20,
	 * unprocessed shortcodes are available before that.
	 *
	 * @since 1.0.0
	 *
	 * @param string Header meta, unprocessed shortcodes, or full HTML.
	 */
	$header_meta = apply_filters( 'tux_header_meta', '[tux_post_published_date] [tux_post_author_archive_link] [tux_post_comments]' );

	if ( $header_meta ) {
		echo '<div' . tux_get_attr( 'entry-meta-before-content' ) . '>' . $header_meta . '</div>';
	}

}
add_action( 'tux_post_header', 'tux_post_header_meta_output', 12 );
add_filter( 'tux_header_meta', 'do_shortcode', 20 );

/**
 * Output the post featured image on archive pages.
 *
 * Attached to the "tux_post_content" action.
 *
 * @since 1.0.0
 */
function tux_post_featured_image_output() {

	$tux_options = get_option( 'tux_options' );
	if ( ! is_singular() && $tux_options[ 'archives_image' ] !== 'none' ) {

		$post_thumbnail = get_the_post_thumbnail( get_the_ID(), $tux_options[ 'archives_image_size' ], array( 'class' => 'align' . $tux_options[ 'archives_image' ] . ' post-image entry-image', 'alt' => get_the_title(), 'itemprop' => 'image' ) );
		if ( $post_thumbnail ) echo '<a href="' . get_permalink() . '" aria-hidden="true">' . $post_thumbnail . '</a>';

	}

}
add_action( 'tux_post_content', 'tux_post_featured_image_output' );

/**
 * Output the post content.
 *
 * Attached to the "tux_post_content" action.
 *
 * @since 1.0.0
 */
function tux_post_content_output() {

	$tux_options = get_option( 'tux_options' );

	if ( ( is_home() || is_archive() || is_search() ) && $tux_options[ 'archives_excerpt' ] ) {

		the_excerpt();
		if ( $tux_options[ 'archives_more_link' ] ) {
			// translators: %s: Name of current post.
			echo '<p><a href="' . get_permalink() . '" class="more-link">' . sprintf( __( 'Continue reading %s', 'tuxedo' ), the_title( '<span class="screen-reader-text">', '</span>', false ) ) . '</a></p>';
		}

	} else {

		// translators: %s: Name of current post.
		the_content( sprintf( __( 'Continue reading %s', 'tuxedo' ), the_title( '<span class="screen-reader-text">', '</span>', false ) ) );

	}

}
add_action( 'tux_post_content', 'tux_post_content_output' );

/**
 * Maybe remove the post content more link.
 *
 * Attached to the "the_content_more_link" filter.
 *
 * @since 1.0.0
 */
function tux_maybe_remove_content_more_link( $more ) {

	$tux_options = get_option( 'tux_options' );

	if ( $tux_options[ 'archives_more_link' ] ) return $more;

	return '';

}
add_filter( 'the_content_more_link', 'tux_maybe_remove_content_more_link', 999 );

/**
 * Limit post excerpt length.
 *
 * Attached to the "excerpt_length" filter.
 *
 * @since 1.0.0
 */
function tux_post_excerpt_length( $length ) {

	$tux_options = get_option( 'tux_options' );

	return intval( $tux_options[ 'archives_excerpt_length' ] );

}
add_filter( 'excerpt_length', 'tux_post_excerpt_length' );

/**
 * Ouput the post navigation links.
 *
 * Attached to the "tux_post_content" action.
 *
 * @since 1.0.0
 */
function tux_post_nav_links_output() {

	wp_link_pages( array(
		'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'tuxedo' ) . '</span>',
		'after'       => '</div>',
		'link_before' => '<span>',
		'link_after'  => '</span>',
		'pagelink'    => '<span class="screen-reader-text">' . __( 'Page', 'tuxedo' ) . ' </span>%',
		'separator'   => '<span class="screen-reader-text">, </span>',
	) );

}
add_action( 'tux_post_content', 'tux_post_nav_links_output' );

/**
 * Output the opening post "footer" tag.
 *
 * Attached to the "tux_post_footer" action.
 *
 * @since 1.0.0
 */
function tux_post_footer_tag_open() {

	echo '<footer' . tux_get_attr( 'entry-footer' ) . '>';

}
add_action ( 'tux_post_footer', 'tux_post_footer_tag_open', 5 );

/**
 * Ouput the closing post "footer" tag.
 *
 * Attached to the "tux_post_footer" action.
 *
 * @since 1.0.0
 */
function tux_post_footer_tag_close() {

	echo '</footer>';

}
add_action( 'tux_post_footer', 'tux_post_footer_tag_close', 15 );

/**
 * Output the post footer meta (not on pages).
 *
 * Attached to the "tux_post_footer" action.
 *
 * @since 1.0.0
 */
function tux_post_footer_meta_output() {

	$post_type = get_post_type();

	if ( $post_type === 'page' ) return;

	/**
	 * Filters footer meta. do_shortcode is attached at priority 20,
	 * unprocessed shortcodes are available before that.
	 *
	 * @since 1.0.0
	 *
	 * @param string Footer meta, unprocessed shortcodes, or full HTML.
	 */
	$footer_meta = apply_filters( 'tux_footer_meta', '[tux_post_categories] [tux_post_tags]' );

	if ( $footer_meta ) {
		echo '<div' . tux_get_attr( 'entry-meta-after-content' ) . '>' . $footer_meta . '</div>';
	}

}
add_action( 'tux_post_footer', 'tux_post_footer_meta_output' );
add_filter( 'tux_footer_meta', 'do_shortcode', 20 );

/**
 * Output previous / next post navigation.
 *
 * @since 1.0.0
 */
function tux_post_nav_prev_next_output() {

	if ( ! is_singular( 'post' ) ) return;

	echo '<div' . tux_get_attr( 'adjacent-entry-pagination' ) . '>';

	echo '<div class="pagination-previous alignleft">';
	previous_post_link();
	echo '</div>';

	echo '<div class="pagination-next alignright">';
	next_post_link();
	echo '</div>';

	echo '</div>';

}

