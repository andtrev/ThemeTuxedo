<?php
/**
 * Metabox panel
 *
 * @package ThemeTuxedo\Metabox
 * @since 1.0.0
 */

/**
 * Tux Metabox Panel Class.
 *
 * Handles panel options.
 *
 * @package ThemeTuxedo\Metabox
 * @since 1.0.0
 */
class Tux_Metabox_Panel {

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
	 * Unique identifier, HTML "id" attribute of the edit screen panel.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @var string
	 */
	public $id;

	/**
	 * Title of the edit screen panel, visible to user.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @var string
	 */
	public $title = '';

	/**
	 * The part of the page where the edit screen panel should be shown.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @var string Default "advanced". Accepts "normal", "advanced", or "side".
	 */
	public $context = 'advanced';

	/**
	 * The priority within the context where the panels should show.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @var string Default "default". Accepts "high", "core", "default" or "low".
	 */
	public $priority = 'default';

	/**
	 * Constructor.
	 *
	 * Any supplied $args override class property defaults.
	 *
	 * @since 1.0.0
	 *
	 * @param Tux_Metabox_Manager $manager Metabox bootstrap instance.
	 * @param string              $id      An specific ID of the panel.
	 * @param array               $args    Panel arguments.
	 */
	public function __construct( $manager, $id, $args = array() ) {

		$keys = array_keys( get_object_vars( $this ) );

		foreach ( $keys as $key ) {
			if ( isset( $args[ $key ] ) ) {
				$this->$key = $args[ $key ];
			}
		}

		$this->manager = $manager;
		$this->id = $id;

	}

}

