<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package ThemeTuxedo\Template
 * @since 1.0.0
 */

// Remove default loop.
remove_action( 'tux_loop', 'tux_loop_default', 10 );

/**
 * Output 404 page template.
 *
 * Attached to the "tux_loop" action.
 *
 * @since 1.0.0
 */
function tux_404_output() {

	$post_query_name = str_replace( "-", " ", preg_replace( "/(.*)-(html|htm|php|asp|aspx)$/", "$1", get_query_var( 'name' ) ) );

	echo '<article class="entry"><div class="entry-content">';

	echo '<h1 class="entry-title">' . sprintf( __( 'Page not found: %s', 'tuxedo' ), esc_html( $post_query_name ) ) . '</h1>';
	echo '<p>' . __( 'Sorry, but we were unable to find the page you were looking for.', 'tuxedo' ) . '</p>';
	echo '<p>' . sprintf( __( 'Please try searching for the page below, try one of the suggested pages, or visit our <a href="%s">homepage</a> to see if you can find it there.', 'tuxedo' ), home_url() ) . '</p>';
	echo '<p>' . get_search_form() . '</p>';

	echo '<h3>' . __( 'Suggested pages:', 'tuxedo' ) . '</h3>';
	$posts = query_posts( 's=' . $post_query_name );
	if ( count( $posts ) > 0 ) {
		echo '<h4>' . sprintf( __( 'A search for &#8220;%s&#8221; found these pages:', 'tuxedo' ), esc_html( $post_query_name ) ) . '</h4>';
		echo '<ul>';
		foreach ( $posts as $post )
			echo '<li><a href="' . get_permalink( $post->ID ) . '">' . $post->post_title . '</a></li>';
		echo '</ul>';
	}
	echo '<h4>' . __( 'Pages:', 'tuxedo' ), '</h4>';
	echo '<ul>';
	wp_list_pages( 'title_li=' );
	echo '</ul>';
	echo '<h4>' . __( 'Categories:', 'tuxedo' ), '</h4>';
	echo '<ul>';
	wp_list_categories( 'sort_column=name&title_li=' );
	echo '</ul>';
	echo '<h4>' . __( 'Monthly:', 'tuxedo' ), '</h4>';
	echo '<ul>';
	wp_get_archives( 'type=monthly' );
	echo '</ul>';
	echo '<h4>' . __( 'Recent Posts:', 'tuxedo' ), '</h4>';
	echo '<ul>';
	wp_get_archives( 'type=postbypost&limit=50' );
	echo '</ul>';

	echo '</div></article>';

}
add_action( 'tux_loop', 'tux_404_output' );

// Output template.
suit_up();

