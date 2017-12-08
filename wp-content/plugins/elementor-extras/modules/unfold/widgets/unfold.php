<?php
namespace ElementorExtras\Modules\Unfold\Widgets;

use ElementorExtras\Base\Extras_Widget;

// Elementor Classes
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Elementor Unfold
 *
 * @since 1.2.0
 */
class Unfold extends Extras_Widget {

	public function get_name() {
		return 'unfold';
	}

	public function get_title() {
		return __( 'Unfold', 'elementor-extras' );
	}

	public function get_icon() {
		return 'nicon nicon-unfold';
	}

	public function get_categories() {
		return [ 'elementor-extras' ];
	}

	/**
	 * A list of scripts that the widgets is depended in
	 * @since 1.2.0
	 **/
	public function get_script_depends() {
		return [ 'unfold' ];
	}

	public static function get_button_sizes() {
		return [
			'xs' => __( 'Extra Small', 'elementor-extras' ),
			'sm' => __( 'Small', 'elementor-extras' ),
			'md' => __( 'Medium', 'elementor-extras' ),
			'lg' => __( 'Large', 'elementor-extras' ),
			'xl' => __( 'Extra Large', 'elementor-extras' ),
		];
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Content', 'elementor-extras' ),
			]
		);

			$this->add_control(
				'content',
				[
					'label' 	=> '',
					'type' 		=> Controls_Manager::WYSIWYG,
					'default' 	=> __( 'And finally, maybe Medium purposely wanted undoing a Clap to be difficult to discover. If writers are now making money as a result of the number of Claps they receive, this should not be something easily undone. Undoing a Clap is difficult to discover on web too (you need to hover over the Clap icon), so we wouldn’t be surprised if this was a conscious decision. Taking back an applaud is also an uncommon occurrence.', 'elementor-extras' ),
				]
			);

			$this->add_control(
				'visible_type',
				[
					'label' 	=> __( 'Visible', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SELECT,
					'default' 	=> '',
					'options' 	=> [
						'' 		=> __( 'Percentage', 'elementor-extras' ),
						'lines' => __( 'Lines', 'elementor-extras' ),
					],
					'frontend_available' => 'true'
				]
			);

			$this->add_control(
				'visible_percentage',
				[
					'label' 	=> __( 'Visible Amount (%)', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SLIDER,
					'default'	=> [
						'size' 	=> 50,
					],
					'range' 	=> [
						'px' 	=> [
							'max' => 100,
							'min' => 10,
						],
					],
					'condition' => [
						'visible_type' => ''
					],
					'frontend_available' => true,
				]
			);

			$this->add_control(
				'visible_lines',
				[
					'label' 	=> __( 'Visible Amount (lines)', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SLIDER,
					'default'	=> [
						'size' 	=> 3,
					],
					'range' 	=> [
						'px' 	=> [
							'max' => 50,
							'min' => 1,
						],
					],
					'condition' => [
						'visible_type' => 'lines'
					],
					'frontend_available' => true,
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_settings',
			[
				'label' => __( 'Settings', 'elementor-extras' ),
			]
		);

			$this->start_controls_tabs( 'tabs_folds' );

			$this->start_controls_tab(
				'tab_unfold',
				[
					'label' => __( 'Unfold', 'elementor-extras' ),
				]
			);

				$this->add_control(
					'duration_unfold',
					[
						'label' 	=> __( 'Unfold Duration', 'elementor-extras' ),
						'type' 		=> Controls_Manager::SLIDER,
						'default'	=> [
							'size' 	=> 0.5,
						],
						'range' 	=> [
							'px' 	=> [
								'max' => 2,
								'min' => 0.1,
								'step'=> 0.1,
							],
						],
						'frontend_available' => true,
					]
				);

				$this->add_control(
					'animation_unfold',
					[
						'label'		=> __( 'Animation', 'elementor-extras' ),
						'type' 		=> Controls_Manager::SELECT,
						'default' 	=> 'Power4',
						'options' 	=> [
							'Power0' 		=> __( 'Linear', 'elementor-extras' ),
							'Power4' 		=> __( 'Break', 'elementor-extras' ),
							'Back' 			=> __( 'Back', 'elementor-extras' ),
							'Elastic' 		=> __( 'Elastic', 'elementor-extras' ),
							'Bounce' 		=> __( 'Bounce', 'elementor-extras' ),
							'SlowMo' 		=> __( 'SlowMo', 'elementor-extras' ),
							'SteppedEase' 	=> __( 'Step', 'elementor-extras' ),
						],
						'frontend_available' => true
					]
				);

				$this->add_control(
					'easing_unfold',
					[
						'label'		=> __( 'Easing', 'elementor-extras' ),
						'type' 		=> Controls_Manager::SELECT,
						'default' 	=> 'easeInOut',
						'options' 	=> [
							'easeInOut' 			=> __( 'Ease In Out', 'elementor-extras' ),
							'easeIn' 				=> __( 'Ease In', 'elementor-extras' ),
							'easeOut' 				=> __( 'Ease Out', 'elementor-extras' ),
						],
						'condition' => [
							'animation_unfold!' => [ 'SlowMo', 'SteppedEase' ]
						],
						'frontend_available' => true
					]
				);

				$this->add_control(
					'steps_unfold',
					[
						'label' 	=> __( 'Steps', 'elementor-extras' ),
						'type' 		=> Controls_Manager::SLIDER,
						'default'	=> [
							'size' 	=> 10,
						],
						'range' 	=> [
							'px' 	=> [
								'max' => 20,
								'min' => 5,
							],
						],
						'condition' => [
							'animation_unfold' => 'SteppedEase'
						],
						'frontend_available' => true,
					]
				);

				$this->add_control(
					'slow_unfold',
					[
						'label' 	=> __( 'Slow Amount', 'elementor-extras' ),
						'type' 		=> Controls_Manager::SLIDER,
						'default'	=> [
							'size' 	=> 0.7,
						],
						'range' 	=> [
							'px' 	=> [
								'max' => 1,
								'min' => 0.1,
								'step'=> 0.1,
							],
						],
						'condition' => [
							'animation_unfold' => 'SlowMo'
						],
						'frontend_available' => true,
					]
				);

			$this->end_controls_tab();

			$this->start_controls_tab(
				'tab_fold',
				[
					'label' => __( 'Fold', 'elementor-extras' ),
				]
			);

				$this->add_control(
					'duration_fold',
					[
						'label' 	=> __( 'Duration', 'elementor-extras' ),
						'type' 		=> Controls_Manager::SLIDER,
						'default'	=> [
							'size' 	=> 0.5,
						],
						'range' 	=> [
							'px' 	=> [
								'max' => 2,
								'min' => 0.1,
								'step'=> 0.1,
							],
						],
						'frontend_available' => true,
					]
				);

				$this->add_control(
					'animation_fold',
					[
						'label'		=> __( 'Animation', 'elementor-extras' ),
						'type' 		=> Controls_Manager::SELECT,
						'default' 	=> 'Power4',
						'options' 	=> [
							'Power0' 		=> __( 'Linear', 'elementor-extras' ),
							'Power4' 		=> __( 'Break', 'elementor-extras' ),
							'Back' 			=> __( 'Back', 'elementor-extras' ),
							'Elastic' 		=> __( 'Elastic', 'elementor-extras' ),
							'Bounce' 		=> __( 'Bounce', 'elementor-extras' ),
							'SlowMo' 		=> __( 'SlowMo', 'elementor-extras' ),
							'SteppedEase' 	=> __( 'Step', 'elementor-extras' ),
						],
						'frontend_available' => true
					]
				);

				$this->add_control(
					'easing_fold',
					[
						'label'		=> __( 'Easing', 'elementor-extras' ),
						'type' 		=> Controls_Manager::SELECT,
						'default' 	=> 'easeInOut',
						'options' 	=> [
							'easeInOut' 			=> __( 'Ease In Out', 'elementor-extras' ),
							'easeIn' 				=> __( 'Ease In', 'elementor-extras' ),
							'easeOut' 				=> __( 'Ease Out', 'elementor-extras' ),
						],
						'condition' => [
							'animation_fold!' => [ 'SlowMo', 'SteppedEase' ]
						],
						'frontend_available' => true
					]
				);

				$this->add_control(
					'steps_fold',
					[
						'label' 	=> __( 'Steps', 'elementor-extras' ),
						'type' 		=> Controls_Manager::SLIDER,
						'default'	=> [
							'size' 	=> 10,
						],
						'range' 	=> [
							'px' 	=> [
								'max' => 20,
								'min' => 5,
							],
						],
						'condition' => [
							'animation_fold' => 'SteppedEase'
						],
						'frontend_available' => true,
					]
				);

				$this->add_control(
					'slow_fold',
					[
						'label' 	=> __( 'Slow Amount', 'elementor-extras' ),
						'type' 		=> Controls_Manager::SLIDER,
						'default'	=> [
							'size' 	=> 0.7,
						],
						'range' 	=> [
							'px' 	=> [
								'max' => 1,
								'min' => 0.1,
								'step'=> 0.1,
							],
						],
						'condition' => [
							'animation_fold' => 'SlowMo'
						],
						'frontend_available' => true,
					]
				);

			$this->end_controls_tab();

			$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_separator_content',
			[
				'label' => __( 'Separator', 'elementor-extras' ),
			]
		);

			$this->add_control(
				'separator',
				[
					'label' 		=> __( 'Hide Separator', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SWITCHER,
					'default' 		=> '',
					'return_value' 	=> 'yes',
					'selectors' 	=> [
						"{{WRAPPER}} .ee-unfold__separator" => 'display: none',
					],
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_trigger',
			[
				'label' => __( 'Button', 'elementor-extras' ),
			]
		);

			$this->add_control(
				'text_closed',
				[
					'label' 		=> __( 'Closed Text', 'elementor-extras' ),
					'type' 			=> Controls_Manager::TEXT,
					'default' 		=> __( 'Read more', 'elementor-extras' ),
					'placeholder' 	=> __( 'Read more', 'elementor-extras' ),
					'frontend_available' => true,
				]
			);

			$this->add_control(
				'text_open',
				[
					'label' 		=> __( 'Open Text', 'elementor-extras' ),
					'type' 			=> Controls_Manager::TEXT,
					'default' 		=> __( 'Read less', 'elementor-extras' ),
					'placeholder' 	=> __( 'Read less', 'elementor-extras' ),
					'frontend_available' => true,
				]
			);

			$this->add_responsive_control(
				'align',
				[
					'label' 	=> __( 'Alignment', 'elementor-extras' ),
					'type' 		=> Controls_Manager::CHOOSE,
					'options' 	=> [
						'left'    	=> [
							'title' 	=> __( 'Left', 'elementor-extras' ),
							'icon' 		=> 'eicon-h-align-left',
						],
						'center' 	=> [
							'title' 	=> __( 'Center', 'elementor-extras' ),
							'icon' 		=> 'eicon-h-align-center',
						],
						'right' 	=> [
							'title' 	=> __( 'Right', 'elementor-extras' ),
							'icon' 		=> 'eicon-h-align-right',
						],
						'justify' 	=> [
							'title' 	=> __( 'Stretch', 'elementor-extras' ),
							'icon' 		=> 'eicon-h-align-stretch',
						],
					],
					'prefix_class' 	=> 'elementor%s-align-',
					'default' 		=> '',
				]
			);

			$this->add_control(
				'size',
				[
					'label' 	=> __( 'Size', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SELECT,
					'default' 	=> 'sm',
					'options' 	=> self::get_button_sizes(),
				]
			);

			$this->add_control(
				'icon',
				[
					'label' 		=> __( 'Icon', 'elementor-extras' ),
					'type' 			=> Controls_Manager::ICON,
					'label_block' 	=> true,
					'default' 		=> '',
				]
			);

			$this->add_control(
				'icon_align',
				[
					'label' 	=> __( 'Icon Position', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SELECT,
					'default' 	=> 'left',
					'options' 	=> [
						'left' 		=> __( 'Before', 'elementor-extras' ),
						'right' 	=> __( 'After', 'elementor-extras' ),
					],
					'condition' => [
						'icon!' => '',
					],
				]
			);

			$this->add_control(
				'icon_indent',
				[
					'label' 	=> __( 'Icon Spacing', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SLIDER,
					'range' 	=> [
						'px' 	=> [
							'max' => 50,
						],
					],
					'condition' => [
						'icon!' => '',
					],
					'selectors' => [
						'{{WRAPPER}} .ee-button .elementor-align-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .ee-button .elementor-align-icon-left' => 'margin-right: {{SIZE}}{{UNIT}};',
					],
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_content',
			[
				'label' => __( 'Content', 'elementor-extras' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_control(
				'content_color',
				[
					'label' 	=> __( 'Color', 'elementor-extras' ),
					'type' 		=> Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ee-unfold__content' => 'color: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				'content_background',
				[
					'label' 	=> __( 'Background Color', 'elementor-extras' ),
					'type' 		=> Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ee-unfold__content' => 'background-color: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				'content_padding',
				[
					'label' 		=> __( 'Padding', 'elementor-extras' ),
					'type' 			=> Controls_Manager::DIMENSIONS,
					'size_units' 	=> [ 'px' ],
					'selectors' 	=> [
						'{{WRAPPER}} .ee-unfold__content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' 		=> 'content_typography',
					'selector' 	=> '{{WRAPPER}} .ee-unfold__content',
					'scheme' 	=> Scheme_Typography::TYPOGRAPHY_3,
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_separator_style',
			[
				'label' 	=> __( 'Separator', 'elementor-extras' ),
				'tab' 		=> Controls_Manager::TAB_STYLE,
				'condition' => [
					'separator!' => 'yes'
				]
			]
		);

			$this->add_control(
				'separator_height',
				[
					'label' 	=> __( 'Height', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SLIDER,
					'default'	=> [
						'size' 	=> 48,
					],
					'range' 	=> [
						'px' 	=> [
							'max' => 100,
							'min' => 0,
						],
						'%' 	=> [
							'max' => 100,
							'min' => 0,
						],
					],
					'size_units' 	=> [ 'px', '%' ],
					'selectors' 	=> [
						'{{WRAPPER}} .ee-unfold__separator' => 'height: {{SIZE}}{{UNIT}}'
					],
					'condition' => [
						'separator!' => 'yes'
					]
				]
			);

			$this->add_group_control(
				Group_Control_Background::get_type(),
				[
					'name' 		=> 'gradient',
					'types' 	=> [ 'gradient', 'classic' ],
					'selector' 	=> '{{WRAPPER}} .ee-unfold__separator',
					'default'	=> 'gradient',
					'condition' => [
						'separator!' => 'yes'
					]
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_trigger_style',
			[
				'label' => __( 'Button', 'elementor-extras' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_control(
				'trigger_distance',
				[
					'label' 	=> __( 'Distance', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SLIDER,
					'default'	=> [
						'size' 	=> 24,
					],
					'range' 	=> [
						'px' 	=> [
							'max' => 96,
						],
					],
					'selectors' => [
						'{{WRAPPER}} .ee-unfold__trigger' => 'margin-top: {{SIZE}}px',
					]
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'typography',
					'label' => __( 'Typography', 'elementor-extras' ),
					'scheme' => Scheme_Typography::TYPOGRAPHY_4,
					'selector' => '{{WRAPPER}} a.ee-button, {{WRAPPER}} .ee-button',
				]
			);

			$this->start_controls_tabs( 'tabs_button_style' );

			$this->start_controls_tab(
				'tab_button_normal',
				[
					'label' => __( 'Normal', 'elementor-extras' ),
				]
			);

			$this->add_control(
				'button_text_color',
				[
					'label' => __( 'Text Color', 'elementor-extras' ),
					'type' => Controls_Manager::COLOR,
					'default' => '',
					'selectors' => [
						'{{WRAPPER}} a.ee-button, {{WRAPPER}} .ee-button' => 'color: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				'background_color',
				[
					'label' => __( 'Background Color', 'elementor-extras' ),
					'type' => Controls_Manager::COLOR,
					'scheme' => [
						'type' => Scheme_Color::get_type(),
						'value' => Scheme_Color::COLOR_4,
					],
					'selectors' => [
						'{{WRAPPER}} a.ee-button, {{WRAPPER}} .ee-button' => 'background-color: {{VALUE}};',
					],
				]
			);

			$this->end_controls_tab();

			$this->start_controls_tab(
				'tab_button_hover',
				[
					'label' => __( 'Hover', 'elementor-extras' ),
				]
			);

			$this->add_control(
				'hover_color',
				[
					'label' => __( 'Text Color', 'elementor-extras' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} a.ee-button:hover, {{WRAPPER}} .ee-button:hover' => 'color: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				'button_background_hover_color',
				[
					'label' => __( 'Background Color', 'elementor-extras' ),
					'type' => Controls_Manager::COLOR,
					'scheme' 	=> [
						'type' 	=> Scheme_Color::get_type(),
						'value' => Scheme_Color::COLOR_4,
					],
					'selectors' => [
						'{{WRAPPER}} a.ee-button:hover, {{WRAPPER}} .ee-button:hover' => 'background-color: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				'button_hover_border_color',
				[
					'label' => __( 'Border Color', 'elementor-extras' ),
					'type' => Controls_Manager::COLOR,
					'condition' => [
						'border_border!' => '',
					],
					'selectors' => [
						'{{WRAPPER}} a.ee-button:hover, {{WRAPPER}} .ee-button:hover' => 'border-color: {{VALUE}};',
					],
				]
			);

			$this->end_controls_tab();

			$this->end_controls_tabs();

			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'border',
					'label' => __( 'Border', 'elementor-extras' ),
					'placeholder' => '1px',
					'default' => '1px',
					'selector' => '{{WRAPPER}} .ee-button',
					'separator' => 'before',
				]
			);

			$this->add_control(
				'border_radius',
				[
					'label' => __( 'Border Radius', 'elementor-extras' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%' ],
					'selectors' => [
						'{{WRAPPER}} a.ee-button, {{WRAPPER}} .ee-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'button_box_shadow',
					'selector' => '{{WRAPPER}} .ee-button',
				]
			);

			$this->add_control(
				'text_padding',
				[
					'label' => __( 'Text Padding', 'elementor-extras' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em', '%' ],
					'selectors' => [
						'{{WRAPPER}} a.ee-button, {{WRAPPER}} .ee-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'separator' => 'before',
				]
			);

		$this->end_controls_section();
		
	}

	protected function render() {
		$settings = $this->get_settings();

		$this->add_render_attribute( 'wrapper', 'class', 'ee-unfold' );
		$this->add_render_attribute( 'mask', 'class', 'ee-unfold__mask' );
		$this->add_render_attribute( 'button', 'class', 'ee-button' );
		$this->add_render_attribute( 'button-wrapper', 'class', 'ee-button-wrapper' );
		$this->add_render_attribute( 'separator', 'class', 'ee-unfold__separator' );

		$this->add_inline_editing_attributes( 'content', 'advanced' );
		$this->add_render_attribute( 'content', 'class', 'ee-unfold__content' );
		$this->add_render_attribute( 'trigger', 'class', 'ee-unfold__trigger' );

		if ( ! empty( $settings['size'] ) ) {
			$this->add_render_attribute( 'button', 'class', 'ee-size-' . $settings['size'] );
		}

		?>

		<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
			<div <?php echo $this->get_render_attribute_string( 'mask' ); ?>>
				<div <?php echo $this->get_render_attribute_string( 'content' ); ?>>
					<?php echo $this->parse_text_editor( $settings['content'] ); ?>
				</div>
				<div <?php echo $this->get_render_attribute_string( 'separator' ); ?>></div>
			</div>
			<div <?php echo $this->get_render_attribute_string( 'trigger' ); ?>>
				<span <?php echo $this->get_render_attribute_string( 'button-wrapper' ); ?>>
					<span <?php echo $this->get_render_attribute_string( 'button' ); ?>>
						<?php $this->render_text(); ?>
					</span>
				</span>
			</div>
		</div>

		<?php

	}

	protected function _content_template() { ?>
		<div class="ee-unfold">
			<div class="ee-unfold__mask">
				<div class="ee-unfold__content elementor-inline-editing" data-elementor-inline-editing-toolbar="advanced" data-elementor-setting-key="content">
					{{{ settings.content }}}
				</div>
				<div class="ee-unfold__separator"></div>
			</div>
			<div class="ee-unfold__trigger">
				<span class="ee-button-wrapper">
					<span class="ee-button ee-size-{{ settings.size }}">
						<span class="ee-button-content-wrapper">
							<# if ( settings.icon ) { #>
							<span class="ee-button-icon elementor-align-icon-{{ settings.icon_align }}">
								<i class="{{ settings.icon }}"></i>
							</span>
							<# } #>
							<span class="ee-button-text">{{{ settings.text_closed }}}</span>
						</span>
					</span>
				</span>
			</div>
		</div>

		<?php
	}

	protected function render_text() {
		$settings = $this->get_settings();
		$this->add_render_attribute( 'content-wrapper', 'class', 'ee-button-content-wrapper' );
		$this->add_render_attribute( 'icon-align', 'class', 'elementor-align-icon-' . $settings['icon_align'] );
		$this->add_render_attribute( 'icon-align', 'class', 'ee-button-icon' );
		?>
		<span <?php echo $this->get_render_attribute_string( 'content-wrapper' ); ?>>
			<?php if ( ! empty( $settings['icon'] ) ) : ?>
			<span <?php echo $this->get_render_attribute_string( 'icon-align' ); ?>>
				<i class="<?php echo esc_attr( $settings['icon'] ); ?>"></i>
			</span>
			<?php endif; ?>
			<span class="ee-button-text"><?php echo $settings['text_closed']; ?></span>
		</span>
		<?php
	}
}
