<?php
/**
 * Breadcrumb output templates
 *
 * @package ThemeTuxedo\Template
 * @since 1.0.0
 */

/**
 * Output breadcrumbs.
 *
 * Attached to the "tux_loop_before" action.
 *
 * @since 1.0.0
 *
 * @params array $args {
 *     Optional. An array of arguments.
 *
 *     @type string $sep    Separator between breadcrumbs. Default " / ".
 *     @type string $before HTML markup before the breadcrumb list. Default "<div class="breadcrumbs">".
 *     @type string $after  HTML markup after the breadcrumb list. Default "</div>".
 *     @type array  $labels {
 *         An array of labels.
 *
 *         @type string $home      Home page label. Default "Home".
 *         @type string $prefix    Breadcrumb list prefix. Default "You are here: ".
 *         @type string $author    Author archive label. Default "Archices for ".
 *         @type string $category  Category archive label. Default "Archives for ".
 *         @type string $tag       Tag archive label. Default "Archives for ".
 *         @type string $date      Date archive label. Default "Archives for ".
 *         @type string $search    Search page label. Default "Search for ".
 *         @type string $taxonomy  Taxonomy archive label. Default "Archives for ".
 *         @type string $post_type Custom post type archive label. Default "Archives for ".
 *         @type string $404       404 not found page label. Default "Not found: ".
 *     }
 * }
 */
function tux_breadcrumbs_output( $args = array() ) {

	// Only show breadcrumbs on select pages.
	$tux_options = get_option( 'tux_options' );
	if ( ! $tux_options[ 'breadcrumbs_homepage' ] && is_front_page() ) return;
	if ( ! $tux_options[ 'breadcrumbs_bloghomepage' ] && is_home() && get_option( 'show_on_front' ) == 'page' && get_option( 'page_for_posts' ) ) return;
	if ( ! $tux_options[ 'breadcrumbs_posts' ] && is_single() ) return;
	if ( ! $tux_options[ 'breadcrumbs_pages' ] && is_page() ) return;
	if ( ! $tux_options[ 'breadcrumbs_archives' ] && ( is_archive() || is_search() ) ) return;
	if ( ! $tux_options[ 'breadcrumbs_notfound' ] && is_404() ) return;
	if ( ! $tux_options[ 'breadcrumbs_attachments' ] && is_attachment() ) return;

	// Support for breadcrumb plugin overrides.

	// Breadcrumb NavXT: https://wordpress.org/plugins/breadcrumb-navxt/
	if ( function_exists( 'bcn_display' ) ) {

		echo '<div' . tux_get_attr( 'breadcrumb' ) . '>';
		bcn_display();
		echo '</div>';
		return;

	}

	// WordPress SEO by Yoast: https://wordpress.org/plugins/wordpress-seo/
	// Yoast Breadcrumbs: https://wordpress.org/plugins/breadcrumbs/
	if ( function_exists( 'yoast_breadcrumb' ) ) {

		if ( class_exists( 'WPSEO_Breadcrumbs' ) ) {

			$wpseo_crumbs = get_option( 'wpseo_internallinks' );
			if ( isset( $wpseo_crumbs[ 'breadcrumbs-enable' ] ) && $wpseo_crumbs[ 'breadcrumbs-enable' ] == true ) {

				yoast_breadcrumb( '<div' . tux_get_attr( 'breadcrumb' ) . '>', '</div>' );
				return;

			}

		} else {

			yoast_breadcrumb( '<div' . tux_get_attr( 'breadcrumb' ) . '>', '</div>' );
			return;

		}

	}

	if ( function_exists( 'breadcrumbs' ) ) {

		breadcrumbs();
		return;

	}

	if ( function_exists( 'crumbs' ) ) {

		crumbs();
		return;

	}

	/**
	 * Filter breadcrumb arguments.
	 *
	 * @since 1.0.0
	 */
	$args = apply_filters( 'tux_breadcrumbs_args', wp_parse_args( $args, array(
		'sep'              => ' / ',
		'post_cat_parents' => true,
		'before'           => '<div' . tux_get_attr( 'breadcrumb' ) . '>',
		'after'            => '</div>',
		'labels'           => array(
			'home'      => __( 'Home', 'tuxedo' ),
			'prefix'    => __( 'You are here: ', 'tuxedo' ),
			'author'    => __( 'Archives for ', 'tuxedo' ),
			'category'  => __( 'Archives for ', 'tuxedo' ),
			'tag'       => __( 'Archives for ', 'tuxedo' ),
			'date'      => __( 'Archives for ', 'tuxedo' ),
			'search'    => __( 'Search for ', 'tuxedo' ),
			'taxonomy'  => __( 'Archives for ', 'tuxedo' ),
			'post_type' => __( 'Archives for ', 'tuxedo' ),
			'404'       => __( 'Page not found: ', 'tuxedo' ),
		)
	) ) );

	// WooCommerce breadcrumbs.
	if ( function_exists( 'woocommerce_breadcrumb' ) ) {

		if ( is_woocommerce() || is_shop() || is_cart() || is_checkout() ) {

			woocommerce_breadcrumb( array(
				'delimiter'   => $args[ 'sep' ],
				'wrap_before' => '<nav class="woocommerce-breadcrumb breadcrumb" ' . ( is_single() ? 'itemprop="breadcrumb"' : '' ) . '>' . $args[ 'labels' ][ 'prefix' ],
				'wrap_after'  => '</nav>',
				'home'        => $args[ 'labels' ][ 'home' ],
			) );
			return;

		}

	}

	// Override breadcrumbs

	global $tux_breadcrumbs_raw;

	if ( empty( $tux_breadcrumbs_raw ) ) tux_breadcrumbs_generate( $args );
	if ( empty( $tux_breadcrumbs_raw ) ) return;

	echo $args[ 'before' ] . $args[ 'labels' ][ 'prefix' ] . '<span' . tux_get_attr( 'breadcrumb-list' ) . '>';

	$tot_breadcrumbs = count( $tux_breadcrumbs_raw );
	$cur_breadcrumb = 0;

	foreach( $tux_breadcrumbs_raw as $raw_crumb ) {

		$cur_breadcrumb++;
		$link = '<span' . tux_get_attr( 'breadcrumb-list-item' ) . '>';

		if ( $raw_crumb[ 'url' ] ) $link .= '<a' . tux_get_attr( 'breadcrumb-link' ) . ' href="' . esc_url( $raw_crumb[ 'url' ] ) . '" title="' . __( 'View ', 'tuxedo' ) . esc_attr( strip_tags( $raw_crumb[ 'title' ] ) ) . '">';

		$link .= '<span' . tux_get_attr( 'breadcrumb-title' ) . '>' . esc_html( strip_tags( $raw_crumb[ 'title' ] ) ) . '</span>';

		if ( $raw_crumb[ 'url' ] ) $link .= '</a>';

		$link .= '<meta itemprop="position" content="' . $cur_breadcrumb . '">';

		if ( $cur_breadcrumb < $tot_breadcrumbs ) $link .= $args[ 'sep' ];

		$link .= '</span>';

		/**
		 * Filters breadcrumb links just before output.
		 *
		 * @since 1.0.0
		 *
		 * @param string $link HTML link with anchor text (escaped).
		 * @param array  $raw_crumb {
		 *     @type string $title Anchor text (not escaped).
		 *     @type string $url	Link url (not escaped).
		 * }
		 * @param array  $args Arguments passed to "tux_breadcrumbs_output".
		 */
		echo apply_filters( 'tux_breadcrumbs_link', $link, $raw_crumb, $args );

	}

	echo '</span>' . $args[ 'after' ];

}
add_action( 'tux_loop_before', 'tux_breadcrumbs_output' );

/**
 * Generate breadcrumbs for current page.
 *
 * Creates a global array "tux_breadcrumbs_raw" containing arrays of urls and titles.
 *
 * @since 1.0.0
 *
 * @param array $args Same as "tux_breadcrumbs_ouput" $args parameter.
 */
function tux_breadcrumbs_generate( $args = array() ) {

	global $tux_breadcrumbs_raw;
	$tux_breadcrumbs_raw = array();

	// Current page is the front page of the site.
	if ( is_front_page() ) {

		/**
		 * Filters breadcrumb home link.
		 *
		 * Url is generated if current page is not the front page.
		 * Links to home url using "home_url" function.
		 *
		 * @since 1.0.0
		 *
		 * @param array $raw_crumb {
		 *     @type string $title Anchor text (not escaped).
		 *     @type string $url   Link url (not escaped).
		 * }
		 * @param array $args Arguments passed to "tux_breadcrumbs_output".
		 */
		tux_breadcrumbs_drop( apply_filters( 'tux_breadcrumbs_home', array( 'title' => $args[ 'labels' ][ 'home' ], 'url' => '' ), $args ) );

		return;

	}

	// This filter is documented above.
	tux_breadcrumbs_drop( apply_filters( 'tux_breadcrumbs_home', array( 'title' => $args[ 'labels' ][ 'home' ], 'url' => home_url( '/' ) ), $args ) );

	/**
	 * Allow the generator to be short circuited.
	 *
	 * The filtering function should use the "tux_breadcrumbs_drop"
	 * function or change the global "tux_breadcrumbs_raw" variable,
	 * returning true to stop the generator from dropping breadcrumbs,
	 * or false to continue. A "Home" breadcrumb will already have
	 * been dropped when this filter is called.
	 *
	 * @since 1.0.0
	 *
	 * @param boolean $continue False, continue processing.
	 * @param array   $args     Arguments passed to "tux_breadcrumbs_output".
	 */
	if ( apply_filters( 'tux_breadcrumbs_generator', false, $args ) ) return;

	// Current page is the blog archive.
	if ( is_home() ) {

		/**
		 * Filters breadcrumb blog link.
		 *
		 * No url is generated.
		 *
		 * @since 1.0.0
		 *
		 * @param array $raw_crumb {
		 *     @type string $title Anchor text (not escaped).
		 *     @type string $url   Link url (not escaped).
		 * }
		 * @param array $args Arguments passed to "tux_breadcrumbs_output".
		 */
		tux_breadcrumbs_drop( apply_filters( 'tux_breadcrumbs_blog', array( 'title' => get_the_title( get_option( 'page_for_posts' ) ), 'url' => '' ), $args ) );

		return;

	}

	// Current page is a search page.
	if ( is_search() ) {

		/**
		 * Filters breadcrumb search link.
		 *
		 * No url is generated.
		 *
		 * @since 1.0.0
		 *
		 * @param array $raw_crumb {
		 *     @type string $title Anchor text (not escaped).
		 *     @type string $url   Link url (not escaped).
		 * }
		 * @param array $args Arguments passed to "tux_breadcrumbs_output".
		 */
		tux_breadcrumbs_drop( apply_filters( 'tux_breadcrumbs_search', array( 'title' => $args[ 'labels' ][ 'search' ] . '"' . apply_filters( 'the_search_query', get_search_query() ) . '"', 'url' => '' ), $args ) );

		return;

	}

	// Current page is the 404 not found page.
	if ( is_404() ) {

		$post_query_name = esc_html( str_replace( "-", " ", preg_replace( "/(.*)-(html|htm|php|asp|aspx)$/", "$1", get_query_var( 'name' ) ) ) );

		/**
		 * Filters breadcrumb 404 not found link.
		 *
		 * No url is generated.
		 *
		 * @since 1.0.0
		 *
		 * @param array $raw_crumb {
		 *     @type string $title Anchor text (not escaped).
		 *     @type string $url   Link url (not escaped).
		 * }
		 * @param array $args Arguments passed to "tux_breadcrumbs_output".
		 */
		tux_breadcrumbs_drop( apply_filters( 'tux_breadcrumbs_404', array( 'title' => $args[ 'labels' ][ '404' ] . $post_query_name, 'url' => '' ), $args ) );

		return;

	}

	// Current page is a page.
	if ( is_page() ) {

		global $wp_query;
		$post = $wp_query->get_queried_object();

		if ( ! $post->post_parent ) {

			/**
			 * Filters breadcrumb page link.
			 *
			 * No url is generated.
			 *
			 * @since 1.0.0
			 *
			 * @param array $raw_crumb {
			 *     @type string $title Anchor text (not escaped).
			 *     @type string $url   Link url (not escaped).
			 * }
			 * @param array $args Arguments passed to "tux_breadcrumbs_output".
			 */
			tux_breadcrumbs_drop( apply_filters( 'tux_breadcrumbs_page', array( 'title' => get_the_title(), 'url' => '' ), $args ) );

			return;

		}

		if ( isset( $post->ancestors ) ) {

			if ( is_array( $post->ancestors ) )

				$ancestors = array_values( $post->ancestors );

			else

				$ancestors = array( $post->ancestors );

		} else {

			$ancestors = array( $post->post_parent );

		}

		foreach ( $ancestors as $ancestor ) {

			/**
			 * Filters breadcrumb page parent link.
			 *
			 * Links to parent page.
			 *
			 * @since 1.0.0
			 *
			 * @param array $raw_crumb {
			 *     @type string $title Anchor text (not escaped).
			 *     @type string $url   Link url (not escaped).
			 * }
			 * @param array $args Arguments passed to "tux_breadcrumbs_output".
			 */
			tux_breadcrumbs_drop( apply_filters( 'tux_breadcrumbs_page_parent', array( 'title' => get_the_title( $ancestor ), 'url' => get_permalink( $ancestor ) ), $args ) );

		}

		// This filter is documented above.
		tux_breadcrumbs_drop( apply_filters( 'tux_breadcrumbs_page', array( 'title' => get_the_title( $post->ID ), 'url' => '' ), $args ) );

		return;

	}

	// Current page is a category archive.
	if ( is_category() ) {

		$category = get_term( get_query_var( 'cat' ), 'category' );

		if ( is_wp_error( $category ) ) return;

		if ( $category->parent && $category->parent != $category->term_id ) {

			$parents = array();
			$visited = array();
			$parent = $category;

			while ( $parent->parent && $parent->parent != $parent->term_id && ! in_array( $parent->parent, $visited ) ) {

				$parent = get_term( $parent->parent, 'category' );

				if ( is_wp_error( $parent ) ) break;

				array_unshift( $parents, array( 'title' => $parent->name, 'url' => get_category_link( $parent->term_id ) ) );
				$visited[] = $parent->term_id;

			}

			foreach ( $parents as $parent ) {

				/**
				 * Filters breadcrumb category archive parent link.
				 *
				 * Links to parent category archive.
				 *
				 * @since 1.0.0
				 *
				 * @param array $raw_crumb {
				 *     @type string $title Anchor text (not escaped).
				 *     @type string $url   Link url (not escaped).
				 * }
				 * @param array $args Arguments passed to "tux_breadcrumbs_output".
				 */
				tux_breadcrumbs_drop( apply_filters( 'tux_breadcrumbs_category_parent', $parent, $args ) );

			}

		}

		/**
		 * Filters breadcrumb category archive link.
		 *
		 * No url is generated.
		 *
		 * @since 1.0.0
		 *
		 * @param array $raw_crumb {
		 *     @type string $title Anchor text (not escaped).
		 *     @type string $url   Link url (not escaped).
		 * }
		 * @param array $args Arguments passed to "tux_breadcrumbs_output".
		 */
		tux_breadcrumbs_drop( apply_filters( 'tux_breadcrumbs_category', array( 'title' => $args[ 'labels' ][ 'category' ] . $category->name, 'url' => '' ), $args ) );

		return;

	}

	// Current page is a tag archive.
	if ( is_tag() ) {

		/**
		 * Filters breadcrumb tag archive link.
		 *
		 * No url is generated.
		 *
		 * @since 1.0.0
		 *
		 * @param array $raw_crumb {
		 *     @type string $title Anchor text (not escaped).
		 *     @type string $url   Link url (not escaped).
		 * }
		 * @param array $args Arguments passed to "tux_breadcrumbs_output".
		 */
		tux_breadcrumbs_drop( apply_filters( 'tux_breadcrumbs_tag', array( 'title' => $args[ 'labels' ][ 'tag' ] . single_term_title( '', false ), 'url' => '' ), $args ) );

		return;

	}

	// Current page is a taxonomy archive.
	if ( is_tax() ) {

		global $wp_query;

		$term  = $wp_query->get_queried_object();
		$taxonomy = get_term( $term->term_id, $term->taxonomy );

		if ( is_wp_error( $taxonomy ) ) return;

		if ( $taxonomy->parent && $taxonomy->parent != $taxonomy->term_id ) {

			$parents = array();
			$visited = array();
			$parent = $taxonomy;

			while ( $parent->parent && $parent->parent != $parent->term_id && ! in_array( $parent->parent, $visited ) ) {

				$parent = get_term( $parent->parent, $parent->taxonomy );

				if ( is_wp_error( $parent ) ) break;

				array_unshift( $parents, array( 'title' => $parent->name, 'url' => get_term_link( $parent->term_id, $parent->taxonomy ) ) );
				$visited[] = $parent->term_id;

			}

			foreach ( $parents as $parent ) {

				/**
				 * Filters breadcrumb taxonomy archive parent link.
				 *
				 * Links to taxonomy archive.
				 *
				 * @since 1.0.0
				 *
				 * @param array $raw_crumb {
				 *     @type string $title Anchor text (not escaped).
				 *     @type string $url   Link url (not escaped).
				 * }
				 * @param array $args Arguments passed to "tux_breadcrumbs_output".
				 */
				tux_breadcrumbs_drop( apply_filters( 'tux_breadcrumbs_taxonomy_parent', $parent, $args ) );

			}

		}

		/**
		 * Filters breadcrumb taxonomy archive link.
		 *
		 * No url is generated.
		 *
		 * @since 1.0.0
		 *
		 * @param array $raw_crumb {
		 *     @type string $title Anchor text (not escaped).
		 *     @type string $url   Link url (not escaped).
		 * }
		 * @param array $args Arguments passed to "tux_breadcrumbs_output".
		 */
		tux_breadcrumbs_drop( apply_filters( 'tux_breadcrumbs_taxonomy', array( 'title' => $args[ 'labels' ][ 'taxonomy' ] . $taxonomy->name, 'url' => '' ), $args ) );

		return;

	}

	// Current page is a year based archive.
	if ( is_year() ) {

		/**
		 * Filters breadcrumb year based archive link.
		 *
		 * Url is generated if current page is a month or day based archive page.
		 * Links to year based archive.
		 *
		 * @since 1.0.0
		 *
		 * @param array $raw_crumb {
		 *     @type string $title Anchor text (not escaped).
		 *     @type string $url   Link url (not escaped).
		 * }
		 * @param array $args Arguments passed to "tux_breadcrumbs_output".
		 */
		tux_breadcrumbs_drop( apply_filters( 'tux_breadcrumbs_year', array( 'title' => $args[ 'labels' ][ 'date' ] . ( ( get_query_var( 'm' ) ) ? get_query_var( 'm' ) : get_query_var( 'year' ) ), 'url' => '' ), $args ) );

		return;

	}

	// Current page is a month based archive.
	if ( is_month() ) {

		$year = get_query_var( 'm' ) ? mb_substr( get_query_var( 'm' ), 0, 4 ) : get_query_var( 'year' );

		// This filter is documented above.
		tux_breadcrumbs_drop( apply_filters( 'tux_breadcrumbs_year', array( 'title' => $year, 'url' => get_year_link( $year ) ), $args ) );

		/**
		 * Filters breadcrumb month based archive link.
		 *
		 * Url is generated if current page is a day based archive page.
		 * Links to month based archive.
		 *
		 * @since 1.0.0
		 *
		 * @param array $raw_crumb {
		 *     @type string $title Anchor text (not escaped).
		 *     @type string $url   Link url (not escaped).
		 * }
		 * @param array $args Arguments passed to "tux_breadcrumbs_output".
		 */
		tux_breadcrumbs_drop( apply_filters( 'tux_breadcrumbs_month', array( 'title' => $args[ 'labels' ][ 'date' ] . single_month_title( ' ', false ) ), $args ) );

		return;

	}

	// Current page is a day based archive.
	if ( is_day() ) {

		global $wp_locale;

		$year = get_query_var( 'm' ) ? mb_substr( get_query_var( 'm' ), 0, 4 ) : get_query_var( 'year' );
		$month = get_query_var( 'm' ) ? mb_substr( get_query_var( 'm' ), 4, 2 ) : get_query_var( 'monthnum' );
		$day = get_query_var( 'm' ) ? mb_substr( get_query_var( 'm' ), 6, 2 ) : get_query_var( 'day' );

		// This filter is documented above.
		tux_breadcrumbs_drop( apply_filters( 'tux_breadcrumbs_year', array( 'title' => $year, 'url' => get_year_link( $year ) ), $args ) );

		// This filter is documented above.
		tux_breadcrumbs_drop( apply_filters( 'tux_breadcrumbs_month', array( 'title' => $wp_locale->get_month( $month ), 'url' => get_month_link( $year, $month ) ), $args ) );

		/**
		 * Filters breadcrumb day based archive link.
		 *
		 * No url is generated.
		 *
		 * @since 1.0.0
		 *
		 * @param array $raw_crumb {
		 *     @type string $title Anchor text (not escaped).
		 *     @type string $url   Link url (not escaped).
		 * }
		 * @param array $args Arguments passed to "tux_breadcrumbs_output".
		 */
		tux_breadcrumbs_drop( apply_filters( 'tux_breadcrumbs_day', array( 'title' => $args[ 'labels' ][ 'date' ] . $day . date( 'S', mktime( 0, 0, 0, 1, $day ) ), 'url' => '' ), $args ) );

		return;

	}

	// Current page is an author archive.
	if ( is_author() ) {

		global $wp_query;

		/**
		 * Filters breadcrumb author archive link.
		 *
		 * No url is generated.
		 *
		 * @since 1.0.0
		 *
		 * @param array $raw_crumb {
		 *     @type string $title Anchor text (not escaped).
		 *     @type string $url   Link url (not escaped).
		 * }
		 * @param array $args Arguments passed to "tux_breadcrumbs_output".
		 */
		tux_breadcrumbs_drop( apply_filters( 'tux_breadcrumbs_author', array( 'title' => $args[ 'labels' ][ 'author' ] . $wp_query->queried_object->display_name, 'url' => ''), $args ) );

		return;

	}

	// Current page is a post archive.
	if ( is_post_type_archive() ) {

		/**
		 * Filters breadcrumb post archive link.
		 *
		 * No url is generated.
		 *
		 * @since 1.0.0
		 *
		 * @param array $raw_crumb {
		 *     @type string $title Anchor text (not escaped).
		 *     @type string $url   Link url (not escaped).
		 * }
		 * @param array $args Arguments passed to "tux_breadcrumbs_output".
		 */
		tux_breadcrumbs_drop( apply_filters( 'tux_breadcrumbs_post_type', array( 'title' => post_type_archive_title( '', false ), 'url' => '' ), $args ) );

		return;

	}

	// Current page is an attachment.
	if ( is_attachment() ) {

		global $post;

		$attachment_parent = get_post( $post->post_parent );

		/**
		 * Filters breadcrumb attachment parent link.
		 *
		 * Links to post the attachment is attached to.
		 *
		 * @since 1.0.0
		 *
		 * @param array $raw_crumb {
		 *     @type string $title Anchor text (not escaped).
		 *     @type string $url   Link url (not escaped).
		 * }
		 * @param array $args Arguments passed to "tux_breadcrumbs_output".
		 */
		if ( $attachment_parent ) tux_breadcrumbs_drop( apply_filters( 'tux_breadcrumbs_attachment_parent', array( 'title' => $attachment_parent->post_title, 'url' => get_permalink( $post->post_parent ) ), $args ) );

		/**
		 * Filters breadcrumb attachment link.
		 *
		 * No url is generated.
		 *
		 * @since 1.0.0
		 *
		 * @param array $raw_crumb {
		 *     @type string $title Anchor text (not escaped).
		 *     @type string $url   Link url (not escaped).
		 * }
		 * @param array $args Arguments passed to "tux_breadcrumbs_output".
		 */
		tux_breadcrumbs_drop( apply_filters( 'tux_breadcrumbs_attachment', array( 'title' => single_post_title( '', false ) ), $args ) );

		return;

	}

	// Current page is a post.
	if ( is_singular( 'post' ) ) {

		global $post;

		$categories = get_the_category( $post->ID );
		$visited = array();

		if ( $args[ 'post_cat_parents' ] ) {

			$parents = array();

			foreach( $categories as $category ) {

				if ( $category->parent && $category->parent != $category->term_id ) {

					$parent = get_term( $category->parent, 'category' );

					if ( ! is_wp_error( $parent ) ) {

						if ( $parent->parent != $parent->term_id && ! in_array( $parent->parent, $visited ) ) {

							$parents[] = array( 'title' => $parent->name, 'url' => get_category_link( $parent->term_id ) );
							$visited[] = $parent->term_id;

						}

						while ( $parent->parent && $parent->parent != $parent->term_id && ! in_array( $parent->parent, $visited ) ) {

							$parent = get_term( $parent->parent, 'category' );

							if ( is_wp_error( $parent ) ) break;

							array_unshift( $parents, array( 'title' => $parent->name, 'url' => get_category_link( $parent->term_id ) ) );
							$visited[] = $parent->term_id;

						}

					}

				}

			}

			if ( $parents ) {

				//$parents = array_reverse( $parents );
				foreach ( $parents as $parent ) {

					/**
					 * Filters breadcrumb post parent link.
					 *
					 * Links to post's categories and parent categories.
					 *
					 * @since 1.0.0
					 *
					 * @param array $raw_crumb {
					 *     @type string $title Anchor text (not escaped).
					 *     @type string $url   Link url (not escaped).
					 * }
					 * @param array $args Arguments passed to "tux_breadcrumbs_output".
					 */
					tux_breadcrumbs_drop( apply_filters( 'tux_breadcrumbs_post_parent', $parent, $args ) );

				}

			}

		}

		foreach ( $categories as $category ) {

			// This filter is documented above.
			if ( ! in_array( $category->term_id, $visited ) ) tux_breadcrumbs_drop( apply_filters( 'tux_breadcrumbs_post_parent', array( 'title' => $category->name, 'url' => get_category_link( $category->term_id ) ), $args ) );

		}

		/**
		 * Filters breadcrumb post link.
		 *
		 * No url is generated.
		 *
		 * @since 1.0.0
		 *
		 * @param array $raw_crumb {
		 *     @type string $title Anchor text (not escaped).
		 *     @type string $url   Link url (not escaped).
		 * }
		 * @param array $args Arguments passed to "tux_breadcrumbs_output".
		 */
		tux_breadcrumbs_drop( apply_filters( 'tux_breadcrumbs_post', array( 'title' => single_post_title( '', false ), 'url' => '' ), $args ) );

		return;

	}

	// Current page is a custom post type.
	if ( is_single() ) {

		$post_type = get_query_var( 'post_type' );
		$post_type_object = get_post_type_object( $post_type );
		$cpt_archive_link = get_post_type_archive_link( $post_type );

		/**
		 * Filters breadcrumb custom post type parent link.
		 *
		 * Links to the post type archive if available.
		 *
		 * @since 1.0.0
		 *
		 * @param array $raw_crumb {
		 *     @type string $title Anchor text (not escaped).
		 *     @type string $url   Link url (not escaped).
		 * }
		 * @param array $args Arguments passed to "tux_breadcrumbs_output".
		 */
		tux_breadcrumbs_drop( apply_filters( 'tux_breadcrumbs_cpt_parent', array( 'title' => $post_type_object->labels->name, 'url' => ( $cpt_archive_link ) ? $cpt_archive_link : '' ), $args ) );

		/**
		 * Filters breadcrumb custom post type link.
		 *
		 * No url is generated.
		 *
		 * @since 1.0.0
		 *
		 * @param array $raw_crumb {
		 *     @type string $title Anchor text (not escaped).
		 *     @type string $url   Link url (not escaped).
		 * }
		 * @param array $args Arguments passed to "tux_breadcrumbs_output".
		 */
		tux_breadcrumbs_drop( apply_filters( 'tux_breadcrumbs_cpt', array( 'title' => single_post_title( '', false ), 'url' => '' ), $args ) );

		return;

	}

}

/**
 * Drop (add) a crumb.
 *
 * Adds to the global array "tux_breadcrumbs_raw".
 *
 * @since 1.0.0
 *
 * @param array $crumb {
 *     @type string $title Anchor text.
 *     @type string $url   Link url.
 * }
 */
function tux_breadcrumbs_drop( $crumb = array() ) {

	global $tux_breadcrumbs_raw;

	$crumb = wp_parse_args( $crumb, array( 'title' => '', 'url' => '' ) );

	if ( $crumb[ 'title' ] ) $tux_breadcrumbs_raw[] = $crumb;

}

