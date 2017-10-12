<?php
/**
 * Archive output templates
 *
 * @package ThemeTuxedo\Template
 * @since 1.0.0
 */

/**
 * Output title and description for an archive page.
 *
 * Will only display on the first page of a paged archive.
 *
 * Attached to the "tux_loop_before" action.
 *
 * @since 1.0.0
 */
function tux_archive_title_desc_output() {

	if ( ! is_archive() ) return;

	the_archive_title( '<h1' . tux_get_attr( 'archive-title' ) . '>', '</h1>' );

	if ( get_query_var( 'paged' ) >= 2 ) return;

	$desc = get_the_archive_description();

	if ( is_author() ) {

		/**
		 * Allow standard WordPress archive description filters
		 * to run on the author archive description.
		 *
		 * @since 1.0.0
		 *
		 * @param string $description Archive description to be displayed.
		 */
		$desc = apply_filters( 'get_the_archive_description', get_the_author_meta( 'description' ) );

	}

	if ( $desc ) echo '<div' . tux_get_attr( 'archive-description' ) . '>' . $desc . '</div>';

}
add_action( 'tux_loop_before', 'tux_archive_title_desc_output' );

/**
 * Output search page title.
 *
 * Attached to the "tux_loop_before" action.
 *
 * @since 1.0
 */
function tux_search_title_output() {

	if ( ! is_search() ) return;

	/**
	 * Filter the search page title text.
	 *
	 * This is the text displayed before the search query terms.
	 *
	 * @since 1.0.0
	 */
	$title_text = apply_filters( 'tux_search_title_text', __( 'Search Results for:', 'tuxedo' ) );

	/**
	 * Filter the search page title.
	 *
	 * @since 1.0.0
	 */
	echo '<h1' . tux_get_attr( 'search-title' ) . '>' . apply_filters( 'tux_search_title_output', $title_text . ' ' . get_search_query() ) . '</h1>' . "\n";

}
add_action( 'tux_loop_before' , 'tux_search_title_output' );

/**
 * Output archive navigation.
 *
 * Attached to the "tux_loop_before" and "tux_post_loop_after" actions.
 *
 * @since 1.0.0
 */
function tux_archive_nav_output() {

	$tux_options = get_option( 'tux_options' );
	$cur_pos = current_action();

	if ( $cur_pos == 'tux_post_loop_before' && $tux_options[ 'archives_nav_loc' ] == 'bottom' ) return;
	if ( $cur_pos == 'tux_post_loop_after' && $tux_options[ 'archives_nav_loc' ] == 'top' ) return;

	if ( $tux_options[ 'archives_paginate' ] )
		tux_archive_nav_paginated_output();
	else
		tux_archive_nav_prev_next_output();

}
add_action( 'tux_post_loop_before', 'tux_archive_nav_output' );
add_action( 'tux_post_loop_after', 'tux_archive_nav_output' );

/**
 * Output paginated archive navigation.
 *
 * @since 1.0.0
 */
function tux_archive_nav_paginated_output() {

	global $wp_query;

	$big = 999999999; // need an unlikely integer
	$translated = __( 'Page', 'tuxedo' ); // Supply translatable string

	$links = paginate_links( (array) apply_filters( 'tux_archive_nav_paginated_args', array(
		'base'               => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
		'format'             => '?paged=%#%',
		'current'            => max( 1, get_query_var( 'paged' ) ),
		'total'              => $wp_query->max_num_pages,
		'before_page_number' => '<span class="screen-reader-text">' . $translated . ' </span>',
		'prev_text'          => apply_filters( 'tux_prev_link_text', '&laquo; ' . __( 'Previous Page', 'tuxedo' ) ),
		'next_text'          => apply_filters( 'tux_next_link_text', __( 'Next Page', 'tuxedo' ) . ' &raquo;' )
	) ) );

	if ( $links ) echo '<div' . tux_get_attr( 'archive-navigation' ) . '>' . $links . '</div>';

}

/**
 * Output previous / next archive navigation.
 *
 * @since 1.0.0
 */
function tux_archive_nav_prev_next_output() {

	$prev_link = get_previous_posts_link( apply_filters( 'tux_prev_link_text', '&laquo; ' . __( 'Previous Page', 'tuxedo' ) ) );
	$next_link = get_next_posts_link( apply_filters( 'tux_next_link_text', __( 'Next Page', 'tuxedo' ) . ' &raquo;' ) );

	$prev_link = $prev_link ? '<div class="pagination-previous alignleft">' . $prev_link . '</div>' : '';
	$next_link = $next_link ? '<div class="pagination-next alignright">' . $next_link . '</div>' : '';

	if ( $prev_link || $next_link ) echo '<div' . tux_get_attr( 'archive-navigation' ) . '>' . $prev_link . $next_link . '</div>';

}

