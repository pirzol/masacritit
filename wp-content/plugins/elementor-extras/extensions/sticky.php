<?php

namespace ElementorExtras\Extensions;

use ElementorExtras\Base\Extension_Base;
use Elementor\Controls_Manager;
use ElementorExtras\Group_Control_Sticky;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Sticky Extension
 *
 * Adds sticky on scroll capability to widgets and sections
 *
 * @since 0.1.0
 */
class Extension_Sticky extends Extension_Base {

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

	/**
	 * Add Actions
	 *
	 * @since 0.1.0
	 *
	 * @access private
	 */
	private function add_controls( $element, $args ) {

		$element_type = $element->get_type();

		$element->add_group_control(
			Group_Control_Sticky::get_type(), [ 'name' => 'sticky' ]
		);

	}

	/**
	 * Add Actions
	 *
	 * @since 0.1.0
	 *
	 * @access private
	 */
	private function add_actions() {

		// Activate for widgets
		add_action( 'elementor/element/common/section_elementor_extras_advanced/before_section_end', function( $element, $args ) {

			$this->add_controls( $element, $args );

		}, 10, 2 );

		// Activate for columns
		add_action( 'elementor/element/section/section_elementor_extras_advanced/before_section_end', function( $element, $args ) {

			$this->add_controls( $element, $args );

		}, 10, 2 );
	}

}