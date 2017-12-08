<?php
namespace ElementorExtras\Admin;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Main Plugin Class
 *
 * Implements the plugin info page
 *
 * @since 1.1.3
 */
class Settings_Info {

	/**
	 * Constructor
	 *
	 * @since 0.1.0
	 *
	 * @access public
	 */
	public function __construct() {

		add_action( 'admin_menu', array( $this, 'admin_menu') );
	}

	/**
	 * admin_menu
	 *
	 * Adds the item to the menu
	 *
	 * @since 1.1.3
	 *
	 * @access public
	*/

	public function admin_menu() {
		// add page
		add_submenu_page( 'admin.php', __( 'Elementor Extras', 'elementor-extras' ), __( 'Elementor Extras', 'elementor-extras' ), 'manage_options', 'ee-settings-info', array( $this, 'render_page' ) );
	}


	/**
	 * render_page
	 *
	 * Renders the page
	 *
	 * @since 1.1.3
	 *
	 * @access public
	*/

	public function render_page() {
		
		// vars
		$info = array(
			'version'		=> ELEMENTOR_EXTRAS_VERSION,
		);
		
		// load view
		elementor_extras_get_view( 'settings-info', $info );

	}

}


// initialize
// new Settings_Info();

?>