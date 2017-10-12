<?php
/**
 * Framework loader
 *
 * @package ThemeTuxedo\Framework
 * @since 1.0.0
 */

/**
 * Fires before the framework initialization.
 *
 * @since 1.0.0
 */
do_action( 'tux_init_before' );

/**
 * Include initialization functions and
 * attach them to "tux_init" action.
 */
require_once( get_template_directory() . '/libtux/init.php' );

/**
 * Fires the framework initialization.
 *
 * @since 1.0.0
 */
do_action( 'tux_init' );

/**
 * Fires the framework setup.
 *
 * @since 1.0.0
 */
do_action( 'tux_setup' );

/**
 * Output the template.
 *
 * @since 1.0.0
 */
function suit_up() {

	// Load header.php template.
	get_header();

	/**
	 * Fires before "content-sidebar-wrapper".
	 *
	 * @since 1.0.0
	 */
	do_action( 'tux_content_sidebar_wrap_before' );

	echo '<div' . tux_get_attr( 'content-sidebar-wrap' ) . '>';

	/**
	 * Fires before "content".
	 *
	 * @since 1.0.0
	 */
	do_action( 'tux_content_before' );

	echo '<main' . tux_get_attr( 'content' ) . '>';

	/**
	 * Fires before the loop.
	 *
	 * @since 1.0.0
	 */
	do_action( 'tux_loop_before' );

	/**
	 * Fires the loop.
	 *
	 * @since 1.0.0
	 */
	do_action( 'tux_loop' );

	/**
	 * Fires after the loop.
	 *
	 * @since 1.0.0
	 */
	do_action( 'tux_loop_after' );

	echo '</main>';

	/**
	 * Fires after "content".
	 *
	 * @since 1.0.0
	 */
	do_action( 'tux_content_after' );

	echo '</div>';

	/**
	 * Fires after "content-sidebar-wrapper".
	 *
	 * @since 1.0.0
	 */
	do_action( 'tux_content_sidebar_wrap_after' );

	// Load footer.php template.
	get_footer();

}

