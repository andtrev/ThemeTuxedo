<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the "site-inner" and "site-wrapper" div and all content after.
 *
 * @package ThemeTuxedo\Template
 * @since 1.0.0
 */

// Close "site-inner-wrap" and "site-inner".
echo '</div></div>';

/**
 * Fires before the footer.
 *
 * @since 1.0.0
 */
do_action( 'tux_footer_before' );

/**
 * Fires the footer.
 *
 * @since 1.0.0
 */
do_action( 'tux_footer' );

/**
 * Fires after the footer.
 *
 * @since 1.0.0
 */
do_action( 'tux_footer_after' );

// Close "site-container".
echo '</div>';

/**
 * Fires before closing body tag.
 *
 * @since 1.0.0
 */
do_action( 'tux_after' );

wp_footer(); ?>
</body>
</html>
