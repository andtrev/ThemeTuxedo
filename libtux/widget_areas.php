<?php
/**
 * Widget area (sidebars) helper functions
 *
 * @package ThemeTuxedo\WidgetAreas
 * @since 1.0.0
 */

/**
 * Register sidebar helper.
 *
 * Registers a sidebar and automatically fills in
 * certain arguments with Theme Tuxedo defaults.
 * Argument array parameter is identical to the
 * WordPress "register_sidebar" function.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 *
 * @since 1.0.0
 *
 * @param array $args {
 *     Identical to WordPress "register_sidebar" parameter.
 *     Most important arguments are as follows.
 *
 *     @type string $name        Sidebar name (default is localized "Sidebar" and numeric ID).
 *     @type string $description Text description of what/where the sidebar is. Shown on widget management screen.
 *     @type string $id          Sidebar id - Must be all in lowercase, with no spaces (default is a numeric auto-incremented ID).
 * }
 * @return string Sidebar id.
 */
function tux_register_sidebar( $args = array() ) {

	return register_sidebar(

		wp_parse_args( $args,

			/**
			 * Filters register sidebar default arguments.
			 *
			 * Defaults for "before_widget", "after_widget", "before_title", "after_title"
			 * are added by Theme Tuxedo.
			 *
			 * @since 1.0.0
			 *
			 * @param array $default_args {
			 *     Default arguments.
			 *
			 *     @type string $before_widget Default HTML to place before every widget (default: "<section id="%1$s" class="widget %2$s"><div class="widget-wrap">").
			 *     @type string $after_widget  Default HTML to place after every widget (default: "</div></section>").
			 *     @type string $before_title  Default HTML to place before every title (default: "<h4 class="widget-title widgettitle">").
			 *     @type string $after_title   Default HTML to place after every title (default: "</h4>").
			 * }
			 * @param array $args Arguments passed to "tux_register_sidebar".
			 */
			apply_filters( 'tux_register_sidebar_defaults', array(

				'before_widget' => '<section id="%1$s" class="widget %2$s"><div class="widget-wrap">',

				'after_widget'  => '</div></section>',

				'before_title'  => '<h4 class="widget-title widgettitle">',

				'after_title'   => '</h4>',

	), $args ) ) );

}

/**
 * Sidebar output.
 *
 * Displays a sidebar by id with optional parameters.
 *
 * @since 1.0.0
 *
 * @param string $id Sidebar id to output.
 * @param array $args {
 *     Optional. Display arguments.
 *
 *     @type string  $before        Default HTML to place before every sidebar (default: "<aside class="widget-area sidebar sidebar-$id">").
 *     @type string  $after         Default HTML to place after every sidebar (default: "</aside>").
 *     @type string  $default       Default HTML to display if the sidebar is empty. (default: empty)
 *     @type boolean $show_inactive Output sidebar if empty. (default: false)
 *     @type string  $before_hook   Hook name to fire before the sidebar. (default: tux_$id_before)
 *     @type string  $after_hook    Hook name to fire after the sidebar. (default: tux_$id_after)
 *     @type boolean $wrap          Output a wrap div. (default: true)
 * }
 * @return bool True if output, false otherwise.
 */
function tux_dynamic_sidebar( $id, $args = array() ) {

	if ( empty( $id ) ) return false;

	$args = wp_parse_args( $args, array(

		'before'        => '<aside class="widget-area sidebar sidebar-' . $id . '">',

		'after'         => '</aside>',

		'default'       => '',

		'show_inactive' => false,

		'before_hook'   => 'tux_sidebar_' . $id . '_before',

		'after_hook'    => 'tux_sidebar_' . $id . '_after',

		'wrap'          => true,

	) );

	if ( ! is_active_sidebar( $id ) && ! $args[ 'show_inactive' ] ) return false;

	echo $args[ 'before' ];
	if ( $args[ 'wrap' ] ) echo '<div class="wrap">';

	if ( ! empty( $args[ 'before_hook' ] ) ) {

		/**
		 * Fires before a sidebar, after the opening tag.
		 *
		 * @since 1.0.0
		 */
		do_action( $args[ 'before_hook' ] );

	}

	if ( ! dynamic_sidebar( $id ) ) echo $args[ 'default' ];

	if ( ! empty( $args[ 'after_hook' ] ) ) {

		/**
		 * Fires after a sidebar, before the closing tag.
		 *
		 * @since 1.0.0
		 */
		do_action( $args[ 'after_hook' ] );

	}

	if ( $args[ 'wrap' ] ) echo '</div>';
	echo $args[ 'after' ];

	return true;

}

/**
 * Register widget areas.
 *
 * Attached to the "tux_setup" action.
 *
 * @since 1.0.0
 */
function tux_sidebar_register() {

	if ( ! current_theme_supports( 'tux-widget-areas' ) ) return;

	$sidebars = get_theme_support( 'tux-widget-areas' );
	if ( $sidebars === false ) return;

	foreach ( (array) $sidebars[0] as $sidebar_args ) {
		tux_register_sidebar( (array) $sidebar_args );
	}

	/**
	 * Fires after sidebar registration.
	 *
	 * @since 1.0.0
	 */
	do_action( 'tux_widget_areas_register_after' );

}
add_action( 'tux_setup', 'tux_sidebar_register' );

/**
 * Outputs the site top area.
 *
 * Attached to the "tux_header_before" action.
 *
 * @since 1.0.0
 */
function tux_site_top_widget_area_output() {

	tux_dynamic_sidebar( 'site-top', array( 'before' => '<aside' . tux_get_attr( 'sidebar-site-top' ) . '>' ) );

}
add_action( 'tux_header_before', 'tux_site_top_widget_area_output' );

/**
 * Outputs the page top area.
 *
 * Attached to the "tux_content_sidebar_wrap_before" action.
 *
 * @since 1.0.0
 */
function tux_page_top_widget_area_output() {

	tux_dynamic_sidebar( 'page-top', array( 'before' => '<aside' . tux_get_attr( 'sidebar-page-top' ) . '>' ) );

}
add_action( 'tux_content_sidebar_wrap_before', 'tux_page_top_widget_area_output' );

/**
 * Outputs the content top area.
 *
 * Attached to the "tux_loop_before" action.
 *
 * @since 1.0.0
 */
function tux_content_top_widget_area_output() {

	tux_dynamic_sidebar( 'content-top', array( 'before' => '<aside' . tux_get_attr( 'sidebar-content-top' ) . '>' ) );

}
add_action( 'tux_loop_before', 'tux_content_top_widget_area_output' );

/**
 * Outputs the content bottom area.
 *
 * Attached to the "tux_loop_after" action.
 *
 * @since 1.0.0
 */
function tux_content_bottom_widget_area_output() {

	tux_dynamic_sidebar( 'content-bottom', array( 'before' => '<aside' . tux_get_attr( 'sidebar-content-bottom' ) . '>' ) );

}
add_action( 'tux_loop_after', 'tux_content_bottom_widget_area_output' );

/**
 * Outputs the page bottom area.
 *
 * Attached to the "tux_content_sidebar_wrap_after" action.
 *
 * @since 1.0.0
 */
function tux_page_bottom_widget_area_output() {

	tux_dynamic_sidebar( 'page-bottom', array( 'before' => '<aside' . tux_get_attr( 'sidebar-page-bottom' ) . '>' ) );

}
add_action( 'tux_content_sidebar_wrap_after', 'tux_page_bottom_widget_area_output' );

/**
 * Outputs the site bottom area.
 *
 * Attached to the "tux_footer_after" action.
 *
 * @since 1.0.0
 */
function tux_site_bottom_widget_area_output() {

	tux_dynamic_sidebar( 'site-bottom', array( 'before' => '<aside' . tux_get_attr( 'sidebar-site-bottom' ) . '>' ) );

}
add_action( 'tux_footer_after', 'tux_site_bottom_widget_area_output' );

