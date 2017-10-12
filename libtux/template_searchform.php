<?php
/**
 * Search form output templates
 *
 * @package ThemeTuxedo\Template
 * @since 1.0.0
 */

/**
 * Output search form.
 *
 * Attached to the "get_search_form" filter.
 *
 * @since 1.0.0
 */
function tux_search_form_output() {

	return  '<form' . tux_get_attr( 'search-form' ) . '>' .
			'<label>' .
			'<span class="screen-reader-text">' .

			/**
			 * Filter the label text for the text input on the search form.
			 *
			 * @since 1.0.0
			 *
			 * @param string $text Search label text. Default "Search for:".
			 */
			apply_filters( 'tux_search_label_text', __( 'Search for:', 'tuxedo' ) ) .

			'</span>' .
			'<input type="search" class="search-field" placeholder="' .

			/**
			 * Filter the placeholder for the text input on the search form.
			 *
			 * @since 1.0.0
			 *
			 * @param string $text Search form input placeholder text. Default "Search &hellip;".
			 */
			esc_attr( apply_filters( 'tux_search_placeholder_text', __( 'Search &hellip;', 'tuxedo' ) ) ) .

			'" value="' . get_search_query() . '" name="s" title="' .

			/**
			 * Filter the title attribute for the text input on the search form.
			 *
			 * @since 1.0.0
			 *
			 * @param string $text
			 */
			esc_attr( apply_filters( 'tux_search_title_text', __( 'Search for:', 'tuxedo' ) ) ) .

			'" />' .
			'</label>' .

			/**
			 * Filter to add controls after search input text box.
			 *
			 * @since 1.0.0
			 *
			 * @param string $text
			 */
			 apply_filters( 'tux_search_input_after', '' ) .
			'<input type="submit" class="search-submit" value="'.

			/**
			 * Filter the search form submit button text.
			 *
			 * @since 1.0.0
			 *
			 * @param string $text
			 */
			esc_attr( apply_filters( 'tux_search_button_text', __( 'Search', 'tuxedo' ) ) ) .

			'" />' .
			'</form>';

}
add_filter( 'get_search_form', 'tux_search_form_output' );
add_shortcode( 'tux_search_form', 'tux_search_form_output' );

