<?php
/**
 * Head output templates
 *
 * @package ThemeTuxedo\Template
 * @since 1.0.0
 */

/**
 * Remove WordPress generator meta from site and feed headers.
 *
 * Attached to the "the_generator" filter.
 *
 * @since 1.0.0
 *
 * @param string $generator_type The generator output.
 * @param string $type           The type of generator to output. Accepts "html",
 *                               "xhtml", "atom", "rss2", "rdf", "comment", "export".
 */
function tux_remove_wordpress_generator( $generator_type, $type ) {

	if ( $type !== 'export' ) return '';

	return $generator_type;

}
add_filter( 'the_generator', 'tux_remove_wordpress_generator', 10, 2 );

/**
 * Add the viewport meta tag to head.
 *
 * Attached to the "wp_head" action.
 *
 * @since 1.0.0
 */
function tux_meta_viewport_output() {

	echo '<meta id="tuxviewport" name="viewport" content="width=device-width, initial-scale=1">';

}
add_action( 'wp_head', 'tux_meta_viewport_output' );

/**
 * Add the pingback link to head, if the "Allow link notifications from other
 * blogs" setting is checked.
 *
 * Attached to the "wp_head" action.
 *
 * @since 1.0.0
 */
function tux_link_pingback_output() {

	if ( get_option( 'default_ping_status' === 'open' ) )
		echo '<link rel="pingback" href="' . get_bloginfo( 'pingback_url' ) . '">';

}
add_action( 'wp_head', 'tux_link_pingback_output' );

/**
 * Output header scripts option.
 *
 * Attached to the "wp_head" action.
 *
 * @since 1.0.0
 */
function tux_header_scripts_option_output() {

	$tux_options = get_option( 'tux_options' );

	echo $tux_options[ 'scripts_header' ];

}
add_action( 'wp_head', 'tux_header_scripts_option_output' );

