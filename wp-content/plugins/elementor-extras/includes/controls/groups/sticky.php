<?php
namespace ElementorExtras;

use Elementor\Group_Control_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Custom sticky group control
 *
 * @since 1.1.4
 */
class Group_Control_Sticky extends Group_Control_Base {

	protected static $fields;

	public static function get_type() {
		return 'sticky';
	}

	protected function init_fields() {
		$controls = [];

		$controls['heading'] = [
			'type'		=> Controls_Manager::HEADING,
			'label' 	=> __( 'Sticky', 'elementor-extras' ),
			'separator' => 'before',
		];

		$controls['enable'] = [
			'label'			=> _x( 'Enable', 'Sticky Control', 'elementor-extras' ),
			'description'	=> __( 'Make sure "Content Position" is set to "Default" for any parent sections of this element.', 'elementor-extras' ),
			'type' 			=> Controls_Manager::SWITCHER,
			'default' 		=> '',
			'label_on' 		=> __( 'Yes', 'elementor-extras' ),
			'label_off' 	=> __( 'No', 'elementor-extras' ),
			'return_value' 	=> 'yes',
			'frontend_available'	=> true,
		];

		$controls['parent'] = [
			'label'			=> _x( 'Stick in', 'Sticky Control', 'elementor-extras' ),
			'type' 			=> Controls_Manager::SELECT,
			'options'		=> [
				'' 	=> __( 'Parent', 'elementor-extras' ),
				'body' 		=> __( 'Body', 'elementor-extras' ),
				'custom' 	=> __( 'Custom', 'elementor-extras' ),
			],
			'condition' => [
				'enable!' => '',
			],
			'default' 		=> '',
			'frontend_available'	=> true,
		];

		$controls['unstick_on'] = [
			'label' 	=> _x( 'Unstick on', 'Sticky Control', 'elementor-extras' ),
			'type' 		=> Controls_Manager::SELECT,
			'default' 	=> 'mobile',
			'options' 			=> [
				'none' 		=> __( 'None', 'elementor-extras' ),
				'tablet' 	=> __( 'Mobile and tablet', 'elementor-extras' ),
				'mobile' 	=> __( 'Mobile only', 'elementor-extras' ),
			],
			'condition' => [
				'enable!' => '',
			],
			'frontend_available' => true,
		];

		$controls['parent_selector'] = [
			'label'			=> _x( 'Parent Selector', 'Sticky Control', 'elementor-extras' ),
			'description'	=> __( 'CSS selector for the parent', 'elementor-extras' ),
			'type' 			=> Controls_Manager::TEXT,
			'default' 		=> '',
			'frontend_available'	=> true,
			'condition' 	=> [
				'parent' 	=> 'custom'
			]
		];

		$controls['offset'] = [
			'label' 	=> _x( 'Offset Top', 'Sticky Control', 'elementor-extras' ),
			'type' 		=> Controls_Manager::SLIDER,
			'range' 	=> [
				'px' 	=> [
					'max' => 100,
				],
			],
			'default' 	=> [
				'size' 	=> 0,
			],
			'condition'		=> [
	        	'enable!' => '',
	        ],
	        'frontend_available' => true,
		];

		return $controls;
	}
}
