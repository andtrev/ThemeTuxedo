<?php
/**
 * Metabox manager
 *
 * @package ThemeTuxedo\Metabox
 * @since 1.0.0
 */

/**
 * Instantiates the Tux_Metabox_Manager class.
 *
 * Attached to the "load-post.php" and "load-post-new.php" hooks.
 *
 * @since 1.0.0
 */
function tux_instantiate_metabox_manager() {

	new Tux_Metabox_Manager();

}
add_action( 'load-post.php', 'tux_instantiate_metabox_manager' );
add_action( 'load-post-new.php', 'tux_instantiate_metabox_manager' );

/**
 * Tux Metabox Manager Class.
 *
 * Manages metabox settings, controls, panels and sections.
 *
 * @package ThemeTuxedo\Metabox
 * @since 1.0.0
 */
final class Tux_Metabox_Manager {

	/**
	 * Array of setting objects.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @var array Tux_Metabox_Setting
	 */
	public $settings = array();

	/**
	 * Array of section objects.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @var array Tux_Metabox_Section
	 */
	public $sections = array();

	/**
	 * Array of panel objects.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @var array Tux_Metabox_Panel
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
	 * Current post's post type.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @var string
	 */
	public $post_type;

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
	 * Hook key.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @var string
	 */
	public $hook_key = 'mb';

	/**
	 * Contructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		global $typenow;
		$this->post_type = $typenow;

		require_once( plugin_dir_path( __FILE__ ) . 'class-tux-metabox-setting.php' );
		require_once( plugin_dir_path( __FILE__ ) . 'class-tux-metabox-section.php' );
		require_once( plugin_dir_path( __FILE__ ) . 'class-tux-metabox-panel.php' );
		require_once( plugin_dir_path( __FILE__ ) . 'class-tux-input-control.php' );

		/**
		 * Fires once WordPress has loaded, allowing metabox controls to be initialized.
		 *
		 * @since 1.0.0
		 *
		 * @param Tux_Metabox_Manager $this Tux_Metabox_Manager instance.
		 */
		do_action( 'tux_metabox_register', $this );

		/**
		 * Fires once WordPress has loaded for a specific post type,
		 * allowing metabox controls to be intialized.
		 *
		 * @since 1.0.0
		 *
		 * @param Tux_Metabox_Manager $this Tux_Metabox_Manager instance.
		 */
		do_action( "tux_metabox_register_{$this->post_type}", $this );

		$this->render_help_tabs();

		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_control_scripts' ) );

		add_action( 'add_meta_boxes', array( $this, 'load_setting_values' ) );
		add_action( 'save_post', array( $this, 'load_setting_values' ) );

		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_action( 'save_post', array( $this, 'save' ) );

	}

	/**
	 * Add metabox containers.
	 *
	 * @since 1.0.0
	 */
	public function add_meta_boxes( $post_type ) {

		foreach ( $this->panels as $panel ) {
			add_meta_box(
			$panel->id,
			$panel->title,
			array( $this, 'render_meta_box_contents' ),
			$post_type,
			$panel->context,
			$panel->priority
			);
		}

	}

	/**
	 * Render metabox controls.
	 *
	 * @since 1.0.0
	 */
	public function render_meta_box_contents( $post, $metabox ) {

		echo '<div class="tux-options tux-metabox">';

		if ( ! $this->_nonce ) {

			wp_nonce_field( 'tux_metabox_save', 'tux_metabox_nonce' );
			$this->_nonce = true;

		}

		do_action( "tux_mb_render_sections_{$metabox[ 'id' ]}" );

		echo '</div>';

	}

	/**
	 * Save the meta.
	 *
	 * @since 1.0.0
	 */
	public function save( $post_id ) {

		if ( wp_is_post_revision( $post_id ) ) return $post_id;

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return $post_id;

		if ( empty( $this->settings ) || ! isset( $_POST[ 'tux_metabox_nonce' ] ) || ! check_admin_referer( 'tux_metabox_save', 'tux_metabox_nonce' ) ) return $post_id;

		$tux_meta_data = null;
		$bases = array();

		foreach ( $this->settings as $setting ) {
			if ( ! in_array( $setting->id_data[ 'base' ], $bases ) ) $bases[] = $setting->id_data[ 'base' ];
		}

		if ( empty( $bases ) ) return;

		foreach ( $bases as $base ) {

			$meta_value = null;

			foreach( $this->settings as $setting ) {

				if ( $setting->id_data[ 'base' ] == $base ) {
					if ( ! empty( $setting->id_data[ 'keys' ] ) ) {

						if ( ! is_array( $meta_value ) ) $meta_value = array();
						$meta_ref = &$meta_value;

						foreach( $setting->id_data[ 'keys' ] as $key ) {

							if ( ! array_key_exists( $key, $meta_ref ) ) $meta_ref[ $key ] = array();
								$meta_ref = &$meta_ref[ $key ];

						}

						$meta_ref = $setting->value();

					} else {
						$meta_value = $setting->value();
					}

				}

			}

			if ( $meta_value !== null && $setting->capability && current_user_can( $setting->capability ) ) update_post_meta( $post_id, $base, $meta_value );

		}

	}

	/**
	 * Add a metabox setting.
	 *
	 * @since 1.0.0
	 *
	 * @param Tux_Metabox_Setting|string $id Metabox Setting object, or ID.
	 * @param array $args                    Setting arguments; passed to Tux_Metabox_Setting
	 *                                       constructor.
	 */
	public function add_setting( $id, $args = array() ) {

		if ( is_a( $id, 'Tux_Metabox_Setting' ) )
			$setting = $id;
		else
			$setting = new Tux_Metabox_Setting( $this, $id, $args );

		$this->settings[ $setting->id ] = $setting;

	}

	/**
	 * Remove a metabox setting.
	 *
	 * @since 1.0.0
	 *
	 * @param string $id Metabox setting id.
	 */
	public function remove_setting( $id ) {

		unset( $this->settings[ $id ] );

	}

	/**
	 * Add a metabox section.
	 *
	 * @since 1.0.0
	 *
	 * @param Tux_Metabox_Section|string $id Metabox Section object, or ID.
	 * @param array $args                    Section arguments; passed to Tux_Metabox_Section
	 *                                       constructor.
	 */
	public function add_section( $id, $args = array() ) {

		if ( is_a( $id, 'Tux_Metabox_Section' ) )
			$section = $id;
		else
			$section = new Tux_Metabox_Section( $this, $id, $args );

		$this->sections[ $section->id ] = $section;

	}

	/**
	 * Remove a metabox section.
	 *
	 * @since 1.0.0
	 *
	 * @param string $id Metabox section id.
	 */
	public function remove_section( $id ) {

		unset( $this->sections[ $id ] );

	}

	/**
	 * Add a metabox panel.
	 *
	 * @since 1.0.0
	 *
	 * @param Tux_Metabox_Panel|string $id Metabox Panel object, or ID.
	 * @param array $args                  Panel arguments; passed to Tux_Metabox_Panel
	 *                                     constructor.
	 */
	public function add_panel( $id, $args = array() ) {

		if ( is_a( $id, 'Tux_Metabox_Panel' ) )
			$panel = $id;
		else
			$panel = new Tux_Metabox_Panel( $this, $id, $args );

		$this->panels[ $panel->id ] = $panel;

	}

	/**
	 * Remove a metabox panel.
	 *
	 * @since 1.0.0
	 *
	 * @param string $id Metabox panel id.
	 */
	public function remove_panel( $id ) {

		unset( $this->panels[ $id ] );

	}

	/**
	 * Add a metabox control.
	 *
	 * @since 1.0.0
	 *
	 * @param Tux_Metabox_Control|string $id Metabox Control object, or ID.
	 * @param array $args                    Control arguments; passed to Tux_Input_Controls
	 *                                       constructor.
	 */
	public function add_control( $id, $args = array() ) {

		if ( is_a( $id, 'Tux_Input_Control' ) )
			$control = $id;
		else
			$control = new Tux_Input_Control( $this, $id, $args );

		$this->controls[ $control->id ] = $control;

	}

	/**
	 * Remove a metabox control.
	 *
	 * @since 1.0.0
	 *
	 * @param string $id Metabox control id.
	 */
	public function remove_control( $id ) {

		unset( $this->controls[ $id ] );

	}

	/**
	 * Add a help tab section to the Contextual Help menu in an edit post page.
	 *
	 * @since 1.0.0
	 *
	 * @param string $id Unique id for the tab.
	 * @param array $args {
	 *     Contains values to be displayed by the created help tab and a callback argument.
	 *
	 *     @type string   $title    Title for the tab.
	 *     @type string   $content  Optional. Help tab content in plain text or HTML.
	 *     @type callback $callback Optional. The function to be called to output the content for this page.
	 * }
	 */
	public function add_help_tab( $id, $args = array() ) {

		$this->help_tabs[ $id ] = wp_parse_args( $args, array(
			'id'       => $id,
			'title'    => $id,
			'content'  => '',
			'callback' => '',
		) );

	}

	/**
	 * Remove a metabox help tab.
	 *
	 * @since 1.0.0
	 *
	 * @param string $id Metabox help tab id.
	 */
	public function remove_help_tab( $id ) {

		unset( $this->help_tabs[ $id ] );

	}

	/**
	 * Render help tabs.
	 *
	 * @since 1.0.0
	 */
	public function render_help_tabs() {

		$screen = get_current_screen();

		foreach ( $this->help_tabs as $help_tab ) $screen->add_help_tab( $help_tab );

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

			$tux_meta_data = $_POST[ 'tuxoptionsdata' ];

		} elseif ( $tux_meta_data = get_post_custom() ) {

			$bases = array();

			foreach ( $this->settings as $setting )
				if ( ! in_array( $setting->id_data[ 'base' ], $bases ) ) $bases[] = $setting->id_data[ 'base' ];

			foreach ( $bases as $base )
				if ( isset( $tux_meta_data[ $base ] ) ) $tux_meta_data[ $base ] = maybe_unserialize( $tux_meta_data[ $base ][0] );

		} else {

			$tux_meta_data = array();

		}

		$setting_keys = array_keys( $this->settings );
		foreach ( $setting_keys as $setting_key ) {

			$meta_data = null;

			if ( array_key_exists( $this->settings[ $setting_key ]->id_data[ 'base' ], $tux_meta_data ) ) {

				$meta_data = $tux_meta_data[ $this->settings[ $setting_key ]->id_data[ 'base' ] ];
				if ( ! empty( $this->settings[ $setting_key ]->id_data[ 'keys' ] ) ) {
					foreach ( $this->settings[ $setting_key ]->id_data[ 'keys' ] as $key ) {
						if ( ! array_key_exists( $key, $meta_data ) ) {
							$meta_data = null;
							break;
						}
						$meta_data = $meta_data[ $key ];
					}
				}

			}

			if ( $meta_data !== null ) {
				$this->settings[ $setting_key ]->set_value( $meta_data );
			} else {
				$this->settings[ $setting_key ]->set_value( $this->settings[ $setting_key ]->default );
			}

			unset( $meta_data );

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

