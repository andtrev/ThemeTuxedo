<?php
/**
 * Admin options
 *
 * @package ThemeTuxedo\Options
 * @since 1.0.0
 */

/**
 * "Options" and "About" admin pages and customizer controls.
 * Admin page options should be added in this order:
 * panel -> section -> setting -> control.
 *
 * Attached to the "tux_adminpage_register" and "customize_register" actions.
 *
 * @since 1.0.0
 *
 * @param Tux_AdminPage_Manager $AP_Manager Admin page manager.
 */
function tux_options_admin( $AP_Manager ) {

	$AP_Manager->add_panel( 'tux_options_page', array(
		'title'      => __( 'Theme Tuxedo', 'tuxedo' ),
		'menu_title' => __( 'Theme Tuxedo', 'tuxedo' ),
		'parent_id'  => 'themes.php',
		'tabs'       => array(
			'tux_options_tab' => __( 'Options', 'tuxedo' ),
			'tux_about_tab'   => __( 'About', 'tuxedo' ),
		),
	) );

	// Options tab.

	// Info options.

	if ( is_a( $AP_Manager, 'Tux_AdminPage_Manager' ) ) {

		$AP_Manager->add_section( 'tux_options_info', array(
			'title'       => __( 'Info', 'tuxedo' ),
			'description' => sprintf( __( 'You Are Currently Running Version: %s', 'tuxedo' ), TUX_VERSION ),
			'panel'       => 'tux_options_page',
			'tab'         => 'tux_options_tab',
		) );

	}

	// Layout options.

	$AP_Manager->add_section( 'tux_options_layout', array(
		'title' => __( 'Theme Layout', 'tuxedo' ),
		'panel' => 'tux_options_page',
		'tab'   => 'tux_options_tab',
	) );

	$AP_Manager->add_setting( 'tux_options[layout]', array(
		'default'           => tux_layout_get_default(),
		'sanitize_callback' => 'tux_sanitize_textbox',
		'section'           => 'tux_options_layout',
		'type'              => 'option',
	) );

	$AP_Manager->add_setting( 'tux_options[body_class]', array(
		'default'           => '',
		'sanitize_callback' => 'tux_sanitize_textbox',
		'section'           => 'tux_options_layout',
		'type'              => 'option',
	) );

	$AP_Manager->add_setting( 'tux_options[post_class]', array(
		'default'           => '',
		'sanitize_callback' => 'tux_sanitize_textbox',
		'section'           => 'tux_options_layout',
		'type'              => 'option',
	) );

	$AP_Manager->add_control( 'tux_theme_layout_select', array(
		'settings' => 'tux_options[layout]',
		'section'  => 'tux_options_layout',
		'label'    => __( 'Default Layout', 'tuxedo' ),
		'type'     => 'select',
		'choices'  => tux_layout_get_registered(),
	) );

	$AP_Manager->add_control( 'tux_theme_layout_body_class', array(
		'settings'    => 'tux_options[body_class]',
		'section'     => 'tux_options_layout',
		'label'       => __( 'Site Wide Custom Body Classes', 'tuxedo' ),
		'type'        => 'text',
		'input_attrs' => array( 'style' => 'width:100%;' ),
	) );

	$AP_Manager->add_control( 'tux_theme_layout_post_class', array(
		'settings'    => 'tux_options[post_class]',
		'section'     => 'tux_options_layout',
		'label'       => __( 'Site Wide Custom Post Classes', 'tuxedo' ),
		'type'        => 'text',
		'input_attrs' => array( 'style' => 'width:100%;' ),
	) );

	// Breadcrumb options.

	$tux_breadcrumb_settings = array(
		'breadcrumbs_homepage'     => __( 'Homepage', 'tuxedo' ),
		'breadcrumbs_bloghomepage' => __( 'Blog Posts Page', 'tuxedo' ),
		'breadcrumbs_posts'        => __( 'Posts', 'tuxedo' ),
		'breadcrumbs_pages'        => __( 'Pages', 'tuxedo' ),
		'breadcrumbs_archives'     => __( 'Archives', 'tuxedo' ),
		'breadcrumbs_notfound'     => __( '404 Not Found Page', 'tuxedo' ),
		'breadcrumbs_attachments'  => __( 'Attachments', 'tuxedo' ),
	);

	$AP_Manager->add_section( 'tux_options_breadcrumbs', array(
		'title'       => __( 'Breadcrumbs', 'tuxedo' ),
		'description' => __( 'Show Breadcrumbs On:', 'tuxedo' ),
		'panel'       => 'tux_options_page',
		'tab'         => 'tux_options_tab',
	) );

	foreach( $tux_breadcrumb_settings as $breadcrumb_id => $breadcrumb_label ) {

		$AP_Manager->add_setting( 'tux_options[' . $breadcrumb_id . ']', array(
			'default'           => 0,
			'sanitize_callback' => 'tux_sanitize_checkbox',
			'section'           => 'tux_options_breadcrumbs',
			'type'              => 'option',
		) );

		$AP_Manager->add_control( 'tux_' . $breadcrumb_id, array(
			'settings' => 'tux_options[' . $breadcrumb_id . ']',
			'section'  => 'tux_options_breadcrumbs',
			'label'    => $breadcrumb_label,
			'type'     => 'checkbox',
		) );

	}

	// Archive options.

	$AP_Manager->add_section( 'tux_options_archives', array(
		'title'       => __( 'Archive Pages', 'tuxedo' ),
		'panel'       => 'tux_options_page',
		'tab'         => 'tux_options_tab',
	) );

	$AP_Manager->add_setting( 'tux_options[archives_nav_loc]', array(
		'default'           => 'bottom',
		'sanitize_callback' => 'tux_sanitize_textbox',
		'section'           => 'tux_options_archives',
		'type'              => 'option',
	) );

	$AP_Manager->add_setting( 'tux_options[archives_paginate]', array(
		'default'           => 1,
		'sanitize_callback' => 'tux_sanitize_checkbox',
		'section'           => 'tux_options_archives',
		'type'              => 'option',
	) );

	$AP_Manager->add_setting( 'tux_options[archives_image]', array(
		'default'           => 'none',
		'sanitize_callback' => 'tux_sanitize_textbox',
		'section'           => 'tux_options_archives',
		'type'              => 'option',
	) );

	$AP_Manager->add_setting( 'tux_options[archives_image_size]', array(
		'default'           => 'full',
		'sanitize_callback' => 'tux_sanitize_textbox',
		'section'           => 'tux_options_archives',
		'type'              => 'option',
	) );

	$AP_Manager->add_setting( 'tux_options[archives_more_link]', array(
		'default'           => 1,
		'sanitize_callback' => 'tux_sanitize_checkbox',
		'section'           => 'tux_options_archives',
		'type'              => 'option',
	) );

	$AP_Manager->add_setting( 'tux_options[archives_excerpt]', array(
		'default'           => 0,
		'sanitize_callback' => 'tux_sanitize_checkbox',
		'section'           => 'tux_options_archives',
		'type'              => 'option',
	) );

	$AP_Manager->add_setting( 'tux_options[archives_excerpt_length]', array(
		'default'           => 55,
		'sanitize_callback' => 'tux_sanitize_integer',
		'section'           => 'tux_options_archives',
		'type'              => 'option',
	) );

	$AP_Manager->add_control( 'tux_archive_nav_pos_select', array(
		'settings' => 'tux_options[archives_nav_loc]',
		'section'  => 'tux_options_archives',
		'label'    => __( 'Navigation Location', 'tuxedo' ),
		'type'     => 'select',
		'choices'  => array(
			'top'       => __( 'Top (Above Posts)', 'tuxedo' ),
			'bottom'    => __( 'Bottom (Beneath Posts)', 'tuxedo' ),
			'topbottom' => __( 'Top / Bottom', 'tuxedo' ),
		),
	) );

	$AP_Manager->add_control( 'tux_archives_paginate', array(
		'settings'    => 'tux_options[archives_paginate]',
		'section'     => 'tux_options_archives',
		'label'       => __( 'Paginate Archive Navigation', 'tuxedo' ),
		'description' => __( 'Example: Prev 1 ... 3 4 5 6 7 ... 9 Next', 'tuxedo' ),
		'type'        => 'checkbox',
	) );

	$AP_Manager->add_control( 'tux_archives_image', array(
		'settings' => 'tux_options[archives_image]',
		'section'  => 'tux_options_archives',
		'label'    => __( 'Featured Image', 'tuxedo' ),
		'type'     => 'select',
		'choices'  => array(
			'none'        => __( 'None', 'tuxedo' ),
			'left'        => __( 'Left Aligned', 'tuxedo' ),
			'right'       => __( 'Right Aligned', 'tuxedo' ),
			'center'      => __( 'Centered', 'tuxedo' ),
		),
	) );

	// Get image sizes
	global $_wp_additional_image_sizes;
	$archives_image_sizes = array();
	$get_intermediate_image_sizes = get_intermediate_image_sizes();
	foreach( $get_intermediate_image_sizes as $_size ) $archives_image_sizes[ $_size ] = $_size;
	foreach( $get_intermediate_image_sizes as $_size ) {
		if ( in_array( $_size, array( 'thumbnail', 'medium', 'large' ) ) ) {
			$archives_image_sizes[ $_size ] = $_size . ' (' . get_option( $_size . '_size_w' ) . 'x' . get_option( $_size . '_size_h' ) . ')';
		} elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {
			$archives_image_sizes[ $_size ] = $_size . ' (' . $_wp_additional_image_sizes[ $_size ][ 'width' ] . 'x' . $_wp_additional_image_sizes[ $_size ][ 'height' ] . ')';
		}
	}

	$AP_Manager->add_control( 'tux_archives_image_size', array(
		'settings' => 'tux_options[archives_image_size]',
		'section'  => 'tux_options_archives',
		'label'    => __( 'Featured Image Size', 'tuxedo' ),
		'type'     => 'select',
		'choices'  => array( 'full' => __( 'Full Size', 'tuxedo' ) ) + $archives_image_sizes,
	) );

	$AP_Manager->add_control( 'tux_archives_more_link', array(
		'settings'    => 'tux_options[archives_more_link]',
		'section'     => 'tux_options_archives',
		'label'       => __( 'Continue Reading Link', 'tuxedo' ),
		'description' => __( 'Display continue reading links.', 'tuxedo' ),
		'type'        => 'checkbox',
	) );

	$AP_Manager->add_control( 'tux_archives_excerpt', array(
		'settings'    => 'tux_options[archives_excerpt]',
		'section'     => 'tux_options_archives',
		'label'       => __( 'Excerpt', 'tuxedo' ),
		'description' => __( 'Display the excerpt instead of the content.', 'tuxedo' ),
		'type'        => 'checkbox',
	) );

	$AP_Manager->add_control( 'tux_archives_excerpt_length', array(
		'settings'    => 'tux_options[archives_excerpt_length]',
		'section'     => 'tux_options_archives',
		'label'       => __( 'Excerpt Length', 'tuxedo' ),
		'type'        => 'text',
	) );

	// Header and footer scripts options.

	$AP_Manager->add_section( 'tux_options_scripts', array(
		'title'       => __( 'Header and Footer Scripts', 'tuxedo' ),
		'description' => ( ! current_user_can( 'unfiltered_html' ) ? __( 'Required user capability (unfiltered_html) not met to edit these options.', 'tuxedo' ) : '' ),
		'panel'       => 'tux_options_page',
		'tab'         => 'tux_options_tab',
	) );

	$AP_Manager->add_setting( 'tux_options[scripts_header]', array(
		'default'           => '',
		'sanitize_callback' => '',
		'section'           => 'tux_options_scripts',
		'capability'        => 'unfiltered_html',
		'type'              => 'option',
	) );

	$AP_Manager->add_setting( 'tux_options[scripts_footer]', array(
		'default'           => '',
		'sanitize_callback' => '',
		'section'           => 'tux_options_scripts',
		'capability'        => 'unfiltered_html',
		'type'              => 'option',
	) );

	$AP_Manager->add_control( 'tux_scripts_header', array(
		'settings'    => 'tux_options[scripts_header]',
		'section'     => 'tux_options_scripts',
		'label'       => __( 'Header Scripts', 'tuxedo' ),
		'description' => __( 'Output from the wp_head() function in the &lt;head&gt;&lt;/head&gt; tags.', 'tuxedo' ),
		'type'        => 'textarea',
		'input_attrs' => array( 'style' => 'width:100%;' ),
	) );

	$AP_Manager->add_control( 'tux_scripts_footer', array(
		'settings'    => 'tux_options[scripts_footer]',
		'section'     => 'tux_options_scripts',
		'label'       => __( 'Footer Scripts', 'tuxedo' ),
		'description' => __( 'Output from the wp_footer() function just before the closing &lt;/body&gt; tag.', 'tuxedo' ),
		'type'        => 'textarea',
		'input_attrs' => array( 'style' => 'width:100%;' ),
	) );

	// Bail if the manager isn't an admin page manager.
	if ( ! is_a( $AP_Manager, 'Tux_AdminPage_Manager' ) ) return;

	// About tab.

	// About section, HTML code is in the description.

	$AP_Manager->add_section( 'tux_about', array(
		'panel'       => 'tux_options_page',
		'tab'         => 'tux_about_tab',
		'box'         => false,
		'max_width'   => '80%',
		'title'       => '',
		'description' =>
'</p>
<img src="' . get_template_directory_uri() . '/libtux/images/logo.png" class="alignright">
<h1>Welcome to Theme Tuxedo ' . TUX_VERSION . '</h1>
<h2>Resources</h2>
<p>
<a href="http://themetuxedo.com/" target="_blank">ThemeTuxedo.com</a>
</p>
<h2>Changelog</h2>
<h3>0.0.4</h3>
<ol>
<li>Added admin archive page options for navigation, featured images and excerpts.</li>
<li>Added about page information.</li>
<li>Combined options page and customizer controls into one function.</li>
</ol>
<h3>0.0.3</h3>
<ol>
<li>Added admin options for layouts and classes.</li>
<li>Admin options function, adding necessary classes per options.</li>
<li>Added admin breadcrumb options. Breadcrumbs show only on selected pages.</li>
<li>Added options database updating.</li>
<li>Changed breadcrumb output to inline.</li>
<li>Changed post header and footer meta to shortcodes instead of function calls.</li>
<li>Added more inline documentation for header and footer meta functions.</li>
<li>Added sidebar widget areas.</li>
</ol>
<h3>0.0.2</h3>
<ol>
<li>Fixed admin option pages not showing.</li>
<li>Finished 404 template.</li>
<li>Finished search form template.</li>
<li>Finished archive template.</li>
<li>Checked to make sure we pass HTML5 validation, we do.</li>
</ol>
<h3>0.0.1</h3>
<ol>
<li>Initial alpha release.</li>
</ol>
<p>',
	) );

	// Help tabs.

	$AP_Manager->add_help_tab( 'tux_theme_layout_help', array(
		'panel'   => 'tux_options_page',
		'tab'     => 'tux_options_tab',
		'title'   => __( 'Theme Layout', 'tuxedo' ),
		'content' => __( '<p>Choose from an array of layout options per post, or set the default layout in admin settings or the Customizer.</p>', 'tuxedo' ),
	) );

}
add_action( 'tux_adminpage_register', 'tux_options_admin' );
add_action( 'customize_register', 'tux_options_admin' );

