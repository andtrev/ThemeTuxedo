<?php
/**
 * Theme Tuxedo initialization functions and definitions
 *
 * @package ThemeTuxedo\Initialize
 * @since 1.0.0
 */

/**
 * Define constants.
 *
 * Attached to the "tux_init" action.
 *
 * @since 1.0.0
 */
function tux_declare_constants() {

	// Versioning.
	define( 'TUX_VERSION', '0.0.4' );
	define( 'TUX_VERSION_DB', '0.0.4' );

	// Directories.
	define( 'TUX_DIR', trailingslashit( get_template_directory() ) );
	define( 'TUX_LIB_DIR', TUX_DIR . 'libtux/' );
	define( 'TUX_LIB_JS_DIR', TUX_LIB_DIR . 'scripts/' );
	define( 'TUX_LIB_CSS_DIR', TUX_LIB_DIR . 'styles/' );
	define( 'TUX_LIB_IMG_DIR', TUX_LIB_DIR . 'images/' );

}
add_action( 'tux_init', 'tux_declare_constants' );

/**
 * Load the text domain for translations.
 *
 * Attached to the "tux_init" action.
 *
 * @since 1.0.0
 */
function tux_load_textdomain() {

	load_theme_textdomain( 'tuxedo', TUX_LIB_DIR . 'languages' );

}
add_action( 'tux_init', 'tux_load_textdomain' );

/**
 * Define supported theme features.
 *
 * Attached to the "tux_init" action.
 *
 * @since 1.0.0
 */
function tux_add_theme_support() {

	// Enable HTML5 markup output.
	add_theme_support( 'html5' );

	// Let WordPress handle the document "title" tag.
	add_theme_support( 'title-tag' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	// Add support for post thumbnails.
	add_theme_support( 'post-thumbnails' );

	// Add support for WooCommerce.
	add_theme_support( 'woocommerce' );

	// Add support for Tuxedo menus if child theme hasn't added any.
	add_theme_support( 'menus' );
	if ( ! current_theme_supports( 'tux-menus' ) )
		add_theme_support( 'tux-menus', array(
			'primary'   => __( 'Primary Navigation Menu', 'tuxedo' ),
			'secondary' => __( 'Secondary Navigation Menu', 'tuxedo' ),
			'footer'    => __( 'Footer Navigation Menu', 'tuxedo' ),
		) );

	// Add support for Tuxedo widget areas if child theme hasn't added any.
	if ( ! current_theme_supports( 'tux-widget-areas' ) )
		add_theme_support( 'tux-widget-areas', array(
			array(
				'id'          => 'site-top',
				'name'        => __( 'Site Top', 'tuxedo' ),
				'description' => __( 'This is the site top widget area.', 'tuxedo' ),
			),
			array(
				'id'          => 'header-right',
				'name'        => __( 'Header', 'tuxedo' ),
				'description' => __( 'This is the header widget area.', 'tuxedo' ),
			),
			array(
				'id'          => 'page-top',
				'name'        => __( 'Page Top', 'tuxedo' ),
				'description' => __( 'This is the page top widget area.', 'tuxedo' ),
			),
			array(
				'id'          => 'content-top',
				'name'        => __( 'Content Top', 'tuxedo' ),
				'description' => __( 'This is the content top widget area.', 'tuxedo' ),
			),
			array(
				'id'          => 'sidebar',
				'name'        => __( 'Primary Sidebar', 'tuxedo' ),
				'description' => __( 'This is the primary sidebar for layout options with one or two sidebars.', 'tuxedo' ),
			),
			array(
				'id'          => 'sidebar-alt',
				'name'        => __( 'Secondary Sidebar', 'tuxedo' ),
				'description' => __( 'This is the secondary sidebar for layout options with two sidebars.', 'tuxedo' ),
			),
			array(
				'id'          => 'content-bottom',
				'name'        => __( 'Content Bottom', 'tuxedo' ),
				'description' => __( 'This is the content bottom widget area.', 'tuxedo' ),
			),
			array(
				'id'          => 'page-bottom',
				'name'        => __( 'Page Bottom', 'tuxedo' ),
				'description' => __( 'This is the page bottom widget area.', 'tuxedo' ),
			),
			array(
				'id'          => 'footer',
				'name'        => __( 'Footer', 'tuxedo' ),
				'description' => __( 'This is the footer widget area.', 'tuxedo' ),
			),
			array(
				'id'          => 'site-bottom',
				'name'        => __( 'Site Bottom', 'tuxedo' ),
				'description' => __( 'This is the site bottom widget area.', 'tuxedo' ),
			),
		) );

}
add_action( 'tux_init', 'tux_add_theme_support' );

/**
 * Define supported post features.
 *
 * Attached to the "tux_init" action.
 *
 * @since 1.0.0
 */
function tux_add_post_type_support() {

	add_post_type_support( 'post', array( 'tux-layouts' ) );
	add_post_type_support( 'page', array( 'tux-layouts' ) );
	add_post_type_support( 'product', array( 'tux-layouts' ) );

}
add_action( 'tux_init', 'tux_add_post_type_support' );

/**
 * Include all required php scripts for the framework.
 *
 * Attached to the "tux_init" action.
 *
 * @since 1.0.0
 */
function tux_include_required_php() {

	// Head (meta, styles, scripts).
	require_once( TUX_LIB_DIR . 'template_head.php' );

	// Layout.
	require_once( TUX_LIB_DIR . 'layout.php' );

	// Update.
	require_once( TUX_LIB_DIR . 'update.php' );

	// Markup attributes.
	require_once( TUX_LIB_DIR . 'markup_attribute.php' );

	// Post meta output template.
	require_once( TUX_LIB_DIR . 'template_postmeta.php' );

	// Header output template.
	require_once( TUX_LIB_DIR . 'template_header.php' );

	// Footer output template.
	require_once( TUX_LIB_DIR . 'template_footer.php' );

	// Menu output template.
	require_once( TUX_LIB_DIR . 'template_menu.php' );

	// Post output template.
	require_once( TUX_LIB_DIR . 'template_post.php' );

	// Comment output template.
	require_once( TUX_LIB_DIR . 'template_comments.php' );

	// Archive output template.
	require_once( TUX_LIB_DIR . 'template_archive.php' );

	// Sidebar output template.
	require_once( TUX_LIB_DIR . 'template_sidebar.php' );

	// Search form output template.
	require_once( TUX_LIB_DIR . 'template_searchform.php' );

	// Widget area helper functions.
	require_once( TUX_LIB_DIR . 'widget_areas.php' );

	// Widgets.
	require_once( TUX_LIB_DIR . 'widgets.php' );

	// Breadcrumb output template.
	require_once( TUX_LIB_DIR . 'template_breadcrumb.php' );

	global $wp_customize;
	if ( is_admin() || ( is_a( $wp_customize, 'WP_Customize_Manager' ) && $wp_customize->is_preview() ) ) {

		// Admin page manager, panel, section, control classes.
		require_once( TUX_LIB_DIR . 'class-tux-adminpage-manager.php' );

		// Metabox manager, panel, section, control classes.
		require_once( TUX_LIB_DIR . 'class-tux-metabox-manager.php' );

		// Post option metaboxes.
		require_once( TUX_LIB_DIR . 'options_post.php' );

		// Admin and customizer options.
		require_once( TUX_LIB_DIR . 'options_admin.php' );

		// Options sanitization.
		require_once( TUX_LIB_DIR . 'options_sanitize.php' );

	}

}
add_action( 'tux_init', 'tux_include_required_php' );

