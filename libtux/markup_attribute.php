<?php
/**
 * Markup attribute output
 *
 * @package ThemeTuxedo\Template
 * @since 1.0.0
 */

/**
 * Filter body classes to add layout and custom classes from admin settings.
 *
 * Attached to the "body_class" filter.
 *
 * @since 1.0.0
 */
function tux_body_class( $classes ) {

	$tux_options = get_option( 'tux_options' );

	if ( ! is_user_logged_in() )
		$classes[] = 'logged-out';

	if ( isset( $tux_options[ 'body_class' ] ) && ! empty( $tux_options[ 'body_class' ] ) )
		$classes[] = esc_attr( $tux_options[ 'body_class' ] );

	if ( is_singular() ) {
		$tux_options = get_post_meta( get_the_ID(), '_tux_layout', true );
		if ( isset( $tux_options[ 'body_class' ] ) && ! empty( $tux_options[ 'body_class' ] ) )
			$classes[] = esc_attr( $tux_options[ 'body_class' ] );
	}

	if ( $current_layout = tux_layout_get_current() )
		$classes[] = $current_layout;

	return $classes;

}
add_filter( 'body_class', 'tux_body_class' );

/**
 * Filter post classes to change "hentry" to "entry" and add
 * custom classes from admin settings.
 *
 * Attached to the "post_class" filter.
 *
 * @since 1.0.0
 */
function tux_post_class( $classes ) {

	$classes[] = 'entry';

	$tux_options = get_option( 'tux_options' );

	if ( isset( $tux_options[ 'post_class' ] ) && ! empty( $tux_options[ 'post_class' ] ) )
		$classes[] = esc_attr( $tux_options[ 'post_class' ] );

	if ( is_singular() ) {
		$tux_options = get_post_meta( get_the_ID(), '_tux_layout', true );
		if ( isset( $tux_options[ 'post_class' ] ) && ! empty( $tux_options[ 'post_class' ] ) )
			$classes[] = esc_attr( $tux_options[ 'post_class' ] );
	}

	return array_diff( $classes, array( 'hentry' ) );

}
add_filter( 'post_class', 'tux_post_class' );

/**
 * Filter and echo an attribute context.
 *
 * @since 1.0.0
 *
 * @param string  $context A specific context.
 */
function tux_attr( $context ) {

	echo tux_get_attr( $context );

}

/**
 * Filter and return an attribute context.
 *
 * @since 1.0.0
 *
 * @param string  $context A specific context.
 * @param boolean $ret_array Optional. True to return an array.
 * @return array|string    Array of keys as attribute names and values as attribute values,
 *                         or a formatted attribute string.
 */
function tux_get_attr( $context, $ret_array = false ) {

	/**
	 * Filters attributes for a specific context.
	 *
	 * @since 1.0.0
	 *
	 * @param array $attrs {
	 *		Empty array, should be filled with array keys being attribute names
	 *		and array values being attribute values.
	 * }
	 */
	$attrs = apply_filters( "tux_attr_{$context}", array( 'class' => sanitize_html_class( $context ) ), $context );

	if ( $ret_array ) return $attrs;

	$attrs_output = '';
	foreach( $attrs as $name => $value )
		$attrs_output .= ( ( ! empty( $value ) ) ? sprintf( ' %s="%s"', esc_html( $name ), esc_attr( $value ) ) : esc_html( ' ' . $name ) );

	/**
	 * Filters attribute output for a specific context.
	 *
	 * @since 1.0.0
	 *
	 * @param string $attrs_output Formatted attributes output string.
	 * @param array  $attrs {
	 *		Raw attributes array with array keys being attribute names
	 *		and array values being attribute values.
	 * }
	 */
	$attrs_output = apply_filters( "tux_attr_{$context}_output", $attrs_output, $attrs, $context );

	return $attrs_output;

}

/**
 * Markup attributes for the "body" tag.
 *
 * Attached to the "tux_attr_body" filter.
 *
 * @since 1.0.0
 *
 * @return array Amended attributes.
 */
function tux_attributes_body( $attrs ) {

	$attrs[ 'class' ]     = join( ' ', get_body_class() );
	$attrs[ 'dir' ]       = is_rtl() ? 'rtl' : 'ltr';
	$attrs[ 'itemscope' ] = '';
	$attrs[ 'itemtype' ]  = 'http://schema.org/WebPage';

	if ( is_search() )
		$attrs[ 'itemtype' ]  = 'http://schema.org/SearchResultsPage';

	return $attrs;

}
add_filter( 'tux_attr_body', 'tux_attributes_body' );

/**
 * Markup attributes for the site "header" tag.
 *
 * Attached to the "tux_attr_site-header" filter.
 *
 * @since 1.0.0
 *
 * @return array Amended attributes.
 */
function tux_attributes_site_header( $attrs ) {

	$attrs[ 'role' ]      = 'banner';
	$attrs[ 'itemscope' ] = '';
	$attrs[ 'itemtype' ]  = 'http://schema.org/WPHeader';

	return $attrs;

}
add_filter( 'tux_attr_site-header', 'tux_attributes_site_header' );

/**
 * Markup attributes for the site title element.
 *
 * Attached to the "tux_attr_site-title" filter.
 *
 * @since 1.0.0
 *
 * @return array Amended attributes.
 */
function tux_attributes_site_title( $attrs ) {

	$attrs[ 'itemprop' ] = 'headline';

	return $attrs;

}
add_filter( 'tux_attr_site-title', 'tux_attributes_site_title' );

/**
 * Markup attributes for the site description element.
 *
 * Attached to the "tux_attr_site-description" filter.
 *
 * @since 1.0.0
 *
 * @return array Amended attributes.
 */
function tux_attributes_site_description( $attrs ) {

	$attrs[ 'itemprop' ] = 'description';

	return $attrs;

}
add_filter( 'tux_attr_site-description', 'tux_attributes_site_description' );

/**
 * Markup attributes for the header widget area.
 *
 * Attached to the 'tux_attr_header-widget-area" filter.
 *
 * @since 1.0.0
 *
 * @return array Amended attributes.
 */
function tux_attributes_header_widget_area( $attrs ) {

	$attrs[ 'class' ] = 'widget-area ' . $attrs[ 'class' ];
	$attrs[ 'role' ]  = 'complementary';

	return $attrs;

}
add_filter( 'tux_attr_header-widget-area', 'tux_attributes_header_widget_area' );

/**
 * Markup attributes for the navigation menus.
 *
 * Attached to the "tux_attr_nav-primary", "tux_attr_nav-secondary", "tux_attr_nav-footer" filters.
 *
 * @since 1.0.0
 *
 * @return array Amended attributes.
 */
function tux_attributes_nav_menu( $attrs, $context ) {

	switch ( $context ) {
		case 'nav-primary':
			$attrs[ 'aria-label' ] = __( 'Primary Navigation', 'tuxedo' );
			$attrs[ 'id' ] = 'tux-nav-primary';
			break;
		case 'nav-secondary':
			$attrs[ 'aria-label' ] = __( 'Secondary Navigation', 'tuxedo' );
			$attrs[ 'id' ] = 'tux-nav-secondary';
			break;
		case 'nav-footer':
			$attrs[ 'aria-label' ] = __( 'Footer Navigation', 'tuxedo' );
			$attrs[ 'id' ] = 'tux-nav-footer';
			break;
	}
	$attrs[ 'class' ]     = 'tux-nav ' . $attrs[ 'class' ];
	$attrs[ 'role' ]      = 'navigation';
	$attrs[ 'itemscope' ] = '';
	$attrs[ 'itemtype' ]  = 'http://schema.org/SiteNavigationElement';

	return $attrs;

}
add_filter( 'tux_attr_nav-primary', 'tux_attributes_nav_menu', 10, 2 );
add_filter( 'tux_attr_nav-secondary', 'tux_attributes_nav_menu', 10, 2 );
add_filter( 'tux_attr_nav-footer', 'tux_attributes_nav_menu', 10, 2 );
add_filter( 'tux_attr_nav-widget', 'tux_attributes_nav_menu', 10, 2 );

/**
 * Markup attributes for the menu item name element.
 *
 * Attached to the "tux_attr_menu-item-title" filter.
 *
 * @since 1.0.0
 *
 * @return array Amended attributes.
 */
function tux_attributes_menu_item_title( $attrs ) {

	unset( $attrs[ 'class' ] );
	$attrs[ 'itemprop' ] = 'name';

	return $attrs;

}
add_filter( 'tux_attr_menu-item-title', 'tux_attributes_menu_item_title' );

/**
 * Markup attributers for the menu item link "a" tag.
 *
 * Attached to the "tux_attr_menu-item-link" filter.
 *
 * @since 1.0.0
 *
 * @return array Amended attributes.
 */
function tux_attributes_menu_item_link( $attrs ) {

	unset( $attrs[ 'class' ] );
	$attrs[ 'itemprop' ] = 'url';

	return $attrs;

}
add_filter( 'tux_attr_menu-item-link', 'tux_attributes_menu_item_link' );

/**
 * Markup attributes for the breadcrumb element.
 *
 * Attached to the "tux_attr_breadcrumb" filter.
 *
 * @since 1.0.0
 *
 * @return array Amended attributes.
 */
function tux_attributes_breadcrumb( $attrs ) {

	$attrs[ 'itemprop' ]  = 'breadcrumb';

	return $attrs;

}
add_filter( 'tux_attr_breadcrumb', 'tux_attributes_breadcrumb' );

/**
 * Markup attributes for the breadcrumb list element.
 *
 * Attached to the "tux_attr_breadcrumb-list" filter.
 *
 * @since 1.0.0
 *
 * @return array Amended attributes.
 */
function tux_attributes_breadcrumb_list( $attrs ) {

	$attrs[ 'itemscope' ] = '';
	$attrs[ 'itemtype' ]  = 'http://schema.org/BreadcrumbList';

	return $attrs;

}
add_filter( 'tux_attr_breadcrumb-list', 'tux_attributes_breadcrumb_list' );

/**
 * Markup attributes for the breadcrumb list item element.
 *
 * Attached to the "tux_attr_breadcrumb-list-item" filter.
 *
 * @since 1.0.0
 *
 * @return array Amended attributes.
 */
function tux_attributes_breadcrumb_list_item( $attrs ) {

	$attrs[ 'itemscope' ] = '';
	$attrs[ 'itemprop' ]  = 'itemListElement';
	$attrs[ 'itemtype' ]  = 'http://schema.org/ListItem';

	return $attrs;

}
add_filter( 'tux_attr_breadcrumb-list-item', 'tux_attributes_breadcrumb_list_item' );

/**
 * Markup attributes for the breacrumb link "a" tag.
 *
 * Attached to the "tux_attr_breadcrumb-link" filter.
 *
 * @since 1.0.0
 *
 * @return array Amended attributes.
 */
function tux_attributes_breadcrumb_link( $attrs ) {

	$attrs[ 'itemprop' ] = 'item';

	return $attrs;

}
add_filter( 'tux_attr_breadcrumb-link', 'tux_attributes_breadcrumb_link' );

/**
 * Markup attributes for the breadcrumb name element.
 *
 * Attached to the "tux_attr_breadcrumb-title" filter.
 *
 * @since 1.0.0
 *
 * @return array Amended attributes.
 */
function tux_attributes_breadcrumb_title( $attrs ) {

	$attrs[ 'itemprop' ] = 'name';

	return $attrs;

}
add_filter( 'tux_attr_breadcrumb-title', 'tux_attributes_breadcrumb_title' );

/**
 * Markup attributes for the post "article" tag.
 *
 * Attached to the "tux_attr_post" filter.
 *
 * @since 1.0.0
 *
 * @return array Amended attributes.
 */
function tux_attributes_post( $attrs ) {

	$attrs[ 'id' ]        = 'post-' . get_the_ID();
	$attrs[ 'class' ]     = join( ' ', get_post_class() );
	$attrs[ 'itemscope' ] = '';
	$attrs[ 'itemtype' ]  = 'http://schema.org/CreativeWork';

	if ( get_post_type() === 'post' ) {

		$post_loop_attrs = tux_attributes_post_loop( array() );
		if ( isset( $post_loop_attrs[ 'itemtype' ] ) && $post_loop_attrs[ 'itemtype' ] === 'http://schema.org/Blog' )
			$attrs[ 'itemprop' ] = 'blogPost';

		$attrs[ 'itemtype' ] = 'http://schema.org/BlogPosting';

	} elseif ( get_post_type() === 'attachment' && wp_attachment_is_image() ) {

		$attrs[ 'itemtype' ] = 'http://schema.org/ImageObject';

	}

	return $attrs;

}
add_filter( 'tux_attr_post', 'tux_attributes_post' );

/**
 * Markup attributes for the content "main" tag.
 *
 * Attached to the "tux_attr_content" filter.
 *
 * @since 1.0.0
 *
 * @return array Amended attributes.
 */
function tux_attributes_content( $attrs ) {

	$attrs[ 'id' ]       = 'tux-content';
	$attrs[ 'role' ]     = 'main';
	$attrs[ 'itemprop' ] = 'mainContentOfPage';

	return $attrs;

}
add_filter( 'tux_attr_content', 'tux_attributes_content' );

/**
 * Markup attributes for the post loop element.
 *
 * Attached to the "tux_attr_post-loop" filter.
 *
 * @since 1.0.0
 *
 * @return array Amended attributes.
 */
function tux_attributes_post_loop( $attrs ) {

	if ( is_singular( 'post' ) || is_home() || is_post_type_archive( 'post' ) ) {
		$attrs[ 'itemscope' ] = '';
		$attrs[ 'itemtype' ]  = 'http://schema.org/Blog';
	}

	return $attrs;

}
add_filter( 'tux_attr_post-loop', 'tux_attributes_post_loop' );

/**
 * Markup attributes for the post title element.
 *
 * Attached to the "tux_attr_entry-title" filter.
 *
 * @since 1.0.0
 *
 * @return array Amended attributes.
 */
function tux_attributes_entry_title( $attrs ) {

	$attrs[ 'itemprop' ] = 'headline';

	return $attrs;

}
add_filter( 'tux_attr_entry-title', 'tux_attributes_entry_title' );

/**
 * Markup attributes for the post content element.
 *
 * Attached to the "tux_attr_entry-content" filter.
 *
 * @since 1.0.0
 *
 * @return array Amended attributes.
 */
function tux_attributes_entry_content( $attrs ) {

	$attrs[ 'itemprop' ] = 'text';

	if ( get_post_type() === 'post' ) {

		$attrs[ 'itemprop' ] = 'articleBody';

	}

	return $attrs;

}
add_filter( 'tux_attr_entry-content', 'tux_attributes_entry_content' );

/**
 * Markup attributes for the post meta element.
 *
 * Attached to the "tux_attr_entry-meta-before-content" and
 * "tux_attr_entry-meta-after-content" filters.
 *
 * @since 1.0.0
 *
 * @return array Amended attributes.
 */
function tux_attributes_entry_meta( $attrs ) {

	$attrs[ 'class' ] = 'entry-meta';

	return $attrs;

}
add_filter( 'tux_attr_entry-meta-before-content', 'tux_attributes_entry_meta' );
add_filter( 'tux_attr_entry-meta-after-content', 'tux_attributes_entry_meta' );

/**
 * Markup attributes for the author meta element.
 *
 * Attached to the "tux_attr_entry-author" filter.
 *
 * @since 1.0.0
 *
 * @return array Amended attributes.
 */
function tux_attributes_entry_author( $attrs ) {

	$attrs[ 'itemprop' ]  = 'author';
	$attrs[ 'itemscope' ] = '';
	$attrs[ 'itemtype' ]  = 'http://schema.org/Person';

	return $attrs;

}
add_filter( 'tux_attr_entry-author', 'tux_attributes_entry_author' );

/**
 * Markup attributes for author link meta "a" tag.
 *
 * Attached to the "tux_attr_entry-author-link" filter.
 *
 * @since 1.0.0
 *
 * @return array Amended attributes.
 */
function tux_attributes_entry_author_link( $attrs ) {

	$attrs[ 'itemprop' ] = 'url';
	$attrs[ 'rel' ]      = 'author';

	return $attrs;

}
add_filter( 'tux_attr_entry-author-link', 'tux_attributes_entry_author_link' );

/**
 * Markup attributes for the author name meta element.
 *
 * Attached to the "tux_attr_entry-author-name" filter.
 *
 * @since 1.0.0
 *
 * @return array Amended attributes.
 */
function tux_attributes_entry_author_name( $attrs ) {

	$attrs[ 'itemprop' ] = 'name';

	return $attrs;

}
add_filter( 'tux_attr_entry-author-name', 'tux_attributes_entry_author_name' );

/**
 * Markup attributes for the published date/time meta "time" tag.
 *
 * Attached to the "tux_attr_entry-time" filter.
 *
 * @since 1.0.0
 *
 * @return array Amended attributes.
 */
function tux_attributes_entry_time( $attrs ) {

	$attrs[ 'itemprop' ] = 'datePublished';
	$attrs[ 'datetime' ] = get_the_time( 'c' );

	return $attrs;

}
add_filter( 'tux_attr_entry-time', 'tux_attributes_entry_time' );

/**
 * Markup attributes for the modified date/time meta "time" tag.
 *
 * Attached to the "tux_attr_entry-modified-time" filter.
 *
 * @since 1.0.0
 *
 * @return array Amended attributes.
 */
function tux_attributes_entry_modified_time( $attrs ) {

	$attrs[ 'itemprop' ] = 'dateModified';
	$attrs[ 'datetime' ] = get_the_modified_time( 'c' );

	return $attrs;

}
add_filter( 'tux_attr_entry-modified-time', 'tux_attributes_entry_modified_time' );

/**
 * Markup attributes for the sidebar "aside" tag.
 *
 * Attached to the "tux_attr_sidebar-primary", "tux_attr_sidebar-secondary"
 * and "tux_attr_sidebar-footer" filter.
 *
 * @since 1.0.0
 *
 * @return array Amended attributes.
 */
function tux_attributes_sidebar( $attrs, $context ) {

	$attrs[ 'class' ]     = 'widget-area sidebar ' . $attrs[ 'class' ];
	$attrs[ 'role' ]      = 'complementary';
	$attrs[ 'itemscope' ] = '';
	$attrs[ 'itemtype' ]  = 'http://schema.org/WPSideBar';
	switch ( $context ) {
		case 'sidebar-primary':
			$attrs[ 'aria-label' ] = __( 'Primary Sidebar', 'tuxedo' );
			break;
		case 'sidebar-secondary':
			$attrs[ 'aria-label' ] = __( 'Secondary Sidebar', 'tuxedo' );
			break;
		case 'sidebar-footer':
			unset( $attrs[ 'itemscope' ] );
			unset( $attrs[ 'itemtype' ] );
			break;
	}

	return $attrs;

}
add_filter( 'tux_attr_sidebar-primary', 'tux_attributes_sidebar', 10, 2 );
add_filter( 'tux_attr_sidebar-secondary', 'tux_attributes_sidebar', 10, 2 );
add_filter( 'tux_attr_sidebar-footer', 'tux_attributes_sidebar', 10, 2 );

/**
 * Markup attributes for the entry comments meta element.
 *
 * Attached to the "tux_attr_entry-comments" filter.
 *
 * @since 1.0.0
 *
 * @return array Amended attributes.
 */
function tux_attributes_entry_comments( $attrs ) {

	$attrs[ 'id' ] = 'comments';

	return $attrs;

}
add_Filter( 'tux_attr_entry-comments', 'tux_attributes_entry_comments' );

/**
 * Markup attributes for the comment "li" tag.
 *
 * Attached to the "tux_attr_comment-li" filter.
 *
 * @since 1.0.0
 *
 * @return array Amended attributes.
 */
function tux_attributes_comment_li( $attrs ) {

	$attrs[ 'class' ] = join( ' ', get_comment_class() );
	$attrs[ 'id' ] = 'comment-' . get_comment_ID();
	$attrs[ 'itemprop' ]  = 'comment';

	return $attrs;

}
add_filter( 'tux_attr_comment-li', 'tux_attributes_comment_li' );

/**
 * Markup attributes for the comment "article" tag.
 *
 * Attached to the "tux_attr_comment" filter.
 *
 * @since 1.0.0
 *
 * @return array Amended attributes.
 */
function tux_attributes_comment( $attrs ) {

	unset( $attrs[ 'class' ] );
	$attrs[ 'itemscope' ] = '';
	$attrs[ 'itemtype' ]  = 'http://schema.org/UserComments';

	return $attrs;

}
add_filter( 'tux_attr_comment', 'tux_attributes_comment' );

/**
 * Markup attributes for the comment author element.
 *
 * Attached to the "tux_attr_comment-author" filter.
 *
 * @since 1.0.0
 *
 * @return array Amended attributes.
 */
function tux_attributes_comment_author( $attrs ) {

	$attrs[ 'itemprop' ]  = 'creator';
	$attrs[ 'itemscope' ] = '';
	$attrs[ 'itemtype' ]  = 'http://schema.org/Person';

	return $attrs;

}
add_filter( 'tux_attr_comment-author', 'tux_attributes_comment_author' );

/**
 * Markup attributes for the comment author title element.
 *
 * Attached to the "tux_attr_comment-author-title" filter.
 *
 * @since 1.0.0
 *
 * @return array Amended attributes.
 */
function tux_attributes_comment_author_title( $attrs ) {

	unset( $attrs[ 'class' ] );
	$attrs[ 'itemprop' ] = 'name';

	return $attrs;

}
add_filter( 'tux_attr_comment-author-title', 'tux_attributes_comment_author_title' );

/**
 * Markup attributes for the comment author link "a" tag.
 *
 * Attached to the "tux_attr_comment-author-link" filter.
 *
 * @since 1.0.0
 *
 * @return array Amended attributes.
 */
function tux_attributes_comment_author_link( $attrs ) {

	$attrs[ 'rel' ]      = 'external nofollow';
	$attrs[ 'itemprop' ] = 'url';

	return $attrs;

}
add_filter( 'tux_attr_comment-author-link', 'tux_attributes_comment_author_link' );

/**
 * Markup attributes for the comment author says element.
 *
 * Attached to the "tux_attr_comment-author-says" filter.
 *
 * @since 1.0.0
 *
 * @return array Amended attributes.
 */
function tux_attributes_comment_author_says( $attrs ) {

	$attrs[ 'class' ] = 'says';

	return $attrs;

}
add_filter( 'tux_attr_comment-author-says', 'tux_attributes_comment_author_says' );

/**
 * Markup attributes for the comment time "time" tag.
 *
 * Attached to the "tux_attr_comment-time" filter.
 *
 * @since 1.0.0
 *
 * @return array Amended attributes.
 */
function tux_attributes_comment_time( $attrs ) {

	$attrs[ 'datetime' ] = get_comment_time( 'c' );
	$attrs[ 'itemprop' ] = 'commentTime';

	return $attrs;

}
add_filter( 'tux_attr_comment-time', 'tux_attributes_comment_time' );

/**
 * Markup attributes for the comment time link "a" tag.
 *
 * Attached to the "tux_attre_comment-time-link" filter.
 *
 * @since 1.0.0
 *
 * @return array Amended attributes.
 */
function tux_attributes_comment_time_link( $attrs ) {

	$attrs[ 'itemprop' ] = 'url';

	return $attrs;

}
add_filter( 'tux_attr_comment-time-link', 'tux_attributes_comment_time_link' );

/**
 * Markup attributes for the comment content element.
 *
 * Attached to the "tux_attr_comment-content" filter.
 *
 * @since 1.0.0
 *
 * @return array Amended attributes.
 */
function tux_attributes_comment_content( $attrs ) {

	$attrs[ 'itemprop' ] = 'commentText';

	return $attrs;

}
add_filter( 'tux_attr_comment-content', 'tux_attributes_comment_content' );

/**
 * Markup attributes for the comment pagination previous element.
 *
 * Attached to the "tux_attr_comment-pagination-previous" filter.
 *
 * @since 1.0.0
 *
 * @return array Amended attributes.
 */
function tux_attributes_comment_pagination_previous( $attrs ) {

	$attrs[ 'class' ] = 'pagination-previous alignleft';

	return $attrs;

}
add_filter( 'tux_attr_comment-pagination-previous', 'tux_attributes_comment_pagination_previous' );

/**
 * Markup attributes for the comment pagination next element.
 *
 * Attached to the "tux_attr_comment-pagination-next" filter.
 *
 * @since 1.0.0
 *
 * @return array Amended attributes.
 */
function tux_attributes_comment_pagination_next( $attrs ) {

	$attrs[ 'class' ] = 'pagination-next alignright';

	return $attrs;

}
add_filter( 'tux_attr_comment-pagination-next', 'tux_attributes_comment_pagination_next' );

/**
 * Markup attributes for the site "footer" tag.
 *
 * Attached to the "tux_attr_site-footer" filter.
 *
 * @since 1.0.0
 *
 * @return array Amended attributes.
 */
function tux_attributes_site_footer( $attrs ) {

	$attrs[ 'role' ]      = 'contentinfo';
	$attrs[ 'itemscope' ] = '';
	$attrs[ 'itemtype' ]  = 'http://schema.org/WPFooter';

	return $attrs;

}
add_filter( 'tux_attr_site-footer', 'tux_attributes_site_footer' );

/**
 * Markup attributes for the search "form" tag.
 *
 * Attached to the "tux_attr_search-form" filter.
 *
 * @since 1.0.0
 *
 * @return array Amended attributes.
 */
function tux_attributes_search_form( $attrs ) {

	$attrs[ 'itemprop' ]  = 'potentialAction';
	$attrs[ 'itemscope' ] = 'itemscope';
	$attrs[ 'itemtype' ]  = 'http://schema.org/SearchAction';
	$attrs[ 'method' ]    = 'get';
	$attrs[ 'action' ]    = home_url( '/' );
	$attrs[ 'role' ]      = 'search';

	return $attrs;

}
add_filter( 'tux_attr_search-form', 'tux_attributes_search_form' );

