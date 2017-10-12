<?php
/**
 * Override of WooCommerce template archive-product.php.
 *
 * @package ThemeTuxedo/WooCommerce
 * @since 1.0.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Remove default loop.
remove_action( 'tux_loop', 'tux_loop_default', 10 );

// Remove WooCommerce wrappers.
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

// Remove WooCommerce breadcrumbs.
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );

// Remove archive title output.
remove_action( 'tux_loop_before', 'tux_archive_title_desc_output', 10 );

/**
 * Load shop layout options.
 *
 * Attached to the "tux_layout_current" filter.
 *
 * @since 1.0.0
 */
function tux_woocommerce_shop_layout(  ) {

	if ( is_shop() ) {

		$current_layout = get_post_meta( get_option( 'woocommerce_shop_page_id' ), '_tux_layout', true );
		if ( ! isset( $current_layout[ 'layout' ] ) || $current_layout[ 'layout' ] == 'default' ) $current_layout = get_option( 'tux_options' );

		$registered_layouts = tux_layout_get_registered();

		return ( isset( $current_layout[ 'layout' ] ) && isset( $registered_layouts[ $current_layout[ 'layout' ] ] ) ) ? esc_attr( $current_layout[ 'layout' ] ) : tux_layout_get_default();

	}

	return null;

}
add_filter( 'tux_layout_current', 'tux_woocommerce_shop_layout' );

/**
 * WooCommerce archive-product.php loop.
 *
 * Items commented out are intentional left in to see what the original template called.
 *
 * Attached to the "tux_loop" hook.
 *
 * @since 1.0.0
 */
function tux_woocommerce_product_archive_loop() {

	//get_header( 'shop' );

	/**
	 * woocommerce_before_main_content hook
	 *
	 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
	 * @hooked woocommerce_breadcrumb - 20
	 */
	do_action( 'woocommerce_before_main_content' );

	if ( apply_filters( 'woocommerce_show_page_title', true ) ) :

			tux_post_header_tag_open(); ?>
			<h1 class="page-title entry-title"><?php woocommerce_page_title(); ?></h1>
			<?php tux_post_header_tag_close();

	endif;

	/**
	 * woocommerce_archive_description hook
	 *
	 * @hooked woocommerce_taxonomy_archive_description - 10
	 * @hooked woocommerce_product_archive_description - 10
	 */
	do_action( 'woocommerce_archive_description' );

	if ( have_posts() ) :

		/**
		 * woocommerce_before_shop_loop hook
		 *
		 * @hooked woocommerce_result_count - 20
		 * @hooked woocommerce_catalog_ordering - 30
		 */
		do_action( 'woocommerce_before_shop_loop' );

		woocommerce_product_loop_start();

		woocommerce_product_subcategories();

		while ( have_posts() ) : the_post();

			wc_get_template_part( 'content', 'product' );

		endwhile; // end of the loop.

		woocommerce_product_loop_end();

		/**
		 * woocommerce_after_shop_loop hook
		 *
		 * @hooked woocommerce_pagination - 10
		 */
		do_action( 'woocommerce_after_shop_loop' );

	elseif ( ! woocommerce_product_subcategories( array( 'before' => woocommerce_product_loop_start( false ), 'after' => woocommerce_product_loop_end( false ) ) ) ) :

		wc_get_template( 'loop/no-products-found.php' );

	endif;

	/**
	 * woocommerce_after_main_content hook
	 *
	 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
	 */
	do_action( 'woocommerce_after_main_content' );

	/**
	 * woocommerce_sidebar hook
	 *
	 * @hooked woocommerce_get_sidebar - 10
	 */
	//do_action( 'woocommerce_sidebar' );

	//get_footer( 'shop' );

}
add_action( 'tux_loop', 'tux_woocommerce_product_archive_loop' );

// Output template.
suit_up();

