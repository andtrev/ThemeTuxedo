<?php
/**
 * AdminPage manager
 *
 * @package ThemeTuxedo\AdminPage
 * @since 1.0.0
 */

/**
 * Instantiates the Tux_AdminPage_Manager class.
 *
 * Attached to the "_admin_menu" action.
 *
 * @since 1.0.0
 */
function tux_instantiate_adminpage_manager() {

	new Tux_AdminPage_Manager();

}
add_action( '_admin_menu', 'tux_instantiate_adminpage_manager' );

/**
 * Tux AdminPage Manager Class.
 *
 * Manages adminpage settings, controls, panels and sections.
 *
 * @package ThemeTuxedo\AdminPage
 * @since 1.0.0
 */
final class Tux_AdminPage_Manager {

	/**
	 * Array of setting objects.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @var array Tux_AdminPage_Setting
	 */
	public $settings = array();

	/**
	 * Array of section objects.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @var array Tux_AdminPage_Section
	 */
	public $sections = array();

	/**
	 * Array of panel objects.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @var array Tux_AdminPage_Panel
	 */
	public $panels = array();

	/**
	 * Array of control objects.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @var array Tux_Input_Control
	 */
	public $controls = array();

	/**
	 * Array of help tabs arguments.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @var array
	 */
	public $help_tabs = array();

	/**
	 * Whether nonce field has been output.
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 * @var boolean
	 */
	private $_nonce = false;

	/**
	 * Hook key for input controls.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @var string
	 */
	public $hook_key = 'ap';

	/**
	 * Contructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		require_once( TUX_LIB_DIR . 'class-tux-adminpage-setting.php' );
		require_once( TUX_LIB_DIR . 'class-tux-adminpage-section.php' );
		require_once( TUX_LIB_DIR . 'class-tux-adminpage-panel.php' );
		require_once( TUX_LIB_DIR . 'class-tux-input-control.php' );

		/**
		 * Fires once WordPress has loaded, allowing admin page controls to be initialized.
		 *
		 * @since 1.0.0
		 *
		 * @param Tux_AdminPage_Manager $this Tux_AdminPage_Manager instance.
		 */
		do_action( 'tux_adminpage_register', $this );

		add_action( 'admin_menu', array( $this, 'add_admin_pages' ) );

	}

	/**
	 * Render admin page controls.
	 *
	 * @since 1.0.0
	 */
	public function render_admin_page_contents() {

		if ( ! $panel_id = $this->current_panel() ) return;

		$this->load_setting_values();
		if ( isset( $_POST[ 'tuxoptionsdata' ] ) ) {
			$this->save();
			echo '<div class="updated"><p><strong>' . __( 'Settings saved.', 'tuxedo' ) . '</strong></p></div>';
		}

		echo '<div class="wrap tux-options tux-adminpage">';

		if ( $this->panels[ $panel_id ]->title )
			echo '<h2>' . $this->panels[ $panel_id ]->title . '</h2>';

		if ( $tab_id = $this->current_tab() ) {

			echo '<h2 class="nav-tab-wrapper">';

			foreach( $this->panels[ $panel_id ]->tabs as $id => $title )
			    echo '<a class="nav-tab' . ( ( $id == $tab_id ) ? ' nav-tab-active' : '' ) . '" href="?page=' . esc_attr( $panel_id ) . '&tab=' . $id . '">' . $title . '</a>';

			echo '</h2>';

			echo '<div id="poststuff">';
			echo '<form method="post" action="?page=' . esc_attr( $panel_id ) . '&tab=' . $tab_id . '">';
			wp_nonce_field( 'tux_adminpage_save', 'tux_adminpage_nonce' );

			do_action( "tux_ap_render_sections_{$tab_id}" );

		} else {

			echo '<div id="poststuff">';
			echo '<form method="post" action="?page=' . esc_attr( $panel_id ) . '">';
			wp_nonce_field( 'tux_adminpage_save', 'tux_adminpage_nonce' );

			do_action( "tux_ap_render_sections_{$panel_id}" );

		}

		if ( count( $this->settings ) > 0 ) echo '<div style="padding:0 0 0 12px;">' . get_submit_button() . '</div>';

		echo '</form></div></div>';

	}

	/**
	 * Add admin pages.
	 *
	 * @since 1.0.0
	 */
	public function add_admin_pages() {

		foreach ( $this->panels as $panel ) {
			if ( $panel->parent_id ) {
				$panel->hook = add_submenu_page( $panel->parent_id, $panel->title, $panel->menu_title, $panel->capability, $panel->id, array( $this, 'render_admin_page_contents' ) );
			}
			else {
				$panel->hook = add_menu_page( $panel->title, $panel->menu_title, $panel->capability, $panel->id, array( $this, 'render_admin_page_contents' ), $panel->menu_icon, $panel->priority );
				if ( $panel->sub_menu_title )
					$panel->hook = add_submenu_page( $panel->id, $panel->title, $panel->sub_menu_title, $panel->capability, $panel->id, array( $this, 'render_admin_page_contents' ) );
			}
			if ( $panel->hook && $panel->id == $this->current_panel() ) {
				add_action( 'load-' . $panel->hook, array( $this, 'render_help_tabs' ) );
				add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_control_scripts' ) );
			}
		}

	}

	/**
	 * Add a admin page setting.
	 *
	 * @since 1.0.0
	 *
	 * @param Tux_AdminPage_Setting|string $id AdminPage Setting object, or ID.
	 * @param array $args                      Setting arguments; passed to Tux_AdminPage_Setting
	 *                                         constructor.
	 */
	public function add_setting( $id, $args = array() ) {

		if ( ! $this->current_panel() ) return;

		if ( is_a( $id, 'Tux_AdminPage_Setting' ) )
			$setting = $id;
		else
			$setting = new Tux_AdminPage_Setting( $this, $id, $args );

		if ( $setting->section && isset( $this->sections[ $setting->section ] ) ) {
			 $this->settings[ $setting->id ] = $setting;
		}

	}

	/**
	 * Remove a admin page setting.
	 *
	 * @since 1.0.0
	 *
	 * @param string $id AdminPage setting id.
	 */
	public function remove_setting( $id ) {

		unset( $this->settings[ $id ] );

	}

	/**
	 * Add a admin page section.
	 *
	 * @since 1.0.0
	 *
	 * @param Tux_AdminPage_Section|string $id AdminPage Section object, or ID.
	 * @param array $args                      Section arguments; passed to Tux_AdminPage_Section
	 *                                         constructor.
	 */
	public function add_section( $id, $args = array() ) {

		if ( ! $this->current_panel() ) return;

		if ( is_a( $id, 'Tux_AdminPage_Section' ) )
			$section = $id;
		else
			$section = new Tux_AdminPage_Section( $this, $id, $args );

		if ( $section->tab ) {
			if ( $section->tab == $this->current_tab() ) $this->sections[ $section->id ] = $section;
		} elseif ( $section->panel ) {
			if ( $section->panel == $this->current_panel() ) $this->sections[ $section->id ] = $section;
		}

	}

	/**
	 * Remove a admin page section.
	 *
	 * @since 1.0.0
	 *
	 * @param string $id AdminPage section id.
	 */
	public function remove_section( $id ) {

		unset( $this->sections[ $id ] );

	}

	/**
	 * Add a admin page panel.
	 *
	 * @since 1.0.0
	 *
	 * @param Tux_AdminPage_Panel|string $id AdminPage Panel object, or ID.
	 * @param array $args                    Panel arguments; passed to Tux_AdminPage_Panel
	 *                                       constructor.
	 */
	public function add_panel( $id, $args = array() ) {

		if ( is_a( $id, 'Tux_AdminPage_Panel' ) )
			$panel = $id;
		else
			$panel = new Tux_AdminPage_Panel( $this, $id, $args );

			$this->panels[ $panel->id ] = $panel;

	}

	/**
	 * Remove a admin page panel.
	 *
	 * @since 1.0.0
	 *
	 * @param string $id AdminPage panel id.
	 */
	public function remove_panel( $id ) {

		unset( $this->panels[ $id ] );

	}

	/**
	 * Add a admin page control.
	 *
	 * @since 1.0.0
	 *
	 * @param Tux_Input_Control|string $id AdminPage Control object, or ID.
	 * @param array $args                  Control arguments; passed to Tux_Input_Controls
	 *                                     constructor.
	 */
	public function add_control( $id, $args = array() ) {

		if ( ! $this->current_panel() ) return;

		if ( is_a( $id, 'Tux_Input_Control' ) )
			$control = $id;
		else
			$control = new Tux_Input_Control( $this, $id, $args );

		if ( $control->section && isset( $this->sections[ $control->section ] ) ) {
			$this->controls[ $control->id ] = $control;
		}

	}

	/**
	 * Remove a admin page control.
	 *
	 * @since 1.0.0
	 *
	 * @param string $id AdminPage control id.
	 */
	public function remove_control( $id ) {

		unset( $this->controls[ $id ] );

	}

	/**
	 * Add a help tab section to the Contextual Help menu in an admin page.
	 *
	 * @since 1.0.0
	 *
	 * @param string $id Unique id for the tab.
	 * @param array $args {
	 *     Contains values to be displayed by the created help tab and a callback argument.
	 *
	 *     @type string   $panel    Panel id to display the help tab on.
	 *     @type string   $tab      Tab id to display the help tab on.
	 *     @type string   $title    Title for the tab.
	 *     @type string   $content  Optional. Help tab content in plain text or HTML.
	 *     @type callback $callback Optional. The function to be called to output the content for this page.
	 * }
	 */
	public function add_help_tab( $id, $args = array() ) {

		if ( isset( $args[ 'panel' ] ) && $args[ 'panel' ] != $this->current_panel() ) return;
		if ( isset( $args[ 'tab' ] ) && $args[ 'tab' ] != $this->current_tab() ) return;

		$this->help_tabs[ $id ] = wp_parse_args( $args, array(
			'id'       => $id,
			'title'    => $id,
			'content'  => '',
			'callback' => '',
		) );

	}

	/**
	 * Remove a admin page help tab.
	 *
	 * @since 1.0.0
	 *
	 * @param string $id AdminPage help tab id.
	 */
	public function remove_help_tab( $id ) {

		unset( $this->help_tabs[ $id ] );

	}

	/**
	 * Render help tabs.
	 *
	 * Attached to the "load-(page)" action for current admin page.
	 *
	 * @since 1.0.0
	 */
	public function render_help_tabs() {

		$screen = get_current_screen();

		foreach ( $this->help_tabs as $help_tab ) $screen->add_help_tab( $help_tab );

	}

	/**
	 * Get the current panel id.
	 *
	 * @since 1.0.0
	 *
	 * @return string The panel id.
	 */
	public function current_panel() {

		static $panel_id = '';
		if ( $panel_id ) return $panel_id;

		if ( isset( $_REQUEST[ 'page' ] ) ) $panel_id = $_REQUEST[ 'page' ];

		return $panel_id;

	}

	/**
	 * Get the current tab id.
	 *
	 * @since 1.0.0
	 *
	 * @return string The tab id.
	 */
	public function current_tab() {

		static $tab_id = '';
		if ( $tab_id ) return $tab_id;

		if ( ! $panel_id = $this->current_panel() ) return '';

		if ( ! empty( $this->panels[ $panel_id ]->tabs ) ) {

			if ( isset( $_REQUEST[ 'tab' ] ) ) $tab_id = $_REQUEST[ 'tab' ];

			if ( empty( $tab_id ) || ! array_key_exists( $tab_id, $this->panels[ $panel_id ]->tabs ) ) {

					reset( $this->panels[ $panel_id ]->tabs );
					$tab_id = key( $this->panels[ $panel_id ]->tabs );

			}

		}

		return $tab_id;

	}

	/**
	 * Save the options.
	 *
	 * @since 1.0.0
	 */
	public function save() {

		if ( empty( $this->settings ) || ! check_admin_referer( 'tux_adminpage_save', 'tux_adminpage_nonce' ) ) return;

		if ( ! $this->current_panel() ) return;

		$tux_option_data = null;
		$bases = array();

		foreach ( $this->settings as $setting ) {
			if ( ! in_array( $setting->id_data[ 'base' ], $bases ) ) $bases[] = $setting->id_data[ 'base' ];
		}

		if ( empty( $bases ) ) return;

		foreach ( $bases as $base ) {

			$option_value = null;

			foreach( $this->settings as $setting ) {

				if ( $setting->id_data[ 'base' ] == $base ) {
					if ( ! empty( $setting->id_data[ 'keys' ] ) ) {

						if ( ! is_array( $option_value ) ) $option_value = array();
						$option_ref = &$option_value;

						foreach( $setting->id_data[ 'keys' ] as $key ) {

							if ( ! array_key_exists( $key, $option_ref ) ) $option_ref[ $key ] = array();
							$option_ref = &$option_ref[ $key ];

						}

						$option_ref = $setting->value();

					} else {
						$option_value = $setting->value();
					}

				}

			}

			if ( $option_value !== null ) {
				if ( is_array( $option_value ) ) {
					$old_option_value = get_option( $base, 'idontexist' );
					if ( $old_option_value !== 'idontexist' && is_array( $old_option_value ) ) {
						$option_value = array_merge( $old_option_value, $option_value );
					}
				}
				if ( $setting->capability && current_user_can( $setting->capability ) )
					update_option( $base, $option_value );
			}

		}

		/**
		 * Fires once after an admin page save.
		 *
		 * @since 1.0.0
		 *
		 * @param string $id Panel or tab id.
		 */
		do_action( 'tux_adminpage_save', ( $this->current_tab() ? $this->current_tab() : $this->current_panel() ) );

	}

	/**
	 * Load setting values.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function load_setting_values() {

		if ( empty( $this->settings ) ) return;

		if ( isset( $_POST[ 'tuxoptionsdata' ] ) ) {

			$tux_option_data = $_POST[ 'tuxoptionsdata' ];

		} else {

			$tux_option_data = array();

		}

		$setting_keys = array_keys( $this->settings );
		foreach ( $setting_keys as $setting_key ) {

			$option_data = null;

			if ( array_key_exists( $this->settings[ $setting_key ]->id_data[ 'base' ], $tux_option_data ) ) {

				$option_data = $tux_option_data[ $this->settings[ $setting_key ]->id_data[ 'base' ] ];
				if ( ! empty( $this->settings[ $setting_key ]->id_data[ 'keys' ] ) ) {
					foreach ( $this->settings[ $setting_key ]->id_data[ 'keys' ] as $key ) {
						if ( ! array_key_exists( $key, $option_data ) ) {
							$option_data = null;
							break;
						}
						$option_data = $option_data[ $key ];
					}
				}

			} else {

				$option_data_db = get_option( $this->settings[ $setting_key ]->id_data[ 'base' ], $this->settings[ $setting_key ]->default );
				$option_data = $option_data_db;
				if ( is_array( $option_data ) ) {
					if ( ! empty( $this->settings[ $setting_key ]->id_data[ 'keys' ] ) ) {
						foreach ( $this->settings[ $setting_key ]->id_data[ 'keys' ] as $key ) {
							if ( ! array_key_exists( $key, $option_data ) ) {
								$option_data = null;
								break;
							}
							$option_data = $option_data[ $key ];
						}
					}
				}

			}

			if ( $option_data !== null ) {
				$this->settings[ $setting_key ]->set_value( $option_data );
			} else {
				$this->settings[ $setting_key ]->set_value( $this->settings[ $setting_key ]->default );
			}

			unset( $option_data );

		}

	}

	/**
	 * Control scripts enqueue.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_control_scripts() {

		foreach ( $this->controls as $control ) {
			$control->enqueue();
		}

	}

}

