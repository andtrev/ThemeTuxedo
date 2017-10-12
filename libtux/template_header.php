<?php
/**
 * Header output templates
 *
 * @package ThemeTuxedo\Template
 * @since 1.0.0
 */

/**
 * Outputs skiplinks for accessibility.
 *
 * Attached to the "tux_before" action.
 *
 * @since 1.0.0
 */
function tux_skiplinks_output() {

	if ( has_nav_menu( 'primary' ) )
		echo '<a class="skip-link screen-reader-text" href="#tux-nav-primary">' . __( 'Skip to primary navigation', 'tuxedo' ) . '</a>';

	echo '<a class="skip-link screen-reader-text" href="#tux-content">' . __( 'Skip to content', 'tuxedo' ) . '</a>';

}
add_action( 'tux_before', 'tux_skiplinks_output', 3 );

/**
 * Outputs opening "header" tag.
 *
 * Attached to the "tux_header" action.
 *
 * @since 1.0.0
 */
function tux_header_tag_open() {

	echo '<header' . tux_get_attr( 'site-header' ) . '><div' . tux_get_attr( 'wrap' ) . '>';

}
add_action( 'tux_header', 'tux_header_tag_open', 5 );

/**
 * Outputs the header including site title, site description, and widget area.
 *
 * Attached to the "tux_header" action.
 *
 * @since 1.0.0
 */
function tux_header_output() {

	echo '<div' . tux_get_attr( 'title-area' ) . '>';

	/**
	 * Fires the header site title.
	 *
	 * @since 1.0.0
	 */
	do_action( 'tux_header_site_title' );

	/**
	 * Fires the header site description.
	 *
	 * @since 1.0.0
	 */
	do_action( 'tux_header_site_description' );

	echo '</div>';

	// If the "header-right" sidebar is active or if the "tux_header_right" hook has actions attached then output them.
	if ( is_active_sidebar( 'header-right' ) || has_action( 'tux_header_right' ) ) {

		echo '<aside' . tux_get_attr( 'header-widget-area' ) . '>';

		/**
		 * Fires before the "header-right" sidebar.
		 *
		 * @since 1.0.0
		 */
		do_action( 'tux_header_right' );

		dynamic_sidebar( 'header-right' );

		echo '</aside>';

	}

}
add_action( 'tux_header', 'tux_header_output' );

/**
 * Outputs closing "header" tag.
 *
 * Attached to the "tux_header" action.
 *
 * @since 1.0.0
 */
function tux_header_tag_close() {

	echo '</div></header>';

}
add_action( 'tux_header', 'tux_header_tag_close', 15 );

/**
 * Outputs the header site title.
 *
 * Attached to the "tux_header_site_title" action.
 *
 * @since 1.0.0
 */
function tux_header_site_title_output() {

	if ( is_front_page() ) {

		echo '<h1' . tux_get_attr( 'site-title' ) . '>' . esc_attr( get_bloginfo( 'name' ) ) . '</h1>';

	} else {

		echo '<p' . tux_get_attr( 'site-title' ) . '><a href="' . esc_url( home_url( '/' ) ) . '" title="' . esc_attr( get_bloginfo( 'name' ) ) . '" rel="home">' . get_bloginfo( 'name' ) . '</a></p>';

	}

}
add_action( 'tux_header_site_title', 'tux_header_site_title_output' );

/**
 * Outpute the header site description.
 *
 * Attached to the "tux_header_site_description" action.
 *
 * @since 1.0.0
 */
function tux_header_site_description_output() {

	$description = get_bloginfo( 'description', 'display' );

	if ( $description ) {

		if ( is_front_page() && is_home() ) {

			echo '<h2' . tux_get_attr( 'site-description' ) . '>' . $description . '</h2>';

		} else {

			echo '<p' . tux_get_attr( 'site-description' ) . '>' . $description . '</p>';

		}

	}

}
add_action( 'tux_header_site_description', 'tux_header_site_description_output' );

