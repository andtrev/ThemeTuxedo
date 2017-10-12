<?php
/**
 * The Sidebar containing the main widget area
 *
 * @package ThemeTuxedo\Template
 * @since 1.0.0
 */

echo '<aside' . tux_get_attr( 'sidebar-primary' ) . '>';

/**
 * Fires before the primary sidebar.
 *
 * @since 1.0.0
 */
do_action( 'tux_sidebar_before' );

/**
 * Fires the primary sidebar.
 *
 * @since 1.0.0
 */
do_action( 'tux_sidebar' );

/**
 * Fires after the primary sidebar.
 *
 * @since 1.0.0
 */
do_action( 'tux_sidebar_after' );

echo '</aside>';

