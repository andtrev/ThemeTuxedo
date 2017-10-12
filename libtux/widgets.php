<?php
/**
 * Widgets
 *
 * @package ThemeTuxedo\Widgets
 * @since 1.0.0
 */

/**
 * Add sidebar rows and columns.
 *
 * Attached to the "dynamic_sidebar_params" filter.
 *
 * @since 1.0.0
 */
function tux_add_sidebar_rows_cols( $params ) {

	static $sidebar_id = '';           // Current sidebar id.
	static $cur_widget = 1;            // Current widget.
	static $total_widgets = 1;         // Total widgets in current sidebar.
	static $cur_row = 0;               // Current row (zero-based).
	static $cur_col_in_row = 1;        // Current column in row.
	static $cols_per_row = array( 1 ); // Columns per row.
	$before = '';

	if ( $sidebar_id != $params[ 0 ][ 'id' ] ) {

		$sidebar_widgets = wp_get_sidebars_widgets(); // Get list of sidebars and their widgets.
		$total_widgets = count( $sidebar_widgets[ $params[ 0 ][ 'id' ] ] );
		$cur_widget = 1;
		$sidebar_id = $params[ 0 ][ 'id' ];
		$cur_row = 0;
		$cols_per_row = array( 1 );
		foreach ( $sidebar_widgets[ $sidebar_id ] as $widget_id ) {
			if ( strpos( $widget_id, 'tux_col' ) !== false ) {
				if ( $cols_per_row[ $cur_row ] == 12 ) {
					$cur_row++;
					$cols_per_row[ $cur_row ] = 1;
				} else {
					$cols_per_row[ $cur_row ]++;
				}
			} elseif ( strpos( $widget_id, 'tux_row' ) !== false ) {
				$cur_row++;
				$cols_per_row[ $cur_row ] = 1;
			}
		}
		$cur_row = 0;
		$before = '<div class="section group"><div class="col span_1_of_' . $cols_per_row[ 0 ] . '">';

	}

	if ( strpos( $params[ 0 ][ 'widget_id' ], 'tux_row' ) !== false || $cur_col_in_row > 12 ) {
		$cur_row++;
		$before = $before . '</div></div><div class="section group"><div class="col span_1_of_' . $cols_per_row[ $cur_row ] . '">';
		$cur_col_in_row = 1;
		$params[ 0 ][ 'before_widget' ] = '';
	} elseif ( strpos( $params[ 0 ][ 'widget_id' ], 'tux_col' ) !== false ) {
		$before = $before . '</div><div class="col span_1_of_' . $cols_per_row[ $cur_row ] . '">';
		$cur_col_in_row++;
		$params[ 0 ][ 'before_widget' ] = '';
	}

	$params[ 0 ][ 'before_widget' ] = $before . $params[ 0 ][ 'before_widget' ];

	if ( $cur_widget == $total_widgets ) {
		$params[ 0 ][ 'after_widget' ] .= '</div></div>';
	}
	$cur_widget++;

	return $params;

}
add_filter( 'dynamic_sidebar_params', 'tux_add_sidebar_rows_cols' );

/**
 * Tux_Widget_Row widget class
 *
 * @package ThemeTuxedo\Widgets
 * @since 1.0.0
 */
class Tux_Widget_Row extends WP_Widget {

	public function __construct() {

		parent::__construct( 'tux_row', __( 'Layout: New Column on New Row', 'tuxedo' ), array(
			'classname'   => 'tux_widget_row',
			'description' => __( 'Create a new row and column of widgets.', 'tuxedo' ),
		) );

	}

	public function widget( $args, $instance ) {

		echo $args[ 'before_widget' ];

	}

	public function update( $new_instance, $old_instance ) {

		return $new_instance;

	}

	public function form( $instance ) { ?><br/><?php }

}

/**
 * Tux_Widget_Column widget class
 *
 * @package ThemeTuxedo\Widgets
 * @since 1.0.0
 */
class Tux_Widget_Column extends WP_Widget {

	public function __construct() {

		parent::__construct( 'tux_col', __( 'Layout: New Column', 'tuxedo' ), array(
			'classname'   => 'tux_widget_col',
			'description' => __( 'Create a new column of widgets.', 'tuxedo' ),
		) );

	}

	public function widget( $args, $instance ) {

		echo $args[ 'before_widget' ];

	}

	public function update( $new_instance, $old_instance ) {

		return $new_instance;

	}

	public function form( $instance ) { ?><br/><?php }

}

/**
 * Register widgets.
 *
 * @since 1.0.0
 */
function tux_register_widgets() {

	register_widget( 'Tux_Widget_Row' );
	register_widget( 'Tux_Widget_Column' );

}
add_action( 'widgets_init', 'tux_register_widgets' );

