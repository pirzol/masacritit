<?php

namespace ElementorExtras\Extensions;

use ElementorExtras\Base\Extension_Base;
use Elementor\Controls_Manager;
use ElementorExtras\Group_Control_Parallax;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Parallax extenstion
 *
 * Adds parallax on widgets and columns
 *
 * @since 1.1.3
 */
class Extension_Parallax extends Extension_Base {

	/**
	 * Constructor
	 *
	 * @since 1.1.3
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
	 * @since 1.1.3
	 *
	 * @access private
	 */
	private function add_controls( $element, $args ) {

		$element_type = $element->get_type();

		$element->add_group_control(
			Group_Control_Parallax::get_type(), [ 'name' => 'parallax_element' ]
		);

	}

	/**
	 * Add Actions
	 *
	 * @since 1.1.3
	 *
	 * @access private
	 */
	private function add_actions() {

		// Activate for widgets
		add_action( 'elementor/element/common/section_elementor_extras_advanced/before_section_end', function( $element, $args ) {

			$this->add_controls( $element, $args );

		}, 10, 2 );

		// Activate for columns
		add_action( 'elementor/element/column/section_elementor_extras_advanced/before_section_end', function( $element, $args ) {

			$this->add_controls( $element, $args );

		}, 10, 2 );

		// Activate for columns

	}

}