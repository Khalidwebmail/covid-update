<?php
/**
 * Live covid update
 *
 * @package           PluginPackage
 * @author            Khalid Ahmed
 * @copyright         2022 Khalid Ahmed
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       Live covid update
 * Plugin URI:        https://example.com/plugin-name
 * Description:       Description of the plugin.
 * Version:           1.0.0
 * Author:            Khalid Ahmed
 * Author URI:        https://example.com
 * Text Domain:       live-covid-update
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Update URI:        https://example.com/my-plugin/
 */

class Covid_Update extends WP_Widget {
    /**
	 * Sets up the widgets name etc
	 */
    public function __construct() {
        parent::__construct(
            'covid_update', // Base ID
            'Live Covid Update', // Name
            array( 'description' => __( 'Live covid update', 'live-covid-update' ), )
        );

        add_action( 'widgets_init', [ $this, 'lcvr_register_widget'] );
    }

    /**
	 * Outputs the content of the widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {
		extract( $args );
        $txt_title   = apply_filters( 'widget_title', $instance['txt_title'] );
        $description = $instance['description'];

        echo $before_widget;
        if ( ! empty( $txt_title ) ) {
            echo $before_title . $txt_title . $after_title;
        }
        echo $after_widget;

        echo $before_widget;
        if ( ! empty( $description ) ) {
            echo  $description;
        }
        echo $after_widget;
	}

	/**
	 * Outputs the options form on admin
	 *
	 * @param array $instance The widget options
	 */
	public function form( $instance ) {

        $txt_title   = !empty( $instance['txt_title'] ) ? $instance['txt_title'] : '';
        $description = !empty( $instance['description'] ) ? $instance['description'] : '';
		?>
        <p>
            <label for="<?php echo $this->get_field_id( 'txt_title' ) ?>">Widget title</label>
            <input type="text" name="<?php echo $this->get_field_name( 'txt_title' ) ?>" id="<?php echo $this->get_field_id( 'txt_title' ) ?>" placeholder="Enter widget title" class="widefat" value="<?php esc_attr_e( $txt_title, 'live-covid-update' )?>">
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'description' ) ?>">Widget description</label>
            
            <textarea name="<?php echo $this->get_field_name( 'description' ) ?>" id="<?php echo $this->get_field_id( 'description' ) ?>" class="widefat"><?php esc_attr_e( $description, 'live-covid-update' )?></textarea>
        </p>
        <?php
	}

	/**
	 * Processing widget options on save
	 *
	 * @param array $new_instance The new options
	 * @param array $old_instance The previous options
	 *
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = [];
        $instance['txt_title']       = ( !empty( $new_instance['txt_title'] ) ) ? strip_tags( $new_instance['txt_title'] ) : '';
        $instance['description'] = ( !empty( $new_instance['description'] ) ) ? strip_tags( $new_instance['description'] ) : '';
 
        return $instance;
	}

    /**
     * Register widget with WordPress.
     */
    public function lcvr_register_widget(){
        register_widget( 'Covid_Update' ); 
    }
}

$lcvr_widget = new Covid_Update();