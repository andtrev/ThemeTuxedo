<?php
/**
 * Sidebar output templates
 *
 * @package ThemeTuxedo\Template
 * @since 1.0.0
 */

/**
 * Output primary sidebar.
 *
 * Attached to the "tux_sidebar" action.
 *
 * @since 1.0.0
 */
function tux_sidebar_primary_output() {

	dynamic_sidebar( 'sidebar' );

}
add_action( 'tux_sidebar', 'tux_sidebar_primary_output' );

/**
 * Output secondary sidebar.
 *
 * Attached to the "tux_sidebar_secondary" action.
 *
 * @since 1.0.0
 */
function tux_sidebar_secondary_output() {

	dynamic_sidebar( 'sidebar-alt' );

}
add_action( 'tux_sidebar_secondary', 'tux_sidebar_secondary_output' );

/**
 * Get primary sidebar template.
 *
 * Attached to the "tux_content_after" action.
 *
 * @since 1.0.0
 */
function tux_get_sidebar_primary() {

	if ( tux_layout_get_current() == 'sidebar-none' ) return;

	get_sidebar();

}
add_action( 'tux_content_after', 'tux_get_sidebar_primary' );

/**
 * Get secondary sidebar template.
 *
 * Attached to the "tux_content_sidebar_wrapper_after" action.
 *
 * @since 1.0.0
 */
function tux_get_sidebar_secondary() {

	if ( in_array( tux_layout_get_current(), array( 'sidebar-right', 'sidebar-left', 'sidebar-none' ) ) ) return;

	get_sidebar( 'secondary' );

}
add_action( 'tux_content_sidebar_wrap_after', 'tux_get_sidebar_secondary' );

