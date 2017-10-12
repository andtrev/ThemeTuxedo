<?php
/**
 * Metabox settings
 *
 * @package ThemeTuxedo\Metabox
 * @since 1.0.0
 */

/**
 * Tux Metabox Setting Class.
 *
 * Handles saving and sanitizing of settings.
 *
 * @package ThemeTuxedo\Metabox
 * @since 1.0.0
 */
class Tux_Metabox_Setting {

	/**
	 * Tux_Metabox_Manager instance.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @var Tux_Metabox_Manager
	 */
	public $manager;

	/**
	 * Unique identifier.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @var string
	 */
	public $id;

	/**
	 * Default setting.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @var string
	 */
	public $default = '';

	/**
	 * User capability level required to save.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @var string
	 */
	public $capability = 'edit_theme_options';

	/**
	 * Server-side sanitization callback for the setting's value.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @var callback
	 */
	public $sanitize_callback = '';

	/**
	 * Holds id data for base and array keys.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public $id_data = array();

	/**
	 * Setting value.
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 * @var mixed
	 */
	private $_value;

	/**
	 * Constructor.
	 *
	 * Any supplied $args override class property defaults.
	 *
	 * @since 1.0.0
	 *
	 * @param Tux_Metabox_Manager $manager Metabox bootstrap instance.
	 * @param string              $id      An specific ID of the setting.
	 * @param array               $args    Setting arguments.
	 */
	public function __construct( $manager, $id, $args = array() ) {

		$keys = array_keys( get_object_vars( $this ) );

		foreach ( $keys as $key ) {
			if ( isset( $args[ $key ] ) )
				$this->$key = $args[ $key ];
		}

		$this->manager = $manager;
		$this->id = $id;

		// Parse the ID for array keys.
		$this->id_data[ 'keys' ] = preg_split( '/\[/', str_replace( ']', '', $this->id ) );
		$this->id_data[ 'base' ] = array_shift( $this->id_data[ 'keys' ] );

		// Rebuild the ID.
		$this->id = $this->id_data[ 'base' ];
		if ( ! empty( $this->id_data[ 'keys' ] ) )
			$this->id .= '[' . implode( '][', $this->id_data[ 'keys' ] ) . ']';

		if ( $this->sanitize_callback )
			add_filter( "tux_mb_sanitize_{$this->id}", $this->sanitize_callback, 10, 2 );

	}

	/**
	 * Fetch the value of the setting.
	 *
	 * @since 1.0.0
	 *
	 * @return mixed The value.
	 */
	public function value() {

		if ( isset( $this->_value ) ) return $this->_value;

		return $this->default;

	}

	/**
	 * Set the value of the setting.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $value The value to set.
	 */
	public function set_value( $value ) {

		$value = wp_unslash( $value );

		/**
		 * Filter a Tux metabox setting value in un-slashed form for sanitization.
		 *
		 * @since 1.0.0
		 *
		 * @param mixed               $value Value of the setting.
		 * @param Tux_Metabox_Setting $this  Tux_Metabox_Setting instance.
		 */
		$this->_value = apply_filters( "tux_mb_sanitize_{$this->id}", $value, $this );

	}

}

