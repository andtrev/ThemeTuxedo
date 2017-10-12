<?php
/**
 * Meta output templates
 *
 * @package ThemeTuxedo\Template
 * @since 1.0.0
 */

/**
 * Return the post author's name meta.
 * Shortcode "[tux_post_author]".
 *
 * @since 1.0.0
 *
 * @return string Author's name meta output.
 */
function tux_meta_post_author( $atts ) {

	/**
	 * Filters the post author name meta shortcode attributes under the
	 * "shortcode_atts_tux_post_author" hook.
	 *
	 * @since 1.0.0
	 *
	 * @param array {
	 *     Arguments.
	 *
	 *     @type string $before HTML before author name. Default: "".
	 *     @type string $after  HTML after author name. Default: "".
	 *     @type string $label  Label before author name. Default: "Author: ".
	 * }
	 */
	$author_meta_args = shortcode_atts( array(

		'before' => '',
		'after'  => '',
		'label'  => __( 'Author: ', 'tuxedo' )

	), $atts, 'tux_post_author' );

	$author = get_the_author();

	$meta = '<span' . tux_get_attr( 'entry-author' ) . '>' . $author_meta_args[ 'before' ] . esc_html( $author_meta_args[ 'label' ] ) . '<span' . tux_get_attr( 'entry-author-name' ) . '>' . esc_html( $author ) . '</span>' . $author_meta_args[ 'after' ] . '</span>';

	/**
	 * Filters the post author meta.
	 * Output from "[tux_post_author]" shortcode.
	 *
	 * @since 1.0.0
	 *
	 * @param string $meta       Post author meta HTML.
	 * @param array $author_meta_args {
	 *     Arguments.
	 *
	 *     @type string $before HTML before author name. Default: "".
	 *     @type string $after  HTML after author name. Default: "".
	 *     @type string $label  Label before author name. Default: "Author: ".
	 * }
	 * @param string $author     Author name.
	 */
	return apply_filters( 'tux_meta_post_author', $meta, $author_meta_args, $author );

}
add_shortcode( 'tux_post_author', 'tux_meta_post_author' );

/**
 * Return the post author's url link meta.
 * Shortcode "[tux_post_author_url_link]".
 *
 * This is the url entered under "website" in the user's profile.
 *
 * @since 1.0.0
 *
 * @return string Author's url link meta output.
 */
function tux_meta_post_author_url_link( $atts ) {

	/**
	 * Filters the post author url link meta shortcode attributes under the
	 * "shortcode_atts_tux_post_author_url_link" hook.
	 *
	 * @since 1.0.0
	 *
	 * @param array {
	 *     Arguments.
	 *
	 *     @type string $before HTML before author name and link. Default "".
	 *     @type string $after  HTML after author name and link. Default "".
	 *     @type string $label  Label before author name and link. Default "Author: ".
	 * }
	 */
	$author_meta_args = shortcode_atts( array(

		'before' => '',
		'after'  => '',
		'label'  => __( 'Author: ', 'tuxedo' )

	), $atts, 'tux_post_author_url_link' );

	$author = get_the_author();
	$url = get_the_author_meta( 'url' );

	$meta = '<span' . tux_get_attr( 'entry-author' ) . '>' . $author_meta_args[ 'before' ] . esc_html( $author_meta_args[ 'label' ] ) . ( ( $url ) ? '<a href="' . esc_url( $url ) . '"' . tux_get_attr( 'entry-author-link' ) . '>' : '' ) . '<span' . tux_get_attr( 'entry-author-name' ) . '>' . esc_html( $author ) . '</span>' . ( ( $url ) ? '</a>' : '' ) . $author_meta_args[ 'after' ] . '</span>';

	/**
	 * Filters the post author url link meta.
	 * Output from "[tux_post_author_url_link]" shortcode.
	 *
	 * @since 1.0.0
	 *
	 * @param string $meta       Post author url link meta HTML.
	 * @param array $author_meta_args {
	 *     Arguments.
	 *
	 *     @type string $before HTML before author name and link. Default "".
	 *     @type string $after  HTML after author name and link. Default "".
	 *     @type string $label  Label before author name and link. Default "Author: ".
	 * }
	 * @param string $author     Author name.
	 * @param string $url        Author url.
	 */
	return apply_filters( 'tux_meta_post_author_url_link', $meta, $author_meta_args, $author, $url );

}
add_shortcode( 'tux_post_author_url_link', 'tux_meta_post_author_url_link' );

/**
 * Return the post author's post archive link meta.
 * Shortcode "[tux_post_author_archive_link]".
 *
 * @since 1.0.0
 *
 * @return string Author's post archive link meta output.
 */
function tux_meta_post_author_archive_link( $atts ) {

	/**
	 * Filters the post author archive link meta shortcode attributes under the
	 * "shortcode_atts_tux_post_author_archive_link" hook.
	 *
	 * @since 1.0.0
	 *
	 * @param array {
	 *     Arguments.
	 *
	 *     @type string $before HTML before author name and archive link. Default "".
	 *     @type string $after  HTML after author name and archive link. Default "".
	 *     @type string $label  Label before author name and archive link. Default "Author: ".
	 * }
	 */
	$author_meta_args = shortcode_atts( array(

		'before' => '',
		'after'  => '',
		'label'  => __( 'Author: ', 'tuxedo' )

	), $atts, 'tux_post_author_archive_link' );

	$author = get_the_author();
	$url = get_author_posts_url( get_the_author_meta( 'ID' ) );

	$meta = '<span' . tux_get_attr( 'entry-author' ) . '>' . $author_meta_args[ 'before' ] . esc_html( $author_meta_args[ 'label' ] ) . '<a href="' . esc_url( $url ) . '"' . tux_get_attr( 'entry-author-link' ) . '><span' . tux_get_attr( 'entry-author-name' ) . '>' . esc_html( $author ) . '</span></a>' . $author_meta_args[ 'after' ] . '</span>';

	/**
	 * Filters the post author archive link meta.
	 * Output from "[tux_post_author_archive_link]" shortcode.
	 *
	 * @since 1.0.0
	 *
	 * @param string $meta       Author archive link meta HTML.
	 * @param array $author_meta_args {
	 *     Arguments.
	 *
	 *     @type string $before HTML before author name and archive link. Default "".
	 *     @type string $after  HTML after author name and archive link. Default "".
	 *     @type string $label  Label before author name and archive link. Default "Author: ".
	 * }
	 * @param string $author     Author name.
	 * @param string $url        Author archive url.
	 */
	return apply_filters( 'tux_meta_post_author_archive_link', $meta, $author_meta_args, $author, $url );

}
add_shortcode( 'tux_post_author_archive_link', 'tux_meta_post_author_archive_link' );

/**
 * Return the post's published date meta.
 * Shortcode "[tux_post_published_date]".
 *
 * @since 1.0.0
 *
 * @return string Published date meta output.
 */
function tux_meta_post_published_date( $atts ) {

	/**
	 * Filters the post's published date meta shortcode attributes under the
	 * "shortcode_atts_tux_post_published_date" hook.
	 *
	 * @since 1.0.0
	 *
	 * @param array {
	 *     Arguments.
	 *
	 *     @type string $format Date format string. Defaults to WordPress settings date format.
	 *     @type string $before HTML before post date. Default "".
	 *     @type string $after  HTML after post date. Default "".
	 *     @type string $label  Label before post date. Default "Published on: ".
	 * }
	 */
	$published_date_meta_args = shortcode_atts( array(

		'format' => get_option( 'date_format' ),
		'before' => '',
		'after'  => '',
		'label'  => __( 'Published on: ', 'tuxedo' )

	), $atts, 'tux_post_published_date' );

	$published_date = get_the_time( $published_date_meta_args[ 'format' ] );

	$meta =  '<time' . tux_get_attr( 'entry-time' ) . '>' . $published_date_meta_args[ 'before' ] . esc_html( $published_date_meta_args[ 'label' ] ) . $published_date . $published_date_meta_args[ 'after' ] . '</time>';

	/**
	 * Filters the post's published date meta.
	 * Output from "[tux_post_published_date]" shortcode.
	 *
	 * @since 1.0.0
	 *
	 * @param string $meta       Published date meta HTML.
	 * @param array $published_date_meta_args {
	 *     Arguments.
	 *
	 *     @type string $format Date format string. Defaults to WordPress settings date format.
	 *     @type string $before HTML before post date. Default "".
	 *     @type string $after  HTML after post date. Default "".
	 *     @type string $label  Label before post date. Default "Published on: ".
	 * }
	 * @param string $published_date Formatted date.
	 */
	return apply_filters( 'tux_meta_post_published_date', $meta, $published_date_meta_args, $published_date );

}
add_shortcode( 'tux_post_published_date', 'tux_meta_post_published_date' );

/**
 * Return the post's published time meta.
 * Shortcode "[tux_post_published_time]".
 *
 * @since 1.0.0
 *
 * @return string Published time meta output.
 */
function tux_meta_post_published_time( $atts ) {

	/**
	 * Filters the post's published time meta shortcode attributes under the
	 * "shortcode_atts_tux_post_published_time" hook.
	 *
	 * @since 1.0.0
	 *
	 * @param array {
	 *     Arguments.
	 *
	 *     @type string $format Date format string. Defaults to WordPress settings time format.
	 *     @type string $before HTML before post time. Default "".
	 *     @type string $after  HTML after post time. Default "".
	 *     @type string $label  Label before post time. Default "Published at: ".
	 * }
	 */
	$published_time_meta_args = shortcode_atts( array(

		'format' => get_option( 'time_format' ),
		'before' => '',
		'after'  => '',
		'label'  => __( 'Published at: ', 'tuxedo' )

	), $atts, 'tux_post_published_time' );

	$published_time = get_the_time( $published_time_meta_args[ 'format' ] );

	$meta = '<time' . tux_get_attr( 'entry-time' ) . '>' . $published_time_meta_args[ 'before' ] . esc_html( $published_time_meta_args[ 'label' ] ) . $published_time . $published_time_meta_args[ 'after' ] . '</time>';

	/**
	 * Filters the post's published time meta.
	 * Output from "[tux_post_published_time]" shortcode.
	 *
	 * @since 1.0.0
	 *
	 * @param string $meta       Published time meta HTML.
	 * @param array $published_time_meta_args {
	 *     Arguments.
	 *
	 *     @type string $format Date format string. Defaults to WordPress settings time format.
	 *     @type string $before HTML before post time. Default "".
	 *     @type string $after  HTML after post time. Default "".
	 *     @type string $label  Label before post time. Default "Published at: ".
	 * }
	 * @param string $published_time Formatted time.
	 */
	return apply_filters( 'tux_meta_post_published_time', $meta, $published_time_meta_args, $published_time );

}
add_shortcode( 'tux_post_published_time', 'tux_meta_post_published_time' );

/**
 * Return the post's last modified date meta.
 * Shortcode "[tux_post_modified_date]".
 *
 * @since 1.0.0
 *
 * @return string Last modified date meta output.
 */
function tux_meta_post_modified_date( $atts ) {

	/**
	 * Filters the post's last modified date meta shortcode attributes under the
	 * "shortcode_atts_tux_post_modified_date" hook.
	 *
	 * @since 1.0.0
	 *
	 * @param array {
	 *     Arguments.
	 *
	 *     @type string $format Date format string. Defaults to WordPress settings date format.
	 *     @type string $before HTML before post date. Default "".
	 *     @type string $after  HTML after post date. Default "".
	 *     @type string $label  Label before post date. Default "Last Updated on: ".
	 * }
	 */
	$modified_date_meta_args = shortcode_atts( array(

		'format' => get_option( 'date_format' ),
		'before' => '',
		'after'  => '',
		'label'  => __( 'Last Updated on: ', 'tuxedo' )

	), $atts, 'tux_post_modified_date' );

	$modified_date = get_the_modified_time( $modified_date_meta_args[ 'format' ] );

	$meta =  '<time' . tux_get_attr( 'entry-modified-time' ) . '>' . $modified_date_meta_args[ 'before' ] . esc_html( $modified_date_meta_args[ 'label' ] ) . $modified_date . $modified_date_meta_args[ 'after' ] . '</time>';

	/**
	 * Filters the post's last modified date meta.
	 * Output from "[tux_post_modified_date]" shortcode.
	 *
	 * @since 1.0.0
	 *
	 * @params string $meta      Modified date meta HTML.
	 * @param array $modified_date_meta_args {
	 *     Arguments.
	 *
	 *     @type string $format Date format string. Defaults to WordPress settings date format.
	 *     @type string $before HTML before post date. Default "".
	 *     @type string $after  HTML after post date. Default "".
	 *     @type string $label  Label before post date. Default "Last Updated on: ".
	 * }
	 * @param string $modified_date Formatted date.
	 */
	return apply_filters( 'tux_meta_post_modified_date', $meta, $modified_date_meta_args, $modified_date );

}
add_shortcode( 'tux_post_modified_date', 'tux_meta_post_modified_date' );

/**
 * Return the post's last modified time meta.
 * Shortcode "[tux_post_modified_time]".
 *
 * @since 1.0.0
 * @return string Last modified time meta output.
 */
function tux_meta_post_modified_time( $atts ) {

	/**
	 * Filters the post's last modified time meta shortcode attributes under the
	 * "shortcode_atts_tux_post_modified_time" hook.
	 *
	 * @since 1.0.0
	 *
	 * @param array {
	 *     Arguments.
	 *
	 *     @type string $format Date format string. Defaults to WordPress settings date format.
	 *     @type string $before HTML before post time. Default "".
	 *     @type string $after  HTML after post time. Default "".
	 *     @type string $label  Label before post time. Default "Last Updated at: ".
	 * }
	 */
	$modified_time_meta_args = shortcode_atts( array(

		'format' => get_option( 'time_format' ),
		'before' => '',
		'after'  => '',
		'label'  => __( 'Last Updated at: ', 'tuxedo' )

	), $atts, 'tux_post_modified_time' );

	$modified_time = get_the_modified_time( $modified_time_meta_args[ 'format' ] );

	$meta =  '<time' . tux_get_attr( 'entry-modified-time' ) . '>' . $modified_time_meta_args[ 'before' ] . esc_html( $modified_time_meta_args[ 'label' ] ) . $modified_time . $modified_time_meta_args[ 'after' ] . '</time>';

	/**
	 * Filters the post's last modified time meta.
	 * Output from "[tux_post_modified_time]" shortcode.
	 *
	 * @since 1.0.0
	 *
	 * @param string $meta       Modified time meta HTML.
	 * @param array $modified_time_meta_args {
	 *     Arguments.
	 *
	 *     @type string $format Date format string. Defaults to WordPress settings date format.
	 *     @type string $before HTML before post time. Default "".
	 *     @type string $after  HTML after post time. Default "".
	 *     @type string $label  Label before post time. Default "Last Updated at: ".
	 * }
	 * @param string $modified_time Formatted time.
	 */
	return apply_filters( 'tux_meta_post_modified_date', $meta, $modified_time_meta_args, $modified_time );

}
add_shortcode( 'tux_post_modified_time', 'tux_meta_post_modified_time' );

/**
 * Return comments link meta.
 * Shortcode "[tux_post_comments]".
 *
 * @since 1.0.0
 *
 * @return string Comments link meta output.
 */
function tux_meta_post_comments_link( $atts ) {

	/**
	 * Filters the post's comment link meta shortcode attributes under the
	 * "shortcode_atts_tux_post_comments" hook.
	 *
	 * @since 1.0.0
	 *
	 * @param array {
	 *     Arguments.
	 *
	 *     @type string $hide_if_off True hides link if comments are closed. Default "true".
	 *     @type string $more        Text to display when there is more than one comment. % is replaced by the number of comments. Default "% Comments".
	 *     @type string $one         Text to display when there is one comment. Default "1 Comment".
	 *     @type string $zero        Text to display when there are no comments. Default "Leave a Comment".
	 *     @type string $off         Text to display when there are no comments and comments are closed. Default "Comments Closed".
	 *     @type string $before      HTML before comment link. Default "".
	 *     @type string $after       HTML after comment link. Default "".
	 *     @type string $label       Label before comment link. Default "".
	 * }
	 */
	$comment_meta_args = shortcode_atts( array(

		'hide_if_off' => 'true',
		'more'        => __( '% Comments', 'tuxedo' ),
		'one'         => __( '1 Comment', 'tuxedo' ),
		'zero'        => __( 'Leave a Comment', 'tuxedo' ),
		'off'         => __( 'Comments Closed', 'tuxedo' ),
		'before'      => '',
		'after'       => '',
		'label'       => ''

	), $atts, 'tux_post_comments' );

	if ( comments_open() ) {

		$comments_num = '<a href="' . get_comments_link() . '">' . get_comments_number_text( $comment_meta_args[ 'zero' ], $comment_meta_args[ 'one' ], $comment_meta_args[ 'more' ] ) . '</a>';

	} else {

		if ( $comment_meta_args[ 'hide_if_off' ] === 'true' ) return '';

		if ( get_comments_number() > 0 )
			$comments_num = '<a href="' . get_comments_link() . '">' . get_comments_number_text( $comment_meta_args[ 'off' ], $comment_meta_args[ 'one' ], $comment_meta_args[ 'more' ] ) . '</a>';
		else
			$comments_num = $comment_meta_args[ 'off' ];

	}

	$meta = '<span' . tux_get_attr( 'entry-comments-link' ) . '>' . $comment_meta_args[ 'before' ] . esc_html( $comment_meta_args[ 'label' ] ) . $comments_num . $comment_meta_args[ 'after' ] . '</span>';

	/**
	 * Filters the post's comments link meta.
	 * Output from "[tux_post_comments]" shortcode.
	 *
	 * @since 1.0.0
	 *
	 * @param string $meta            Post comments link meta HTML.
	 * @param array $comment_meta_args {
	 *     Arguments.
	 *
	 *     @type string $hide_if_off True hides link if comments are closed. Default "true".
	 *     @type string $more        Text to display when there is more than one comment. % is replaced by the number of comments. Default "% Comments".
	 *     @type string $one         Text to display when there is one comment. Default "1 Comment".
	 *     @type string $zero        Text to display when there are no comments. Default "Leave a Comment".
	 *     @type string $off         Text to display when there are no comments and comments are closed. Default "Comments Closed".
	 *     @type string $before      HTML before comment link. Default "".
	 *     @type string $after       HTML after comment link. Default "".
	 *     @type string $label       Label before comment link. Default "".
	 * }
	 * @param string $comments_num    Comments number link HTML.
	 */
	return apply_filters( 'tux_meta_post_comments_link', $meta, $comment_meta_args, $comments_num );

}
add_shortcode( 'tux_post_comments', 'tux_meta_post_comments_link' );

/**
 * Return post categories meta.
 * Shortcode "[tux_post_categories]".
 *
 * @since 1.0.0
 *
 * @return string Categories meta output.
 */
function tux_meta_post_categories( $atts ) {

	/**
	 * Filters the post categories meta shortcode attributes under the
	 * "shortcode_atts_tux_post_categories" hook.
	 *
	 * @since 1.0.0
	 *
	 * @param array {
	 *     Arguments.
	 *
	 *     @type string $sep    Categories list separator. Default ", ".
	 *     @type string $before HTML before categories list. Default "".
	 *     @type string $after  HTML after categories list. Default "".
	 *     @type string $label  Label before categories list. Default "Posted in: ".
	 * }
	 */
	$categories_meta_args = shortcode_atts( array(

		'sep'    => ', ',
		'before' => '',
		'after'  => '',
		'label'  => __( 'Posted in: ', 'tuxedo' )

	), $atts, 'tux_post_categories' );

	$category_list = get_the_category_list( $categories_meta_args[ 'sep' ] );

	$meta = '<span' . tux_get_attr( 'entry-categories' ) . '>' . $categories_meta_args[ 'before' ] . esc_html( $categories_meta_args[ 'label' ] ) . $category_list . $categories_meta_args[ 'after' ] . '</span>';

	/**
	 * Filters the post categories meta.
	 * Output from "[tux_post_categories]" shortcode.
	 *
	 * @since 1.0.0
	 *
	 * @param string $meta       Categories list meta HTML.
	 * @param array {
	 *     Arguments.
	 *
	 *     @type string $sep    Categories list separator. Default ", ".
	 *     @type string $before HTML before categories list. Default "".
	 *     @type string $after  HTML after categories list. Default "".
	 *     @type string $label  Label before categories list. Default "Posted in: ".
	 * }
	 * @param string $category_list Categories list links HTML.
	 */
	return apply_filters( 'tux_meta_post_categories', $meta, $categories_meta_args, $category_list );

}
add_shortcode( 'tux_post_categories', 'tux_meta_post_categories' );

/**
 * Return post tags meta.
 * Shortcode "[tux_post_tags]".
 *
 * @since 1.0.0
 *
 * @return string Tag meta output.
 */
function tux_meta_post_tags( $atts ) {

	/**
	 * Filters the post tags meta shortcode attributes under the
	 * "shortcode_atts_tux_post_tags" hook.
	 *
	 * @since 1.0.0
	 *
	 * @param array {
	 *     Arguments.
	 *
	 *     @type string $sep    Tags list separator. Default ", ".
	 *     @type string $before HTML before tags list. Default "".
	 *     @type string $after  HTML after tags list. Default "".
	 *     @type string $label  Label before tags list. Default "Posted in: ".
	 * }
	 */
	$tags_meta_args = shortcode_atts( array(

		'sep'    => ', ',
		'before' => '',
		'after'  => '',
		'label'  => __( 'Tagged: ', 'tuxedo' )

	), $atts, 'tux_post_tags' );

	$tags = get_the_tag_list( $tags_meta_args[ 'before' ] . esc_html( $tags_meta_args[ 'label' ] ), $tags_meta_args[ 'sep' ], $tags_meta_args[ 'after' ] );

	if ( ! $tags || is_wp_error( $tags ) ) return false;

	$meta = '<span' . tux_get_attr( 'entry-tags' ) . '>' . $tags . '</span>';

	/**
	 * Filters the post tags meta.
	 * Output from "[tux_post_tags]" shortcode.
	 *
	 * @since 1.0.0
	 *
	 * @param string $meta       Tags list meta HTML.
	 * @param array {
	 *     Arguments.
	 *
	 *     @type string $sep    Tags list separator. Default ", ".
	 *     @type string $before HTML before tags list. Default "".
	 *     @type string $after  HTML after tags list. Default "".
	 *     @type string $label  Label before tags list. Default "Posted in: ".
	 * }
	 * @param string $tags       Tags list links HTML.
	 */
	return apply_filters( 'tux_meta_post_tags', $meta, $tags_meta_args, $tags );

}
add_shortcode( 'tux_post_tags', 'tux_meta_post_tags' );

/**
 * Return post terms meta.
 * Shortcode "[tux_post_terms]".
 *
 * @since 1.0.0
 *
 * @return string Terms meta output.
 */
function tux_meta_post_terms( $atts ) {

	/**
	 * Filters the post terms meta shortcode attributes under the
	 * "shortcode_atts_tux_post_terms" hook.
	 *
	 * @since 1.0.0
	 *
	 * @param array {
	 *     Arguments.
	 *
	 *     @type string $sep      Terms list separator. Default ", ".
	 *     @type string $before   HTML before terms list. Default "".
	 *     @type string $after    HTML after terms list. Default "".
	 *     @type string $label    Label before terms list. Default "Tagged in: ".
	 *     @type string $taxonomy Taxonomy of terms to list. Default "category".
	 * }
	 */
	$terms_meta_args = shortcode_atts( array(

		'sep'      => ', ',
		'before'   => '',
		'after'    => '',
		'label'    => __( 'Tagged: ', 'tuxedo' ),
		'taxonomy' => 'category'

	), $atts, 'tux_post_terms' );

	$terms = get_the_term_list( get_the_ID(), $terms_meta_args[ 'taxonomy' ], $terms_meta_args[ 'before' ] . esc_html( $terms_meta_args[ 'label' ] ), $terms_meta_args[ 'sep' ], $terms_meta_args[ 'after' ] );

	if ( empty( $terms ) || is_wp_error( $terms ) ) return '';

	$meta = '<span' . tux_get_attr( 'entry-terms' ) . '>' . $terms . '</span>';

	/**
	 * Filters the post terms meta.
	 * Output from "[tux_post_terms]" shortcode.
	 *
	 * @since 1.0.0
	 *
	 * @param string $meta         Terms list meta HTML.
	 * @param array {
	 *     Arguments.
	 *
	 *     @type string $sep      Terms list separator. Default ", ".
	 *     @type string $before   HTML before terms list. Default "".
	 *     @type string $after    HTML after terms list. Default "".
	 *     @type string $label    Label before terms list. Default "Tagged in: ".
	 *     @type string $taxonomy Taxonomy of terms to list. Default "category".
	 * }
	 * @param string $terms        Terms list links HTML.
	 */
	return apply_filters( 'tux_meta_post_terms', $meta, $terms_meta_args, $terms );

}
add_shortcode( 'tux_post_terms', 'tux_meta_post_terms' );

