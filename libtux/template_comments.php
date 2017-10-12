<?php
/**
 * Comments output templates
 *
 * @package ThemeTuxedo\Template
 * @since 1.0.0
 */

/**
 * Output comments template at end of posts that support comments/trackbacks.
 *
 * Attached to the "tux_post_after" action.
 *
 * @package Theme_Tuxedo
 * @since 1.0.0
 */
function tux_comments_template() {

	global $post;

	if ( post_type_supports( $post->post_type, 'comments' ) || post_type_supports( $post->post_type, 'trackbacks' ) )
		comments_template( '', true );

}
add_action( 'tux_post_after', 'tux_comments_template' );

/**
 * Output comments structure.
 *
 * Attached to the "tux_comments" action.
 *
 * @since 1.0.0
 *
 */
function tux_comments_output() {

	global $post, $wp_query;

	if ( have_comments() && ! empty( $wp_query->comments_by_type[ 'comment' ] ) ) {

		echo '<div' . tux_get_attr( 'entry-comments' ) . '>';

		/**
		 * Filters the comments area title.
		 *
		 * @since 1.0.0
		 *
		 * @param string $title Comments area title. Default "<h3>Comments</h3>".
		 */
		echo '<h3' . tux_get_attr( 'comments-title' ) . '>' . apply_filters( 'tux_comments_title', __( 'Comments', 'tuxedo' ) ) . '</h3>';
		echo '<ol' . tux_get_attr( 'comment-list' ) . '>';

			/**
			 * Fires the comment list.
			 *
			 * @since 1.0.0
			 */
			do_action( 'tux_comments_list' );
		echo '</ol>';

		/**
		 * Filters the previous comments link text.
		 *
		 * @since 1.0.0
		 *
		 * @params string $text Empty string.
		 */
		$prev_link = get_previous_comments_link( apply_filters( 'tux_comments_prev_link_text', '' ) );

		/**
		 * Filters the next comments link text.
		 *
		 * @since 1.0.0
		 *
		 * @params string $text Empty string.
		 */
		$next_link = get_next_comments_link( apply_filters( 'tux_comments_next_link_text', '' ) );

		if ( $prev_link || $next_link ) {

			echo '<div' . tux_get_attr( 'comments-pagination' ) . '">';

			echo '<div' . tux_get_attr( 'comment-pagination-previous' ) . '>' . $prev_link . '</div>';
			echo '<div' . tux_get_attr( 'comment-pagination-next' ) . '">' . $next_link . '</div>';

			echo '</div>';

		}

		echo '</div>';

	/**
	 * Filters the text to display if there are no comments,
	 * and comments are open.
	 *
	 * @since 1.0.0
	 *
	 * @param string $text Empty string.
	 */
	} elseif ( 'open' === $post->comment_status && $no_comments_text = apply_filters( 'tux_comments_none_text', '' ) ) {

		echo '<div' . tux_get_attr( 'entry-comments' ) . '">' . $no_comments_text . '</div>';

	/**
	 * Filters the text to display if comments are closed.
	 *
	 * @since 1.0.0
	 *
	 * @param string $text Empty string.
	 */
	} elseif ( $comments_closed_text = apply_filters( 'tux_comments_closed_text', '' ) ) {

		echo '<div' . tux_get_attr( 'entry-comments' ) . '>' . $comments_closed_text . '</div>';

	}

}
add_action( 'tux_comments', 'tux_comments_output' );

/**
 * Output comment list.
 *
 * Attached to the "tux_comments_list" action.
 *
 * @since 1.0.0
 *
 */
function tux_list_comments() {

	/**
	 * Filters the "wp_list_comments" arguments for comments.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args {
	 *     Array of "wp_list_comments" arguments.
	 *
	 *     @type string   $type        Type of comments to display. Default "comment".
	 *     @type integer  $avatar_size Size that the avatar should be shown as, in pixels. Default "48".
	 *     @type string   $format      HTML format. Default "html5".
	 *     @type string   $style       Container tag style. Default "ol".
	 *     @type callback $callback    Custom function to use to open and display each comment. Default "tux_comment_output".
	 * }
	 */
	wp_list_comments( apply_filters( 'tux_comments_list_args', array(
		'type'        => 'comment',
		'avatar_size' => 48,
		'format'      => 'html5',
		'style'       => 'ol',
		'callback'    => 'tux_comment_output',
	) ) );

}
add_action( 'tux_comments_list', 'tux_list_comments' );

/**
 * Output a comment.
 *
 * Callback for "wp_list_comments" in "tux_list_comments".
 *
 * @since 1.0.0
 *
 * @param stdClass $comment Comment object.
 * @param array    $args    Comment arguments.
 * @param integer  $depth   Depth of current comment.
 */
function tux_comment_output( $comment, array $args, $depth ) {

	$GLOBALS[ 'comment' ] = $comment;

	echo '<li' . tux_get_attr( 'comment-li' ) . '>';
	echo '<article' . tux_get_attr( 'comment' ) . '>';

	/**
	 * Fires before a comment.
	 *
	 * Just after the opening "li" and "article" tags.
	 *
	 * @since 1.0.0
	 */
	do_action( 'tux_comment_before' );

	echo '<header' . tux_get_attr( 'comment-header' ) . '>';
	echo '<p' . tux_get_attr( 'comment-author' ) . '>';
	echo get_avatar( $comment, $args[ 'avatar_size' ] );

	$author = esc_html( get_comment_author() );
	$url    = esc_url( get_comment_author_url() );

	if ( ! empty( $url ) && $url !== 'http://' )
		$author = '<a href="' . $url . '"' . tux_get_attr( 'comment-author-link' ) . '>' . $author . '</a>';

	/**
	 * Filters the comment author "says" text.
	 *
	 * @since 1.0.0
	 *
	 * @param string $text Comment author says text. Default "says".
	 */
	echo '<span' . tux_get_attr( 'comment-author-title' ) . '>' . $author . '</span> <span' . tux_get_attr( 'comment-author-says' ) . '>' . apply_filters( 'tux_comment_author_says_text', __( 'says', 'tuxedo' ) ) . '</span>';

	echo '</p><p' . tux_get_attr( 'comment-meta' ) . '>';
	echo '<time' . tux_get_attr( 'comment-time' ) . '><a href="' . esc_url( get_comment_link( $comment->comment_ID ) ) . '"' . tux_get_attr( 'comment-time-link' ) . '>' .
		 esc_html( get_comment_date() ) . ' ' . __( 'at', 'tuxedo' ) . ' ' . esc_html( get_comment_time() ) . '</a></time>';
	edit_comment_link( __( '(Edit)', 'tuxedo' ), ' ' );
	echo '</p></header>';

	echo '<div' . tux_get_attr( 'comment-content' ) . '>';
	if ( ! $comment->comment_approved ) {

		/**
		 * Filter the comment awaiting moderation text.
		 *
		 * Text to display if the comment isn't approved yet.
		 *
		 * @since 1.0.0
		 *
		 * @param string $text Comment awaiting moderation text.
		 */
		echo '<p class="alert">' . apply_filters( 'tux_comment_awaiting_moderation', __( 'Your comment is awaiting moderation.', 'tuxedo' ) ) . '</p>';

	}
	comment_text();
	echo '</div>';

	comment_reply_link( array_merge( $args, array(
		'depth'  => $depth,
		'before' => '<div' . tux_get_attr( 'comment-reply' ) . '>',
		'after'  => '</div>',
	) ) );

	/**
	 * Fires after a comment.
	 *
	 * Just before closing "article" tag.
	 *
	 * @since 1.0.0
	 */
	do_action( 'tux_comment_after' );

	echo '</article>'; // WordPress will handle the closing "li" tag.

}

/**
 * Output pings structure.
 *
 * Attached to the "tux_pings" action.
 *
 * @since 1.0.0
 *
 */
function tux_pings_output() {

	global $wp_query;

	if ( have_comments() && ! empty( $wp_query->comments_by_type[ 'pings' ] ) ) {

		echo '<div' . tux_get_attr( 'entry-pings' ) . '>';

		/**
		 * Filters the pings area title.
		 *
		 * @since 1.0.0
		 *
		 * @param string $title Pings area title. Default "<h3>Trackbacks</h3>".
		 */
		echo apply_filters( 'tux_pings_title', __( '<h3>Trackbacks</h3>', 'tuxedo' ) );
		echo '<ol' . tux_get_attr( 'ping-list' ) . '>';

			/**
			 * Fires the ping list.
			 *
			 * @since 1.0.0
			 */
			do_action( 'tux_pings_list' );
		echo '</ol>';
		echo '</div>';

	} else {

		/**
		 * Filters the text to display if there are no pings.
		 *
		 * @since 1.0.0
		 *
		 * @param string $text Empty string.
		 */
		echo apply_filters( 'tux_pings_none_text', '' );

	}

}
add_action( 'tux_pings', 'tux_pings_output' );

/**
 * Output ping list.
 *
 * Attached to the "tux_pings_list" action.
 *
 * @since 1.0.0
 */
function tux_list_pings() {

	/**
	 * Filters the "wp_list_comments" arguments for pings.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args {
	 *     Array of "wp_list_comments" arguments.
	 *
	 *     @type string $type  Type of comments to display. Default "pings".
	 *     @type string $style Container tag style. Default "ol".
	 * }
	 */
	wp_list_comments( apply_filters( 'tux_pings_list_args', array(
		'type'  => 'pings',
		'style' => 'ol',
	) ) );

}

/**
 * Output comment form.
 *
 * Attached to the "tux_comment_form" action.
 *
 * @since 1.0.0
 */
function tux_comment_form_output() {

	if ( comments_open() ) comment_form( array( 'format' => 'html5' ) );

}
add_action( 'tux_comment_form', 'tux_comment_form_output' );

