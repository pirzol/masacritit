<?php

namespace ElementorExtras\Extensions;

use ElementorExtras\Base\Extension_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Sections
 *
 * Sets up the necessary section(s) to place our controls in
 *
 * @since 0.1.0
 */
class Extension_Sections extends Extension_Base {

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
	private function add_sections( $element, $args ) {

		$element_type = $element->get_type();

		$element->start_controls_section(
			'section_elementor_extras_advanced',
			[
				'tab' 	=> \Elementor\Controls_Manager::TAB_ADVANCED,
				'label' => __( 'Extras', 'elementor-extras' ),
			]
		);

			

		$element->end_controls_section();

	}

	/**
	 * Add Actions
	 *
	 * @since 0.1.0
	 *
	 * @access private
	 */
	private function add_actions() {

		// Activate sections for widgets
		add_action( 'elementor/element/common/_section_style/after_section_end', function( $element, $args ) {

			$this->add_sections( $element, $args );

		}, 10, 2 );

		// Activate sections for columns
		add_action( 'elementor/element/column/section_advanced/after_section_end', function( $element, $args ) {

			$this->add_sections( $element, $args );

		}, 10, 2 );

		// Activate sections for columns
		add_action( 'elementor/element/section/section_advanced/after_section_end', function( $element, $args ) {

			$this->add_sections( $element, $args );

		}, 10, 2 );
	}

}