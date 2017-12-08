<?php

namespace ElementorExtras\Extensions;

use ElementorExtras\Base\Extension_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Portfolio Extension
 *
 * Extends the options and design of the default
 * portfolio widget
 *
 * @since 0.1.0
 */
class Extension_Portfolio extends Extension_Base {

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

		$element->start_controls_section(
			'section_elementor_extras',
			[
				'label' => __( 'Extras', 'elementor-extras' ),
			]
		);

			// Make sure columns are available for our plugin
			$element->update_control( 'columns', [ 'frontend_available' => true ] );

			$element->add_control(
				'parallax_enable',
				[
					'label'			=> __( 'Parallax', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SWITCHER,
					'default' 		=> '',
					'label_on' 		=> __( 'Yes', 'elementor-extras' ),
					'label_off' 	=> __( 'No', 'elementor-extras' ),
					'return_value' 	=> 'yes',
					'frontend_available' => true,
				]
			);

			$element->add_control(
				'parallax_disable_on',
				[
					'label' 	=> __( 'Disable for', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SELECT,
					'default' 	=> 'mobile',
					'options' 			=> [
						'none' 		=> __( 'None', 'elementor-extras' ),
						'mobile' 	=> __( 'Mobile only', 'elementor-extras' ),
						'tablet' 	=> __( 'Mobile and tablet', 'elementor-extras' ),
					],
					'condition' => [
						'parallax_enable' => 'yes',
					],
					'frontend_available' => true,
				]
			);

			$element->add_responsive_control(
				'parallax_speed',
				[
					'label' 	=> __( 'Parallax speed', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SLIDER,
					'default'	=> [
						'size'	=> 0.5
					],
					'tablet_default' => [
						'size'	=> 0.5
					],
					'mobile_default' => [
						'size'	=> 0.5
					],
					'range' 	=> [
						'px' 	=> [
							'min'	=> 0.05,
							'max' 	=> 1,
							'step'	=> 0.01,
						],
					],
					'condition' => [
						'parallax_enable' => 'yes',
					],
					'frontend_available' => true,
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


		// ——— CUSTOM CONTROLS

		add_action( 'elementor/element/portfolio/section_layout/after_section_end', function( $element, $args ) {

			$this->add_controls( $element, $args );

		}, 10, 2 );

	}

}