<?php
/**
 * Footer output templates
 *
 * @package ThemeTuxedo\Template
 * @since 1.0.0
 */

/**
 * Outputs opening "footer" tag.
 *
 * Attached to the "tux_footer" action.
 *
 * @since 1.0.0
 */
function tux_footer_tag_open() {

	echo '<footer' . tux_get_attr( 'site-footer' ) . '><div' . tux_get_attr( 'wrap' ) . '>';

}
add_action( 'tux_footer', 'tux_footer_tag_open', 5 );

/**
 * Outputs the footer widget area.
 *
 * Attached to the "tux_footer" action.
 *
 * @since 1.0.0
 */
function tux_footer_widget_area_output() {

	tux_dynamic_sidebar( 'footer', array( 'before' => '<aside' . tux_get_attr( 'sidebar-footer' ) . '>' ) );

}
add_action( 'tux_footer', 'tux_footer_widget_area_output' );

/**
 * Outputs closing "footer" tag.
 *
 * Attached to the "tux_footer" action.
 *
 * @since 1.0.0
 */
function tux_footer_tag_close() {

	echo '</div></footer>';

}
add_action( 'tux_footer', 'tux_footer_tag_close', 15 );

/**
 * Output footer scripts option.
 *
 * Attached to the "wp_footer" action.
 *
 * @since 1.0.0
 */
function tux_footer_scripts_option_output() {

	$tux_options = get_option( 'tux_options' );

	echo $tux_options[ 'scripts_footer' ];

}
add_action( 'wp_footer', 'tux_footer_scripts_option_output' );

