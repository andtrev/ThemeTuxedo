<?php
/**
 * AdminPage section
 *
 * @package ThemeTuxedo\AdminPage
 * @since 1.0.0
 */

/**
 * Tux AdminPage Section Class.
 *
 * Handles section options.
 *
 * @package ThemeTuxedo\AdminPage
 * @since 1.0.0
 */
class Tux_AdminPage_Section {

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
	 * Unique identifier, HTML "id" attribute of the panel section.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @var string
	 */
	public $id;

	/**
	 * Priority (order) for the section.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @var int
	 */
	public $priority = 10;

	/**
	 * Title of the panel section, visible to user.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @var string
	 */
	public $title = '';

	/**
	 * The panel id where the section should be shown.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @var string
	 */
	public $panel = '';

	/**
	 * The panel tab id where the section should be shown.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @var string
	 */
	public $tab = '';

	/**
	 * Description to show at the top of the section.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @var string
	 */
	public $description = '';

	/**
	 * Render the section in a post metabox.
	 *
	 * Doesn't actually turn off post metabox HTML/CSS,
	 * only makes the background and border color transparent.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @var boolean
	 */
	public $box = true;

	/**
	 * Maximum width of the section boxe.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @var string
	 */
	public $max_width = '690px';

	/**
	 * Constructor.
	 *
	 * Any supplied $args override class property defaults.
	 *
	 * @since 1.0.0
	 *
	 * @param Tux_AdminPage_Manager  $manager AdminPage bootstrap instance.
	 * @param string                 $id      An specific ID of the section.
	 * @param array                  $args    Section arguments.
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

		if ( $this->tab ) {
			add_action( "tux_ap_render_sections_{$this->tab}", array( $this, 'render' ), $this->priority );
		} else {
			add_action( "tux_ap_render_sections_{$this->panel}", array( $this, 'render' ), $this->priority );
		}

	}

	/**
	 * Render the section container.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function render() {

		$postbox_style = '';
		if ( ! $this->box || $this->max_width ) {
			$postbox_style = ' style="';
			if ( ! $this->box ) $postbox_style .= 'background:transparent;border-color:transparent;box-shadow:none;-webkit-box-shadow:none;';
			if ( $this->max_width ) $postbox_style .= 'max-width:' . $this->max_width . ';';
			$postbox_style .= '"';
		}
		echo '<div class="postbox"' . $postbox_style . '>';
		if ( $this->title  ) echo '<h3 class="hndle" style="cursor:auto;"><span>' . $this->title . '</span></h3>';
		echo '<div class="inside">';

		if ( $this->description ) echo '<p>' . $this->description . '</p>';

		do_action( "tux_ap_render_controls_{$this->id}" );

		echo '<div style="display:table;clear:both;"></div></div></div>';

	}

}

