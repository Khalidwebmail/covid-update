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
 * Plugin URI:        https://github.com/Khalidwebmail/covid-update
 * Description:       Description of the plugin.
 * Version:           1.0.0
 * Author:            Khalid Ahmed
 * Author URI:        https://example.com
 * Text Domain:       live-covid-update
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Update URI:        https://github.com/Khalidwebmail/wp-plg-covid-update
 */

if( ! defined( 'ABSPATH' ) ){
	exit;
}

class Covid_Update_Widget extends WP_Widget{
	public function __construct(){
		parent::__construct(
			'covid_update', // Base ID
            'Live Covid Update', // Name
            array( 'description' => __( 'Live covid update', 'live-covid-update' ) )
		);

		/**
		 * Register new widget
		 */
		add_action( 'widgets_init', [ $this, 'lcr_register_widget' ] );
	}

	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {
        echo $args [ 'before_title' ];
		if( ! empty( $instance[ 'title' ] ) ){
			echo  $instance[ 'title' ];
		}
		echo $args [ 'after_title' ];

		$lcr_covid_data = $this->lcr_get_covid_report();
		$data = json_decode($lcr_covid_data);
		$all_data = isset( $data->Global ) ? $data->Global : [];
		$countries_data = isset( $data->Countries ) ? $data->Countries : [];
		echo $args [ 'before_widget' ];

		echo '<table class="table table-bordered">
			<thead>
				<tr>
					<th>Total Confirmed</th>
					<th>Total Death</th>
					<th>Total Recovered</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>'.$all_data->TotalConfirmed.'</td>
					<td>'.$all_data->TotalDeaths.'</td>
					<td>'.$all_data->TotalRecovered.'</td>
				</tr>
			</tbody>
		</table>';

		$countries_data_list = '';
		foreach( $countries_data as $country_data ){
			$countries_data_list .= '<tr>
			<td>'.$country_data->CountryCode.'</td>
				<td>'.$country_data->Country.'</td>
				<td>'.$country_data->TotalConfirmed.'</td>
				<td>'.$country_data->TotalDeaths.'</td>
				<td>'.$country_data->TotalRecovered.'</td>
			</tr>';
		}

		echo '<table class="table table-bordered">
			<thead>
				<tr>
					<th>Country Code</th>
					<th>Country Name</th>
					<th>Total Confirmed</th>
					<th>Total Death</th>
					<th>Total Recovered</th>
				</tr>
			</thead>
			<tbody>
				'.$countries_data_list.'
			</tbody>
	  	</table>';
		echo $args [ 'after_widget' ];
	}

	/**
	 * Outputs the options form on admin
	 *
	 * @param array $instance The widget options
	 */
	public function form( $instance ) {
		$txt_title   = !empty( $instance['txt_title'] ) ? $instance['txt_title'] : '';
		?>
			<p>
                <label for="<?php echo $this->get_field_id( 'txt_title' ) ?>">Widget title</label>
                <input type="text" name="<?php echo $this->get_field_name( 'txt_title' ) ?>" id="<?php echo $this->get_field_id( 'txt_title' ) ?>" placeholder="Enter widget title" class="widefat" value="<?php esc_attr_e( $txt_title, 'live-covid-update' )?>">
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
	public function update( $new_instance, $old_instance ){
		$instance = [];
        $instance['txt_title'] = ( !empty( $new_instance['txt_title'] ) ) ? strip_tags( $new_instance['txt_title'] ) : '';
        return $instance;
	}

    /**
     * Register widget with WordPress.
     */
    public function lcr_register_widget(){
        register_widget( 'Covid_Update_Widget' );
    }

	/**
	 * Pull live report using API
	 */
    public function lcr_get_covid_report(){
        $lcr_ch = curl_init();
        curl_setopt( $lcr_ch, CURLOPT_URL, 'https://api.covid19api.com/summary' );
        curl_setopt( $lcr_ch, CURLOPT_RETURNTRANSFER, 1 );

        $lcr_result = curl_exec( $lcr_ch );
        if ( curl_errno( $lcr_ch ) ) {
            echo 'Error:' . curl_error( $lcr_ch );
        }
        curl_close( $lcr_ch );
		return $lcr_result;
    }
}

$lcr_widget = new Covid_Update_Widget();