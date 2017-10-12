<?php
/**
 * Menu output templates
 *
 * @package ThemeTuxedo\Template
 * @since 1.0.0
 */

/**
 * Register custom navigation menu locations added through "tux-menus" theme support option.
 *
 * Attached to the "after_setup_theme" action.
 *
 * @since 1.0.0
 */
function tux_menu_register_locations() {

	if ( ! current_theme_supports( 'tux-menus' ) ) return;

	$menus = get_theme_support( 'tux-menus' );
	if ( $menus === false ) return;

	register_nav_menus( (array) $menus[0] );

	/**
	 * Fires after custom navigation menu location registration.
	 *
	 * @since 1.0.0
	 */
	do_action( 'tux_menu_register_locations_after' );

}
add_action( 'after_setup_theme', 'tux_menu_register_locations' );

/**
 * Output menu attached to "primary" location.
 *
 * Attched to the "tux_header_after" action.
 *
 * @since 1.0.0
 */
function tux_menu_primary_output() {

	tux_nav_menu( 'primary' );

}
add_action( 'tux_header_after', 'tux_menu_primary_output' );

/**
 * Output menu attached to "secondary" location.
 *
 * Attched to the "tux_header_after" action.
 *
 * @since 1.0.0
 */
function tux_menu_secondary_output() {

	tux_nav_menu( 'secondary' );

}
add_action( 'tux_header_after', 'tux_menu_secondary_output' );

/**
 * Output menu attached to "footer" location.
 *
 * Attched to the "tux_header_after" action.
 *
 * @since 1.0.0
 */
function tux_menu_footer_output() {

	tux_nav_menu( 'footer' );

}
add_action( 'tux_footer_after', 'tux_menu_footer_output' );

/**
 * Output navigation menu attached to a specified location.
 *
 * @since 1.0.0
 *
 * @param string $loc  Menu location slug.
 * @param array  $args Optional. Menu arguments. See http://codex.wordpress.org/Function_Reference/wp_nav_menu
 */
function tux_nav_menu( $loc, $args = array() ) {

	if ( ! has_nav_menu( $loc ) ) return;

	/**
	 * Filter nav menu arguments.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args {
	 *     Menu arguments.
	 *
	 *     @type string  $theme_location  Location ID/slug of menu.
	 *     @type string  $container       Whether to wrap the ul, and what to wrap it with.
	 *                                    Allowed tags are div and nav. Use false for no container.
	 *     @type string  $container_class The class that is applied to the container.
	 *     @type string  $menu_class      The class that is applied to the ul element which encloses
	 *                                    the menu items. Multiple classes can be separated with spaces.
	 *     @type string  $link_before     Output text before the link text.
	 *     @type string  $link_after      Output text after the link text.
	 *     @type string  $fallback_cb     If the menu doesn't exist, the fallback function to use.
	 *     @type boolean $echo            Whether to echo the menu or return it. For returning menu use "0".
	 *     @type boolean $wrap            Output a wrap div. (default: true)
	 * }
	 */
	$args = apply_filters( 'tux_nav_menu_args', wp_parse_args( $args, array(
		'theme_location'  => $loc,
		'container'       => false,
		'container_class' => '',
		'menu_class'      => 'menu tux-nav-menu menu-' . $loc,
		'link_before'     => '<span' . tux_get_attr( 'menu-item-title' ) . '>',
		'link_after'      => '</span>',
		'fallback_cb'     => false,
		'echo'            => 0,
		'wrap'            => true,
	) ) );

	$menu = wp_nav_menu( $args );

	if ( ! $menu ) return;

	echo '<nav' . tux_get_attr( 'nav-' . sanitize_key( $loc ) ) . '>';
	if ( $args[ 'wrap' ] ) echo '<div' . tux_get_attr( 'wrap' ) . '>';
	echo '<div' . tux_get_attr( 'tux-mobile-menu' ) . '><span></span></div>';

	/**
	 * Filter nav menu output.
	 *
	 * @since 1.0.0
	 *
	 * @param string $menu HTML menu output.
	 * @param array  $args Menu arguments.
	 */
	echo apply_filters( 'tux_nav_menu_output', $menu, $args );

	if ( $args[ 'wrap' ] ) echo '</div>';
	echo '</nav>';

}

/**
 * Filter menu link attributes.
 *
 * Attached to the "nav_menu_link_attributes" filter.
 *
 * @since 1.0.0
 */
function tux_menu_link_attrs( $attrs ){

	return wp_parse_args( tux_get_attr( 'menu-item-link', true ), $attrs );

}
add_filter( 'nav_menu_link_attributes', 'tux_menu_link_attrs' );

/**
 * Add standard Tuxedo classes to menu widgets.
 *
 * Attached to the "widget_nav_menu_args" filter.
 *
 * @since 1.0.0
 */
function tux_add_menu_widget_classes( $nav_menu_args, $nav_menu, $args ) {

	return wp_parse_args( array(
		'container'       => false,
		'container_class' => '',
		'menu_class'      => 'menu tux-nav-menu menu-widget',
		'link_before'     => '<span' . tux_get_attr( 'menu-item-title' ) . '>',
		'link_after'      => '</span>',
	), $nav_menu_args );

}
add_filter( 'widget_nav_menu_args', 'tux_add_menu_widget_classes', 10, 3 );

/**
 * Add mobile menu markup to menu widgets.
 *
 * Attached to the "dynamic_sidebar_params" filter.
 *
 * @since 1.0.0
 */
function tux_add_menu_widget_nav_tags( $params ) {

	if ( strpos( $params[ 0 ][ 'widget_id' ], 'nav_menu' ) !== false ) {

		$params[ 0 ][ 'before_widget' ] .= '<nav' . tux_get_attr( 'nav-widget' ) . '><div' . tux_get_attr( 'tux-mobile-menu' ) . '><span></span></div>';
		$params[ 0 ][ 'after_widget' ] = '</nav>' . $params[ 0 ][ 'after_widget' ];

	}

	return $params;

}
add_filter( 'dynamic_sidebar_params', 'tux_add_menu_widget_nav_tags' );

