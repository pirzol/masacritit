<?php
namespace ElementorExtras\Modules\Heading\Widgets;

use ElementorExtras\Base\Extras_Widget;

// Elementor Classes
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Scheme_Typography;
use Elementor\Scheme_Color;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Elementor Text_Divider
 *
 * @since 0.1.0
 */
class Text_Divider extends Extras_Widget {

	public function get_name() {
		return 'text-divider';
	}

	public function get_title() {
		return __( 'Text Divider', 'elementor-extras' );
	}

	public function get_icon() {
		return 'nicon nicon-divider-text';
	}

	public function get_categories() {
		return [ 'elementor-extras' ];
	}

	/**
	 * A list of scripts that the widgets is depended in
	 * @since 0.1.0
	 **/
	public function get_script_depends() {
		return [ 'elementor-extras' ];
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'section_text',
			[
				'label' => __( 'General', 'elementor-extras' ),
			]
		);
		
			$this->add_control(
				'text',
				[
					'label'			=> __( 'Text', 'elementor-extras' ),
					'type' 			=> Controls_Manager::TEXT,
					'default'		=> __( 'Text Divider', 'elementor-extras' ),
				]
			);

			$this->add_control(
				'text_html_tag',
				[
					'label' => __( 'HTML Tag', 'elementor-extras' ),
					'type' => Controls_Manager::SELECT,
					'options' => [
						'h1' => __( 'H1', 'elementor' ),
						'h2' => __( 'H2', 'elementor' ),
						'h3' => __( 'H3', 'elementor' ),
						'h4' => __( 'H4', 'elementor' ),
						'h5' => __( 'H5', 'elementor' ),
						'h6' => __( 'H6', 'elementor' ),
						'div' => __( 'div', 'elementor' ),
						'span' => __( 'span', 'elementor' ),
						'p' => __( 'p', 'elementor' ),
					],
					'default' => 'h6',
				]
			);

			$this->add_responsive_control(
				'horizontal_align',
				[
					'label' 		=> __( 'Alignment', 'elementor-extras' ),
					'type' 			=> Controls_Manager::CHOOSE,
					'default' 		=> 'center',
					'options' 		=> [
						'flex-start'    => [
							'title' 	=> __( 'Left', 'elementor-extras' ),
							'icon' 		=> 'eicon-h-align-left',
						],
						'center' 		=> [
							'title' 	=> __( 'Center', 'elementor-extras' ),
							'icon' 		=> 'eicon-h-align-center',
						],
						'flex-end' 		=> [
							'title' 	=> __( 'Right', 'elementor-extras' ),
							'icon' 		=> 'eicon-h-align-right',
						],
					],
					'selectors' => [
						'{{WRAPPER}} .ee-text-divider' => 'justify-content: {{VALUE}};',
					],
				]
			);

		$this->end_controls_section();


		$this->start_controls_section(
			'section_text_style',
			[
				'label' => __( 'Text', 'elementor-extras' ),
				'tab' 		=> Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_responsive_control(
				'align',
				[
					'label' 		=> __( 'Position', 'elementor-extras' ),
					'type' 			=> Controls_Manager::CHOOSE,
					'default' 		=> '',
					'options' 		=> [
						'left'    		=> [
							'title' 	=> __( 'Left', 'elementor-extras' ),
							'icon' 		=> 'eicon-h-align-left',
						],
						'center' 		=> [
							'title' 	=> __( 'Center', 'elementor-extras' ),
							'icon' 		=> 'eicon-h-align-center',
						],
						'right' 		=> [
							'title' 	=> __( 'Right', 'elementor-extras' ),
							'icon' 		=> 'eicon-h-align-right',
						],
					],
					'prefix_class'		=> 'ee-text-divider--'
				]
			);

			$this->add_responsive_control(
				'text_space',
				[
					'label' 	=> __( 'Text Spacing', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SLIDER,
					'default' 	=> [
						'size' 	=> 6,
					],
					'range' 	=> [
						'px' 	=> [
							'min' 	=> 0,
							'max' 	=> 100,
						],
					],
					'selectors' 	=> [
						'{{WRAPPER}} .ee-text-divider__text' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}}.ee-text-divider--left .ee-text-divider__text' => 'margin-left: 0; margin-right: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}}.ee-text-divider--right .ee-text-divider__text' => 'margin-right: 0; margin-left: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->add_responsive_control(
				'text-padding',
				[
					'label' 		=> __( 'Padding', 'elementor-extras' ),
					'type' 			=> Controls_Manager::DIMENSIONS,
					'size_units' 	=> [ 'px', '%', 'em' ],
					'selectors' 	=> [
						'{{WRAPPER}} .ee-text-divider__text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_control(
				'text_color',
				[
					'label' 	=> __( 'Color', 'elementor-extras' ),
					'type' 		=> Controls_Manager::COLOR,
					'scheme' 	=> [
						'type' 	=> Scheme_Color::get_type(),
						'value' => Scheme_Color::COLOR_3,
					],
					'selectors' => [
						'{{WRAPPER}} .ee-text-divider__text' => 'color: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				'text_background_color',
				[
					'label' 	=> __( 'Background Color', 'elementor-extras' ),
					'type' 		=> Controls_Manager::COLOR,
					'default'	=> '',
					'selectors' => [
						'{{WRAPPER}} .ee-text-divider__text' => 'background-color: {{VALUE}};',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' 		=> 'text_border',
					'label' 	=> __( 'Text Border', 'elementor-extras' ),
					'selector' 	=> '{{WRAPPER}} .ee-text-divider__text',
				]
			);

			$this->add_control(
				'text_border_radius',
				[
					'label' 		=> __( 'Border Radius', 'elementor-extras' ),
					'type' 			=> Controls_Manager::DIMENSIONS,
					'size_units' 	=> [ 'px', '%' ],
					'selectors' 	=> [
						'{{WRAPPER}} .ee-text-divider__text' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name' 		=> 'text_box_shadow',
					'selector' 	=> '{{WRAPPER}} .ee-text-divider__text',
				]
			);

			$this->add_group_control(
				Group_Control_Text_Shadow::get_type(),
				[
					'name' 		=> 'text_shadow',
					'selector' 	=> '{{WRAPPER}} .ee-text-divider__text',
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' 		=> 'text_typography',
					'selector' 	=> '{{WRAPPER}} .ee-text-divider__text',
					'scheme' 	=> Scheme_Typography::TYPOGRAPHY_3,
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_divider_style',
			[
				'label' => __( 'Divider', 'elementor-extras' ),
				'tab' 		=> Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_responsive_control(
				'vertical_align',
				[
					'label' 		=> __( 'Vertical Alignment', 'elementor-extras' ),
					'type' 			=> Controls_Manager::CHOOSE,
					'default' 		=> '',
					'options' 		=> [
						'flex-start'    => [
							'title' 	=> __( 'Top', 'elementor-extras' ),
							'icon' 		=> 'eicon-v-align-top',
						],
						'center' 		=> [
							'title' 	=> __( 'Middle', 'elementor-extras' ),
							'icon' 		=> 'eicon-v-align-middle',
						],
						'flex-end' 		=> [
							'title' 	=> __( 'Bottom', 'elementor-extras' ),
							'icon' 		=> 'eicon-v-align-bottom',
						],
						'baseline' 		=> [
							'title' 	=> __( 'Baseline', 'elementor-extras' ),
							'icon' 		=> 'nicon nicon-v-align-baseline',
						],
					],
					'selectors' => [
						'{{WRAPPER}} .ee-text-divider' => 'align-items: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				'divider_width',
				[
					'label' 	=> __( 'Width', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SLIDER,
					'default' 	=> [
						'size' 	=> 1000,
					],
					'range' 	=> [
						'px' 	=> [
							'min' 	=> 0,
							'max' 	=> 1000,
						],
					],
					'selectors' => [
						'{{WRAPPER}} .ee-text-divider__before' => 'max-width: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .ee-text-divider__after' => 'max-width: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->add_control(
				'divider_height',
				[
					'label' 	=> __( 'Height', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SLIDER,
					'default' 	=> [
						'size' 	=> 1,
					],
					'range' 	=> [
						'px' 	=> [
							'min' 	=> 1,
							'max' 	=> 100,
						],
					],
					'selectors' 	=> [
						'{{WRAPPER}} .ee-text-divider__before' => 'height: {{SIZE}}{{UNIT}}',
						'{{WRAPPER}} .ee-text-divider__after' => 'height: {{SIZE}}{{UNIT}}',
					],
				]
			);

			$this->add_control(
				'divider_background_color',
				[
					'label' 	=> __( 'Background Color', 'elementor-extras' ),
					'type' 		=> Controls_Manager::COLOR,
					'scheme' 	=> [
						'type' 	=> Scheme_Color::get_type(),
						'value' => Scheme_Color::COLOR_4,
					],
					'selectors' => [
						'{{WRAPPER}} .ee-text-divider__divider' => 'background-color: {{VALUE}};',
					],
				]
			);

			$this->start_controls_tabs( 'divider_style_tabs' );

			$this->start_controls_tab(
				'divider_before_tab',
				[
					'label' 		=> __( 'Before', 'elementor-extras' ),
					'condition'		=> [
						'align!'	=> 'left',
					]
				]
			);

				$this->add_group_control(
					Group_Control_Background::get_type(),
					[
						'name' 		=> 'divider_before_background',
						'types' 	=> [ 'none', 'classic', 'gradient' ],
						'selector' 	=> '{{WRAPPER}} .ee-text-divider__divider.ee-text-divider__before',
						'condition'		=> [
							'align!'	=> 'left'
						]
					]
				);

			$this->end_controls_tab();

			$this->start_controls_tab(
				'divider_after_tab',
				[
					'label' 		=> __( 'After', 'elementor-extras' ),
					'condition'		=> [
						'align!'	=> 'right'
					]
				]
			);

				$this->add_group_control(
					Group_Control_Background::get_type(),
					[
						'name' 		=> 'divider_after_background',
						'types' 	=> [ 'none', 'classic', 'gradient' ],
						'selector' 	=> '{{WRAPPER}} .ee-text-divider__divider.ee-text-divider__after',
						'condition'		=> [
							'align!'	=> 'right'
						]
					]
				);

			$this->end_controls_tab();

			$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings();

		$this->add_inline_editing_attributes( 'text', 'basic' );
		$this->add_render_attribute( 'text', 'class', 'ee-text-divider__text' );
		$this->add_render_attribute( 'wrapper', 'class', 'ee-text-divider' );
		$this->add_render_attribute( 'before', 'class', [
			'ee-text-divider__divider',
			'ee-text-divider__before',
		] );

		$this->add_render_attribute( 'after', 'class', [
			'ee-text-divider__divider',
			'ee-text-divider__after',
		] );

		$text_html = sprintf( '<%1$s %2$s>%3$s</%1$s>', $settings['text_html_tag'], $this->get_render_attribute_string( 'text' ), $this->parse_text_editor( $settings['text'] ) );

		?>
		<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
			<div <?php echo $this->get_render_attribute_string( 'before' ); ?>></div>
			
			<?php echo $text_html; ?>

			<div <?php echo $this->get_render_attribute_string( 'after' ); ?>></div>
		</div>
		<?php
		
	}

	protected function _content_template() {
		?>
		<div class="ee-text-divider">
			<div class="ee-text-divider__divider ee-text-divider__before"></div>
			<{{ settings.text_html_tag }} class="ee-text-divider__text elementor-inline-editing" data-elementor-inline-editing-toolbar="basic" data-elementor-setting-key="text">{{{ settings.text }}}</{{ settings.text_html_tag }}>
			<div class="ee-text-divider__divider ee-text-divider__after"></div>
		</div>
		<?php
	}
}
