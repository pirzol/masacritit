<?php
namespace ElementorExtras;

/**
 * Namogo Plugin Usage Tracker.
 *
 * @author     Namogo
 * @version    1.0.0
 * @copyright  (c) 2017 Namogo
 * @package    nmg-plugin-usage-tracker
*/
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * WP_Plugin_Usage_Tracker class.
 */
class Nmg_Plugin_Usage_Tracker {
	/**
	 * Codeless library helper object.
	 * @var object
	 */
	public $helper;
	/**
	 * Installation date of the plugin using this library.
	 * @var string
	 */
	private $installation_date;
	/**
	 * The API endpoint. Configured through the class's constructor.
	 *
	 * @var String  The API endpoint.
	 */
	private $api_endpoint;
	/**
	 * How many days we should wait to show the tracking message.
	 * @var string
	 */
	private $days_passed;
	/**
	 * Prefix of the plugin using this library.
	 * @var string
	 */
	private $plugin_prefix;
	/**
	 * Plugin name identifier that will be sent to Keen.io
	 * @var string
	 */
	private $plugin_name;
	/**
	 * Keen.io Project ID.
	 * @var string
	 */
	private $product_id;
	/**
	 * Keen.io Writekey.
	 * @var string
	 */
	private $write_key;
	/**
	 * Keen.io Client.
	 * @var object
	 */
	public $client;
	/**
	 * Get things started.
	 *
	 * @param string $plugin_prefix     Prefix of the plugin using this library.
	 * @param string $plugin_name       Name of the plugin, this will be sent to keen.io so you can identify the data easily.
	 * @param string $installation_date Installation date of the plugin using this library.
	 * @param string $days_passed       How many days we should wait to show the tracking message.
	 */
	public function __construct( $plugin_prefix, $plugin_name, $installation_date, $days_passed, $product_id, $notice, $api_endpoint ) {
		$this->plugin_prefix     	= sanitize_title( $plugin_prefix );
		$this->plugin_name       	= strip_tags( $plugin_name );
		$this->installation_date 	= strtotime( $installation_date );
		$this->days_passed       	= $days_passed;
		$this->product_id        	= $product_id;
		$this->notice 				= $notice;
		$this->api_endpoint 		= $api_endpoint;

		require __DIR__ . '/../lib/autoload.php';

		$this->helper = new \TDP\Codeless;

		// $this->track();
	}
	/**
	 * Run hooks.
	 *
	 * @return void
	 */
	public function init() {

		// Enqueue Codeless scripts and styles
		add_action( 'admin_enqueue_scripts', array( $this->helper, 'add_ui_helper_files' ) );

		// Admin actions for handling notices
		add_action( 'admin_init', array( $this, 'admin_notice' ) );
		add_action( 'admin_init', array( $this, 'approve_tracking' ), 10 );
		add_action( 'admin_init', array( $this, 'schedule_tracking' ), 10 );

		if( $this->is_tracking_enabled() && $this->is_date_passed() ) {
			// add_filter( 'cron_schedules', array( $this, 'cron_schedules' ) );
			// add_action( $this->plugin_prefix . '_usage_tracking', array( $this, 'track' ) );
		}
	}
	/**
	 * Show admin notice.
	 *
	 * @return void
	 */
	public function admin_notice() {

		if( current_user_can( 'manage_options' ) && ! $this->is_tracking_enabled() && $this->is_date_passed() ) {
			$this->helper->show_admin_notice( $this->get_message() , 'info' , $this->plugin_prefix . '_usage_tracking_message' );
		}
	}
	/**
	 * Retrieve the message for the admin notice.
	 *
	 * @return string
	 */
	public function get_message() {
		$message = esc_html__( $this->notice );
		$message .= ' <a href="'. esc_url( $this->get_tracking_approval_url() ) .'" class="button-primary">'. esc_html( 'Allow tracking' ) .'</a>';
		return $message;
	}
	/**
	 * Get the url of the approval button.
	 *
	 * @return string
	 */
	protected function get_tracking_approval_url() {
		return add_query_arg( array( 'wpput_tracker' => 'approved', 'plugin' => $this->plugin_prefix ), admin_url() );
	}
	/**
	 * Check if it's time to display the tracking message.
	 *
	 * @return boolean
	 */
	private function is_date_passed() {
		$passed = true;
		$installation_date = $this->installation_date;

		$past_date         = strtotime( '-'. $this->days_passed .' days' );

		if( $installation_date && $past_date >= $installation_date ) {
			$passed = true;
		}

		return $passed;
	}
	/**
	 * Set the required flags to approve the tracking.
	 *
	 * @return void
	 */
	public function approve_tracking() {
		if (
			( isset( $_GET['wpput_tracker'] )
			&& $_GET['wpput_tracker'] == 'approved'
			&& isset( $_GET['plugin'] )
			&& $_GET['plugin'] == $this->plugin_prefix
			&& current_user_can( 'manage_options' )
			&& $this->is_date_passed() )
			||
			( $this->notice === false && ! $this->is_tracking_enabled() )
		) {
			update_option( $this->plugin_prefix . '_tracking' , true );
			wp_redirect( admin_url() );
			exit;
		}
	}
	/**
	 * Retrieves the data to send to Keen.io
	 *
	 * @return array
	 */
	public function get_data() {

		$data = array();
		$info 			= array(
			'php_version'    	=> phpversion(),
			'wp_version'     	=> get_bloginfo( 'version' ),
			'server'         	=> isset( $_SERVER['SERVER_SOFTWARE'] ) ? $_SERVER['SERVER_SOFTWARE']: '',
			'multisite'      	=> is_multisite(),
			'theme'          	=> $this->get_theme_name(),
			'locale'         	=> get_locale(),
			'active_plugins' 	=> $this->get_active_plugins(),
		);

		$data['plugin_name']    = $this->plugin_name;
		$data['admin_email']	= get_option( 'admin_email' );
		$data['host']			= $_SERVER['HTTP_HOST'];
		$data['info']			= serialize( $info );
		
		return $data;
	}
	/**
	 * Retrieve the current theme's name and version.
	 *
	 * @return string
	 */
	private function get_theme_name() {
		$theme_data = wp_get_theme();
		$theme      = $theme_data->Name . ' ' . $theme_data->Version;
		return $theme;
	}
	/**
	 * Get list of activated plugins.
	 *
	 * @return array
	 */
	private function get_active_plugins() {
		$active_plugins = get_option( 'active_plugins', array() );
		return $active_plugins;
	}
	/**
	 * Register a new cron schedule with WP.
	 *
	 * @param  array $schedules existing ones.
	 * @return array
	 */
	public function cron_schedules( $schedules ) {
		$schedules['monthly'] = array(
			'interval' 	=> 30 * 86400,
			'display' 	=> __( 'Once a month' )
		);
		return $schedules;
	}
	/**
	 * Determines whether tracking is enabled for this plugin.
	 *
	 * @return boolean
	 */
	private function is_tracking_enabled() {
		return (bool) get_option( $this->plugin_prefix . '_tracking' , false );
	}
	/**
	 * Disables the tracking for a plugin.
	 *
	 * @return void
	 */
	public function disable_tracking() {
		delete_option( $this->plugin_prefix . '_tracking' );
		wp_clear_scheduled_hook( $this->plugin_prefix.'_usage_tracking' );
	}
	/**
	 * Use this method to schedule the tracking.
	 *
	 * @return void
	 */
	public function schedule_tracking() {
		if( $this->is_tracking_enabled() && $this->is_date_passed() && ! wp_next_scheduled ( $this->plugin_prefix.'_usage_tracking' ) ) {
			wp_schedule_event( time(), 'daily', $this->plugin_prefix . '_usage_tracking' );
		}
	}
	/**
	 * Send the data to Keen.io
	 *
	 * @param  array $data the data to send.
	 * @return void
	 */
	private function call_api( $data ) {

		// $this->helper->show_admin_notice( $data['info'] , 'info' );

		$url = $this->api_endpoint;

		// Append parameters for GET request
		$url .= '?' . http_build_query( $data );

		// Send the request
		$response = wp_remote_get( $url, array(
			'sslverify'	=> false
		));
	}
	/**
	 * Task triggered by the cron Event.
	 *
	 * @return void
	 */
	public function track() {
		$this->call_api( $this->get_data() );
	}
}