<?php
/**
 * The template for displaying the header
 *
 * Displays all of the head element and everything up until the "site-inner" div.
 *
 * @package ThemeTuxedo\Template
 * @since 1.0.0
 */
?><!DOCTYPE html>
<html <?php language_attributes( 'html' ); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<!--[if lt IE 9]><script src="<?php echo esc_url( get_template_directory_uri() ); ?>/libtux/scripts/html5shiv.min.js"></script><![endif]-->
	<?php wp_head(); ?>
</head>

<body<?php tux_attr( 'body' ); ?>>
<?php
/**
 * Fires after opening body tag.
 *
 * @since 1.0.0
 */
do_action( 'tux_before' );

// Open "site-container".
echo '<div' . tux_get_attr( 'site-container' ) . '>';

/**
 * Fires before the header.
 *
 * @since 1.0.0
 */
do_action( 'tux_header_before' );

/**
 * Fires the header.
 *
 * @since 1.0.0
 */
do_action( 'tux_header' );

/**
 * Fires after the header.
 *
 * @since 1.0.0
 */
do_action( 'tux_header_after' );

// Open "site-inner".
echo '<div' . tux_get_attr( 'site-inner' ) . '><div' . tux_get_attr( 'wrap' ) . '>';

