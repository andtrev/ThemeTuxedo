<?php
/**
 * Layout functions
 *
 * @package ThemeTuxedo\Layout
 * @since 1.0.0
 */

/**
 * Initialize layout defaults.
 *
 * @since 1.0.0
 */
function tux_layout_defaults() {

	global $tux_layouts;

	/**
	 * Filter default layouts.
	 *
	 * @since 1.0.0
	 *
	 * @param array Keys are layout ids, values are human readable names.
	 */
	$tux_layouts[ 'layouts' ] = apply_filters( 'tux_layout_defaults', array(
		'sidebar-none'        => __( 'Content', 'tuxedo' ),
		'sidebar-left'        => __( 'Sidebar / Content', 'tuxedo' ),
		'sidebar-right'       => __( 'Content / Sidebar', 'tuxedo' ),
		'sidebar-left-right'  => __( 'Sidebar / Content / Sidebar', 'tuxedo' ),
		'sidebar-left-left'   => __( 'Sidebar / Sidebar / Content', 'tuxedo' ),
		'sidebar-right-right' => __( 'Content / Sidebar / Sidebar', 'tuxedo' ),
	) );

	/**
	 * Filter default layout fallback.
	 *
	 * @since 1.0.0
	 *
	 * @param string $layout Default layout id.
	 */
	$tux_layouts[ 'default' ] = apply_filters( 'tux_layout_default', is_rtl() ? 'sidebar-left' : 'sidebar-right' );

}
add_action( 'tux_init', 'tux_layout_defaults', 0 );

/**
 * Register a layout.
 *
 * @since 1.0.0
 *
 * @param string $id   Unique layout id.
 * @param string $desc Human readable description for menu display.
 */
function tux_layout_register( $id = '', $desc = '' ) {

	if ( ! $id || ! $desc ) return;

	global $tux_layouts;

	$tux_layouts[ 'layouts' ][ $id ] = $desc;

}

/**
 * Unregister a layout.
 *
 * @since 1.0.0
 *
 * @param string $id Unique layout id.
 */
function tux_layout_unregister( $id = '' ) {

	if ( ! $id ) return;

	global $tux_layouts;

	unset( $tux_layouts[ 'layout' ][ $id ] );

}

/**
 * Set the default layout.
 *
 * @since 1.0.0
 *
 * @param string $id Unique layout id.
 */
function tux_layout_set_default( $id = '' ) {

	if ( ! $id ) return;

	global $tux_layouts;

	$tux_layouts[ 'default' ] = $id;

}

/**
 * Return the default layout.
 *
 * @since 1.0.0
 *
 * @return string Unique layout id.
 */
function tux_layout_get_default() {

	global $tux_layouts;

	return isset( $tux_layouts[ 'default' ] ) ? esc_attr( $tux_layouts[ 'default' ] ) : '';

}

/**
 * Return a list of registered layouts.
 *
 * @since 1.0.0
 *
 * @return array Array key is layout id, value is description.
 */
function tux_layout_get_registered() {

	global $tux_layouts;

	return isset( $tux_layouts[ 'layouts' ] ) ? $tux_layouts[ 'layouts' ] : array();

}

/**
 * Return the current layout.
 *
 * @since 1.0.0
 *
 * @return string Unique layout id.
 */
function tux_layout_get_current() {

	/**
	 * Filter current layout short circuit.
	 *
	 * @since 1.0.0
	 *
	 * @param null
	 */
	$pre = apply_filters( 'tux_layout_current', null );
	if ( $pre !== null ) return $pre;

	if ( is_singular() ) {

		$current_layout = get_post_meta( get_the_ID(), '_tux_layout', true );
		if ( ! isset( $current_layout[ 'layout' ] ) || $current_layout[ 'layout' ] == 'default' ) $current_layout = get_option( 'tux_options' );

	} else {

		$current_layout = get_option( 'tux_options' );

	}

	$registered_layouts = tux_layout_get_registered();

	return ( isset( $current_layout[ 'layout' ] ) && isset( $registered_layouts[ $current_layout[ 'layout' ] ] ) ) ? esc_attr( $current_layout[ 'layout' ] ) : tux_layout_get_default();

}

/**
 * Filter the content width based on current layout.
 *
 * Attached to the "content_width" filter.
 *
 * @since 1.0.0
 *
 * @param integer $default Content width for "sidebar-left" and "sidebar-right" layouts.
 * @param integer $small   Content width for "sidebar-left-right", "sidebar-left-left" and
 *                         "sidebar-right-right" layouts.
 * @param integer $large   Content width for "sidebar-none" layout.
 * @return integer Content width.
 */
function tux_content_width( $default, $small, $large ) {

	switch ( tux_layout_get_current() ) {

		case 'sidebar-none':
			$width = $large;
			break;

		case 'sidebar-left-right':
		case 'sidebar-left-left':
		case 'sidebar-right-right':
			$width = $small;
			break;

		default:
			$width = $default;

	}

	return $width;

}
add_filter( 'content_width', 'tux_content_width', 10, 3 );

/**
 * Quick return to filter layout.
 *
 * @since 0.0.1
 */
function __return_sidebar_none( $layout ) {
	return 'sidebar-none';
}

/**
 * Quick return to filter layout.
 *
 * @since 0.0.1
 */
function __return_sidebar_left( $layout ) {
	return 'sidebar-left';
}

/**
 * Quick return to filter layout.
 *
 * @since 0.0.1
 */
function __return_sidebar_right( $layout ) {
	return 'sidebar-right';
}

/**
 * Quick return to filter layout.
 *
 * @since 0.0.1
 */
function __return_sidebar_left_right( $layout ) {
	return 'sidebar-left-right';
}

/**
 * Quick return to filter layout.
 *
 * @since 0.0.1
 */
function __return_sidebar_left_left( $layout ) {
	return 'sidebar-left-left';
}

/**
 * Quick return to filter layout.
 *
 * @since 0.0.1
 */
function __return_sidebar_right_right( $layout ) {
	return 'sidebar-right-right';
}

