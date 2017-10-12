<?php
/**
 * Input controls
 *
 * @package ThemeTuxedo\InputControl
 * @since 1.0.0
 */

/**
 * Tux Metabox Setting Class.
 *
 * Handles saving and sanitizing of settings.
 *
 * @package ThemeTuxedo\Input_Control
 * @since 1.0.0
 */
class Tux_Input_Control {

	/**
	 * Tux metabox or adminpage manager object.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @var Tux_Manager
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
	 * Priority (order) for the control.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @var int
	 */
	public $priority = 10;

	/**
	 * The id of the setting for the control.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @var string
	 */
	public $settings = '';

	/**
	 * The id of the section for the control.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @var string
	 */
	public $section = '';

	/**
	 * The id of the tab for the control.
	 *
	 * Only applies to metaboxes.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @var string
	 */
	public $tab = '';

	/**
	 * The label for the control.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @var string
	 */
	public $label = '';

	/**
	 * The description for the control.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @var string
	 */
	public $description = '';

	/**
	 * The type of the control.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @var string
	 */
	public $type = 'textbox';

	/**
	 * The choices for the control (if applicable).
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @var string
	 */
	public $choices = array();

	/**
	 * The input attrributes for the control (if applicable).
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @var string
	 */
	public $input_attrs = array();

	/**
	 * HTML to output before the control.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @var string
	 */
	public $before = '';

	/**
	 * HTML to output after the control.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @var string
	 */
	public $after = '';

	/**
	 * Constructor.
	 *
	 * Supplied $args override class property defaults.
	 *
	 * If $args['settings'] is not defined, use the $id as the setting ID.
	 *
	 * @since 1.0.0
	 *
	 * @param Tux_Metabox_Manager  $manager Tux_Metabox_Manager object.
	 * @param string               $id      An specific ID of the setting.
	 * @param array                $args    Setting arguments.
	 * @return Tux_Input_Control   $control
	 */
	public function __construct( &$manager, $id, $args = array() ) {

		$keys = array_keys( get_object_vars( $this ) );

		foreach ( $keys as $key ) {
			if ( isset( $args[ $key ] ) ) {
				$this->$key = $args[ $key ];
			}
		}

		$this->manager = $manager;
		$this->id = $id;
		if ( empty( $this->settings ) ) $this->settings = $id;

		if ( ! empty( $this->tab ) ) {
			add_action( "tux_{$manager->hook_key}_render_controls_{$this->tab}", array( $this, 'render' ), $this->priority );
		} else {
			add_action( "tux_{$manager->hook_key}_render_controls_{$this->section}", array( $this, 'render' ), $this->priority );
		}

		return $this;

	}

	/**
	 * Generate a name/id from a settings "id_data".
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @param array   $id_data Array of settings id_data.
	 * @return string $name    Name/id.
	 */
	public function name( $id_data ) {

		$name = 'tuxoptionsdata[' . $id_data[ 'base' ] . ']';

		if ( ! empty( $id_data[ 'keys' ] ) ) $name .= '[' . implode( '][', $id_data[ 'keys' ] ) . ']';

		return esc_attr( $name );

	}

	/**
	 * Render the custom attributes for the control's input element.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function input_attrs() {

		foreach( $this->input_attrs as $attr => $value ) echo $attr . '="' . esc_attr( $value ) . '" ';

	}

	/**
	 * Enqueue control related scripts/styles.
	 *
	 * @since 1.0.0
	 */
	public function enqueue() {}

	/**
	 * Render the control.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @param array $settings Array of setting objects.
	 */
	public function render() {

		if ( $this->type == 'html_desc' ) {
			echo ( $this->before ? $this->before : '' );
			echo $this->description;
			echo ( $this->after ? $this->after : '' );
			return;
		}

		if ( ! isset( $this->manager->settings[ $this->settings ] ) || ! $this->manager->settings[ $this->settings ]->capability || ! current_user_can( $this->manager->settings[ $this->settings ]->capability ) ) return;

		$value = $this->manager->settings[ $this->settings ]->value();
		$name = $this->name( $this->manager->settings[ $this->settings ]->id_data );

		echo ( $this->before ? $this->before : '' );
		switch( $this->type ) {

			case 'checkbox':

				?>
				<p><label>
					<input type="hidden" value="0" name="<?php echo esc_attr( $name ); ?>" />
					<input type="checkbox" value="1" name="<?php echo esc_attr( $name ); ?>" <?php checked( $value, 1 ); ?> />
					<span><?php echo esc_html( $this->label ); ?></span>
					<?php if ( ! empty( $this->description ) ) : ?>
						<br><span style="font-style:italic;"><?php echo $this->description; ?></span>
					<?php endif; ?>
				</label></p>
				<?php

				break;

			case 'radio':

				if ( empty( $this->choices ) ) return;

				echo '<p>';
				if ( ! empty( $this->label ) ) : ?>
					<span style="font-size:13px;font-weight:600;"><?php echo esc_html( $this->label ); ?></span>
				<?php endif;
				foreach ( $this->choices as $choice_value => $choice_label ) :
					?>
					<label>
						<br><input type="radio" value="<?php echo esc_attr( $choice_value ); ?>" name="<?php echo esc_attr( $name ); ?>" <?php checked( $value, $choice_value ); ?> />
						<?php echo esc_html( $choice_label ); ?><br/>
					</label>
					<?php
				endforeach;
				if ( ! empty( $this->description ) ) : ?>
					<br><span style="font-style:italic;"><?php echo $this->description ; ?></span>
				<?php endif;
				echo '</p>';

				break;

			case 'select':

				if ( empty( $this->choices ) ) return;

				?>
				<p><label>
					<?php if ( ! empty( $this->label ) ) : ?>
						<span style="font-size:13px;font-weight:600;"><?php echo esc_html( $this->label ); ?></span>
					<?php endif; ?>
					<br><select <?php $this->input_attrs(); ?> name="<?php echo esc_attr( $name ); ?>">
						<?php
						foreach ( $this->choices as $choice_value => $choice_label )
							echo '<option value="' . esc_attr( $choice_value ) . '"' . selected( $value, $choice_value, false ) . '>' . $choice_label . '</option>';
						?>
					</select>
					<?php if ( ! empty( $this->description ) ) : ?>
						<br><span style="font-style:italic;"><?php echo $this->description; ?></span>
					<?php endif; ?>
				</label></p>
				<?php

				break;

			case 'textarea':

				?>
				<p><label>
					<?php if ( ! empty( $this->label ) ) : ?>
						<span style="font-size:13px;font-weight:600;"><?php echo esc_html( $this->label ); ?></span>
					<?php endif; ?>
					<br><textarea rows="5" <?php $this->input_attrs(); ?> name="<?php echo esc_attr( $name ); ?>"><?php echo esc_textarea( $value ); ?></textarea>
					<?php if ( ! empty( $this->description ) ) : ?>
						<br><span style="font-style:italic;"><?php echo $this->description; ?></span>
					<?php endif; ?>
				</label></p>
				<?php

				break;

			case 'dropdown-pages':

				$dropdown = wp_dropdown_pages(
					array(
						'name'              => $name,
						'echo'              => 0,
						'show_option_none'  => __( '&mdash; Select &mdash;' ),
						'option_none_value' => '0',
						'selected'          => $value,
					)
				);

				?>
				<p><label>
					<?php if ( ! empty( $this->label ) ) : ?>
						<span style="font-size:13px;font-weight:600;"><?php echo esc_html( $this->label ); ?></span>
					<?php endif; ?>
					<br><?php echo $dropdown; ?>
					<?php if ( ! empty( $this->description ) ) : ?>
						<br><span style="font-style:italic;"><?php echo $this->description; ?></span>
					<?php endif; ?>
				</label></p>
				<?php

				break;

			default:

				?>
				<p><label>
					<?php if ( ! empty( $this->label ) ) : ?>
						<span style="font-size:13px;font-weight:600;"><?php echo esc_html( $this->label ); ?></span>
					<?php endif; ?>
					<br><input type="<?php echo esc_attr( $this->type ); ?>" <?php $this->input_attrs(); ?> value="<?php echo esc_attr( $value ); ?>" name="<?php echo esc_attr( $name ); ?>" />
					<?php if ( ! empty( $this->description ) ) : ?>
						<br><span style="font-style:italic;"><?php echo $this->description; ?></span>
					<?php endif; ?>
				</label></p>
				<?php

				break;

		}
		echo ( $this->after ? $this->after : '' );

	}

}

