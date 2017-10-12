<?php
/**
 * Options sanitization functions
 *
 * @package ThemeTuxedo\Options
 * @since 1.0.0
 */

/**
 * Sanitize checkbox.
 *
 * @since 1.0.0
 *
 * @param $value   Value to be sanitized.
 * @param $setting Setting object.
 * @return Sanitized value.
 */
function tux_sanitize_checkbox( $value, $setting = null ) {

	return intval( $value, 10 ) ? 1 : 0;

}

/**
 * Sanitize textbox.
 *
 * @since 1.0.0
 *
 * @param $value   Value to be sanitized.
 * @param $setting Setting object.
 * @return Sanitized value.
 */
function tux_sanitize_textbox( $value, $setting = null ) {

	return sanitize_text_field( $value );

}

/**
 * Sanitize integer.
 *
 * @since 1.0.0
 *
 * @param $value   Value to be sanitized.
 * @param $setting Setting object.
 * @return Sanitized value.
 */
function tux_sanitize_integer( $value, $setting = null ) {

	return intval( $value, 10 );

}

/**
 * Sanitize float.
 *
 * @since 1.0.0
 *
 * @param $value   Value to be sanitized.
 * @param $setting Setting object.
 * @return Sanitized value.
 */
function tux_sanitize_float( $value, $setting = null ) {

	return floatval( $value );

}

/**
 * Sanitize absolute number.
 *
 * @since 1.0.0
 *
 * @param $value   Value to be sanitized.
 * @param $setting Setting object.
 * @return Sanitized value.
 */
function tux_sanitize_absolute( $value, $setting = null ) {

	return abs( $value );

}

/**
 * Sanitize absolute number, non-zero.
 *
 * @since 1.0.0
 *
 * @param $value   Value to be sanitized.
 * @param $setting Setting object.
 * @return Sanitized value.
 */
function tux_sanitize_abs_nonzero( $value, $setting = null ) {

	return max( abs( $value ), 1 );

}

/**
 * Sanitize a url.
 *
 * @since 1.0.0
 *
 * @param $value   Value to be sanitized.
 * @param $setting Setting object.
 * @return Sanitized value.
 */
function tux_sanitize_url( $value, $setting = null ) {

	return esc_url_raw( $value );

}

