<?php
/**
 * Post options
 *
 * @package ThemeTuxedo\Options
 * @since 1.0.0
 */

/**
 * Layout metabox for posts.
 *
 * Attached to the "tux_metabox_register" action.
 *
 * @since 1.0.0
 *
 * @param Tux_Metabox_Manager $MB_Manager Metabox manager.
 */
function tux_metabox_layout( $MB_Manager ) {

	if ( ! post_type_supports( $MB_Manager->post_type, 'tux-layouts' ) ) return;

	// Panels.

	$MB_Manager->add_panel( 'tux_theme_layout', array(
		'title'   => __( 'Theme Layout', 'tuxedo' ),
		'context' => 'side',
	) );

	// Help tabs.

	$MB_Manager->add_help_tab( 'tux_theme_layout_help', array(
		'title'   => __( 'Theme Layout', 'tuxedo' ),
		'content' => __( '<p>Choose from an array of layout options per post, or set the default layout in Appearance -> Theme Tuxedo -> Options or the Customizer.</p><p>Body and post classes should be space separated.</p>', 'tuxedo' ),
	) );

	// Sections.

	$MB_Manager->add_section( 'tux_theme_layout_section', array(
		'title'       => '',
		'description' => '',
		'panel'       => 'tux_theme_layout',
	) );

	// Settings.

	$MB_Manager->add_setting( '_tux_layout[layout]', array(
		'default'           => 'default',
		'sanitize_callback' => 'tux_sanitize_textbox',
	) );

	$MB_Manager->add_setting( '_tux_layout[body_class]', array(
		'default'           => '',
		'sanitize_callback' => 'tux_sanitize_textbox',
	) );

	$MB_Manager->add_setting( '_tux_layout[post_class]', array(
		'default'           => '',
		'sanitize_callback' => 'tux_sanitize_textbox',
	) );

	// Controls.

	$MB_Manager->add_control( 'tux_theme_layout_select', array(
		'settings' => '_tux_layout[layout]',
		'section'  => 'tux_theme_layout_section',
		'label'    => __( 'Layout', 'tuxedo' ),
		'type'     => 'select',
		'choices'  => array( 'default' => __( 'Default', 'tuxedo' ) ) + tux_layout_get_registered(),
	) );

	$MB_Manager->add_control( 'tux_theme_layout_body_class', array(
		'settings'    => '_tux_layout[body_class]',
		'section'     => 'tux_theme_layout_section',
		'label'       => __( 'Custom Body Classes', 'tuxedo' ),
		'type'        => 'text',
		'input_attrs' => array( 'style' => 'width:100%;' ),
	) );

	$MB_Manager->add_control( 'tux_theme_layout_post_class', array(
		'settings'    => '_tux_layout[post_class]',
		'section'     => 'tux_theme_layout_section',
		'label'       => __( 'Custom Post Classes', 'tuxedo' ),
		'type'        => 'text',
		'input_attrs' => array( 'style' => 'width:100%;' ),
	) );

}
add_action( 'tux_metabox_register', 'tux_metabox_layout' );

