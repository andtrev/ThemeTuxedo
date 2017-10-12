<?php
/**
 * The Sidebar containing the secondary widget area
 *
 * @package ThemeTuxedo\Template
 * @since 1.0.0
 */

echo '<aside' . tux_get_attr( 'sidebar-secondary' ) . '>';

/**
 * Fires before the secondary sidebar.
 *
 * @since 1.0.0
 */
do_action( 'tux_sidebar_secondary_before' );

/**
 * Fires the secondary sidebar.
 *
 * @since 1.0.0
 */
do_action( 'tux_sidebar_secondary' );

/**
 * Fires after the secondary sidebar.
 *
 * @since 1.0.0
 */
do_action( 'tux_sidebar_secondary_after' );

echo '</aside>';

