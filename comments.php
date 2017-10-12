<?php
/**
 * The template for displaying comments
 *
 * The area of the page that contains both current comments
 * and the comment form.
 *
 * @package ThemeTuxedo\Template
 * @since 1.0.0
 */

// Lock out direct accessing the comments.php script.
if ( ! empty( $_SERVER['SCRIPT_FILENAME'] ) && 'comments.php' === basename( $_SERVER['SCRIPT_FILENAME'] ) )
		die ( 'This page con not be accessed directly.' );

// Inform visitor if post is password protected.
if ( post_password_required() ) {
		printf( '<p class="alert">%s</p>', __( 'This post is password protected. Enter the password to view comments.', 'tuxedo' ) );
		return;
}

/**
 * Fires before the comment list.
 *
 * @since 1.0.0
 */
do_action( 'tux_comments_before' );

/**
 * Fires the comment list.
 *
 * @since 1.0.0
 */
do_action( 'tux_comments' );

/**
 * Fires after the comment list.
 *
 * @since 1.0.0
 */
do_action( 'tux_comments_after' );

/**
 * Fires before the trackback ping list.
 *
 * @since 1.0.0
 */
do_action( 'tux_pings_before' );

/**
 * Fires the trackback ping list.
 *
 * @since 1.0.0
 */
do_action( 'tux_pings' );

/**
 * Fires after the trackback ping list.
 *
 * @since 1.0.0
 */
do_action( 'tux_pings_after' );

/**
 * Fires before the comment form.
 *
 * @since 1.0.0
 */
do_action( 'tux_comment_form_before' );

/**
 * Fires the comment form.
 *
 * @since 1.0.0
 */
do_action( 'tux_comment_form' );

/**
 * Fires after the comment form.
 *
 * @since 1.0.0
 */
do_action( 'tux_comment_form_after' );

