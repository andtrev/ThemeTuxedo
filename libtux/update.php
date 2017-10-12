<?php
/**
 * Database updater
 *
 * @package ThemeTuxedo\Update
 * @since 1.0.0
 */

 /**
  * Database defaults.
  *
  * @since 1.0.0
  *
  * @return array Array of default values.
  */
function tux_options_defaults() {

	/**
	 * Filter default options.
	 *
	 * @since 1.0.0
	 *
	 * @param array Empty, insert key/value to override a default setting.
	 */
	$default_overrides = apply_filters( 'tux_options_defaults', array() );

	return wp_parse_args( (array) $default_overrides, array(

		'version_db'               => TUX_VERSION_DB,
		'layout'                   => tux_layout_get_default(),
		'body_class'               => '',
		'post_class'               => '',
		'breadcrumbs_homepage'     => 0,
		'breadcrumbs_bloghomepage' => 0,
		'breadcrumbs_posts'        => 0,
		'breadcrumbs_pages'        => 0,
		'breadcrumbs_archives'     => 0,
		'breadcrumbs_notfound'     => 0,
		'breadcrumbs_attachments'  => 0,
		'archives_nav_loc'         => 'bottom',
		'archives_paginate'        => 1,
		'archives_image'           => 'none',
		'archives_image_size'      => 'full',
		'archives_more_link'       => 1,
		'archives_excerpt'         => 0,
		'archives_excerpt_length'  => 55,
		'scripts_header'           => '',
		'scripts_footer'           => '',

	) );

}

/**
 * Update options database to new version.
 *
 * Attached to the "admin_init" action.
 *
 * @since 1.0.0
 */
function tux_update_options_database() {

	$tux_options = get_option( 'tux_options' );

	if ( $tux_options !== false && isset( $tux_options[ 'version_db' ] ) && version_compare( $tux_options[ 'version_db' ], TUX_VERSION_DB, '>=' ) ) return;

	if ( ! is_array( $tux_options ) ) $tux_options = array();

	$tux_options = wp_parse_args( $tux_options, tux_options_defaults() );

	$tux_options[ 'version_db' ] = TUX_VERSION_DB;

	update_option( 'tux_options', $tux_options );

	/**
	 * Fires after database update.
	 *
	 * @since 1.0.0
	 */
	do_action( 'tux_update' );

}
add_action( 'admin_init', 'tux_update_options_database', 20 );

/**
 * Redirects user to "About" page after an update.
 *
 * Attached to the "tux_update" action.
 *
 * @since 1.0.0
 */
function tux_update_about_redirect() {

	if ( ! is_admin() || ! current_user_can( 'edit_theme_options' ) ) return;

	wp_redirect( admin_url( 'themes.php?page=tux_options_page&tab=tux_about_tab' ) );
	exit;

}
add_action( 'tux_update', 'tux_update_about_redirect' );

