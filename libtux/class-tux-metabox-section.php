<?php
/**
 * Metabox section
 *
 * @package ThemeTuxedo\Metabox
 * @since 1.0.0
 */

/**
 * Tux Metabox Section Class.
 *
 * Handles section options.
 *
 * @package ThemeTuxedo\Metabox
 * @since 1.0.0
 */
class Tux_Metabox_Section {

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
	 * Tab ids and titles for the section, HTML "id" attribute of the tab section.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @var array Key is tab id, value is title.
	 */
	public $tabs = array();

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
	 * Description to show at the top of the section.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @var string
	 */
	public $description = '';

	/**
	 * Constructor.
	 *
	 * Any supplied $args override class property defaults.
	 *
	 * @since 1.0.0
	 *
	 * @param Tux_Metabox_Manager $manager Metabox bootstrap instance.
	 * @param string              $id      An specific ID of the section.
	 * @param array               $args    Section arguments.
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

		add_action( "tux_mb_render_sections_{$this->panel}", array( $this, 'render' ), $this->priority );

	}

	/**
	 * Render the section container.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function render() {

		if ( $this->title ) echo '<span class="tux-section-title">' . $this->title . '</span>';
		if ( $this->description ) echo '<br><span class="tux-section-description">' . $this->description . '</span>';

		do_action( "tux_mb_render_controls_{$this->id}" );

		if ( ! empty( $this->tabs ) ) {

			echo '<ul class="category-tabs">';
			foreach( $this->tabs as $tab_id => $tab_title ) echo '<li class="tabs"><a href="#' . $tab_id . '">' . $tab_title . '</a></li>';
			echo '</ul>';

			foreach( $this->tabs as $tab_id => $tab_title ) {

				do_action( "tux_mb_render_controls_{$tab_id}" );

			}

		}

	}

}

