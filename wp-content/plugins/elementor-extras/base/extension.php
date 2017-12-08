<?php

namespace ElementorExtras\Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Extension
 *
 * Class to easify extend Elementor controls and functionality
 *
 * @since 0.1.0
 */
class Extension_Base {

	/**
	 * Constructor
	 *
	 * @since 0.1.0
	 *
	 * @access public
	 */
	public function __construct() {
		// Elementor hooks
		$this->add_actions();
	}

	public function get_script_depends() {
		return [];
	}

	/**
	 * Add Actions
	 *
	 * @since 0.1.0
	 *
	 * @access private
	 */
	private function add_actions() {

	}

	/**
	 * Removes controls in bulk
	 *
	 * @since 0.1.0
	 *
	 * @access private
	 */
	private function remove_controls( $element, $controls = null ) {
		if ( empty( $controls ) )
			return;

		if ( is_array( $controls ) ) {
			$control_id = $controls;

			foreach( $controls as $control_id ) {
				$element->remove_control( $control_id );
			}
		} else {
			$element->remove_control( $controls );
		}
	}

}