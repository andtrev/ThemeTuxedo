<?php
/**
 * Metabox manager
 *
 * @package ThemeTuxedo\AdminPage
 * @since 1.0.0
 */

/**
 * Tux AdminPage Panel Class.
 *
 * Handles panel options.
 *
 * @package ThemeTuxedo\AdminPage
 * @since 1.0.0
 */
class Tux_AdminPage_Panel {

	/**
	 * Tux_AdminPage_Manager instance.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @var Tux_AdminPage_Manager
	 */
	public $manager;

	/**
	 * Unique identifier, the slug name to refer to this admin page by.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @var string
	 */
	public $id;

	/**
	 * Unique identifier, slug of the admin page panel.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @var string
	 */
	public $parent_id = '';

	/**
	 * The text to be displayed in the title tags of the page when the menu is selected.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @var string
	 */
	public $title = '';

	/**
	 * The text to be used for the menu.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @var string
	 */
	public $menu_title = '';

	/**
	 * The text to be used for the sub menu of the top menu.
	 *
	 * When other sub menus are added to a top level menu, WordPress
	 * automatically adds the top level menu as the first sub menu.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @var string
	 */
	public $sub_menu_title = '';

	/**
	 * The icon for the menu.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @var string
	 */
	public $menu_icon = '';

	/**
	 * Tab ids and titles for the panel, HTML "id" attribute of the tab section.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @var array Key is tab id, value is title.
	 */
	public $tabs = array();

	/**
	 * The capability required for the menu to be displayed to the user.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @var string
	 */
	public $capability = 'manage_options';

	/**
	 * The position in the menu order this menu should appear.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @var integer
	 */
	public $priority = null;

	/**
	 * The action hook our content render callback is attached to.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @var string
	 */
	public $hook = '';

	/**
	 * Constructor.
	 *
	 * Any supplied $args override class property defaults.
	 *
	 * @since 3.4.0
	 *
	 * @param Tux_AdminPage_Manager $manager AdminPage bootstrap instance.
	 * @param string                $id      An specific ID of the panel.
	 * @param array                 $args    Panel arguments.
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

