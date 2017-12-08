<?php
namespace ElementorExtras\Modules\Gallery\Widgets;

use ElementorExtras\Base\Extras_Widget;

// Elementor Classes
use Elementor\Controls_Manager;
use Elementor\Control_Media;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Scheme_Typography;
use Elementor\Scheme_Color;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Elementor Gallery_Slider
 *
 * @since 0.1.0
 */
class Gallery_Slider extends Extras_Widget {

	public function get_name() {
		return 'gallery-slider';
	}

	public function get_title() {
		return __( 'Gallery Slider', 'elementor-extras' );
	}

	public function get_icon() {
		return 'nicon nicon-slider-gallery';
	}

	public function get_categories() {
		return [ 'elementor-extras' ];
	}

	public function get_script_depends() {
		return [ 'jquery-slick' ];
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'section_gallery',
			[
				'label' => __( 'Gallery', 'elementor-extras' ),
			]
		);

			$this->add_control(
				'wp_gallery',
				[
					'label' 	=> __( 'Add Images', 'elementor-extras' ),
					'type' 		=> Controls_Manager::GALLERY,
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_thumbnails',
			[
				'label' => __( 'Thumbnails', 'elementor-extras' ),
			]
		);

			$this->add_group_control(
				Group_Control_Image_Size::get_type(),
				[
					'name' 		=> 'thumbnail',
					'label'		=> __( 'Thumbnails Size', 'elementor-extras' ),
					'exclude' 	=> [ 'custom' ],
				]
			);

			$this->add_responsive_control(
				'columns',
				[
					'label' 	=> __( 'Columns', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SELECT,
					'default' 	=> '3',
					'tablet_default' 	=> '6',
					'mobile_default' 	=> '4',
					'options' 			=> [
						'1' => '1',
						'2' => '2',
						'3' => '3',
						'4' => '4',
						'5' => '5',
						'6' => '6',
					],
					'frontend_available' => true,
				]
			);

			$this->add_control(
				'gallery_rand',
				[
					'label' 	=> __( 'Ordering', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SELECT,
					'options' 	=> [
						'' 		=> __( 'Default', 'elementor-extras' ),
						'rand' 	=> __( 'Random', 'elementor-extras' ),
					],
					'default' 	=> '',
				]
			);

			$this->add_control(
				'view',
				[
					'label' 	=> __( 'View', 'elementor-extras' ),
					'type' 		=> Controls_Manager::HIDDEN,
					'default' 	=> 'traditional',
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_preview',
			[
				'label' => __( 'Preview', 'elementor-extras' ),
			]
		);

			$this->add_group_control(
				Group_Control_Image_Size::get_type(),
				[
					'name' 		=> 'preview',
					'label'		=> __( 'Preview Size', 'elementor-extras' ),
					'default'	=> 'full',
					'exclude' 	=> [ 'custom' ],
				]
			);

			$this->add_control(
				'link_to',
				[
					'label' 	=> __( 'Link to', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SELECT,
					'default' 	=> 'none',
					'options' 	=> [
						'none' 		=> __( 'None', 'elementor-extras' ),
						'file' 		=> __( 'Media File', 'elementor-extras' ),
						'custom' 	=> __( 'Custom URL', 'elementor-extras' ),
					],
				]
			);

			$this->add_control(
				'link',
				[
					'label' 		=> 'Link to',
					'type' 			=> Controls_Manager::URL,
					'placeholder' 	=> __( 'http://your-link.com', 'elementor-extras' ),
					'condition' 	=> [
						'link_to' 	=> 'custom',
					],
					'show_label' 	=> false,
				]
			);

			$this->add_control(
				'open_lightbox',
				[
					'label' 	=> __( 'Lightbox', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SELECT,
					'default' 	=> 'default',
					'options' 	=> [
						'default' 	=> __( 'Default', 'elementor-extras' ),
						'yes' 		=> __( 'Yes', 'elementor-extras' ),
						'no' 		=> __( 'No', 'elementor-extras' ),
					],
					'condition' => [
						'link_to' => 'file',
					],
				]
			);

			$this->add_control(
				'preview_stretch',
				[
					'label' 	=> __( 'Image Stretch', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SELECT,
					'default' 	=> 'yes',
					'options' 	=> [
						'no' 	=> __( 'No', 'elementor-extras' ),
						'yes' 	=> __( 'Yes', 'elementor-extras' ),
					],
				]
			);

			$this->add_control(
				'caption_type',
				[
					'label' 	=> __( 'Caption', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SELECT,
					'default' 	=> '',
					'options' 	=> [
						'' 				=> __( 'None', 'elementor-extras' ),
						'title' 		=> __( 'Title', 'elementor-extras' ),
						'caption' 		=> __( 'Caption', 'elementor-extras' ),
						'description' 	=> __( 'Description', 'elementor-extras' ),
					],
				]
			);

			$this->add_control(
				'pause_on_hover',
				[
					'label' 	=> __( 'Pause on Hover', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SELECT,
					'default' 	=> 'yes',
					'options' 	=> [
						'yes' 	=> __( 'Yes', 'elementor-extras' ),
						'no' 	=> __( 'No', 'elementor-extras' ),
					],
					'frontend_available' => true,
				]
			);

			$this->add_control(
				'autoplay',
				[
					'label' 	=> __( 'Autoplay', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SELECT,
					'default' 	=> 'yes',
					'options' 	=> [
						'yes' 	=> __( 'Yes', 'elementor-extras' ),
						'no' 	=> __( 'No', 'elementor-extras' ),
					],
					'frontend_available' => true,
				]
			);

			$this->add_control(
				'autoplay_speed',
				[
					'label' 	=> __( 'Autoplay Speed', 'elementor-extras' ),
					'type' 		=> Controls_Manager::NUMBER,
					'default' 	=> 5000,
					'frontend_available' => true,
				]
			);

			$this->add_control(
				'infinite',
				[
					'label' 	=> __( 'Infinite Loop', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SELECT,
					'default' 	=> 'yes',
					'options' 	=> [
						'yes' 	=> __( 'Yes', 'elementor-extras' ),
						'no' 	=> __( 'No', 'elementor-extras' ),
					],
					'frontend_available' => true,
				]
			);

			$this->add_control(
				'adaptive_height',
				[
					'label' 	=> __( 'Adaptive Height', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SELECT,
					'default' 	=> 'yes',
					'options' 	=> [
						'yes' 	=> __( 'Yes', 'elementor-extras' ),
						'no' 	=> __( 'No', 'elementor-extras' ),
					],
					'frontend_available' => true,
				]
			);

			$this->add_control(
				'effect',
				[
					'label' 	=> __( 'Effect', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SELECT,
					'default' 	=> 'slide',
					'options' 	=> [
						'slide' 	=> __( 'Slide', 'elementor-extras' ),
						'fade' 		=> __( 'Fade', 'elementor-extras' ),
					],
					'frontend_available' => true,
				]
			);

			$this->add_control(
				'speed',
				[
					'label' 	=> __( 'Animation Speed', 'elementor-extras' ),
					'type' 		=> Controls_Manager::NUMBER,
					'default' 	=> 500,
					'frontend_available' => true,
				]
			);

			$this->add_control(
				'direction',
				[
					'label' 	=> __( 'Direction', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SELECT,
					'default' 	=> 'ltr',
					'options' 	=> [
						'ltr' 	=> __( 'Left', 'elementor-extras' ),
						'rtl' 	=> __( 'Right', 'elementor-extras' ),
					],
					'frontend_available' => true,
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_preview',
			[
				'label' 	=> __( 'Preview', 'elementor-extras' ),
				'tab' 		=> Controls_Manager::TAB_STYLE,
			]
		);

			$this->start_controls_tabs( 'preview_tabs' );

				$this->start_controls_tab( 'preview_layout', [ 'label' => __( 'Layout', 'elementor-extras' ) ] );

					$this->add_responsive_control(
						'preview_position',
						[
							'label' 	=> __( 'Position', 'elementor-extras' ),
							'type' 		=> Controls_Manager::SELECT,
							'default' 	=> 'left',
							'tablet_default' 	=> 'top',
							'mobile_default' 	=> 'top',
							'options' 	=> [
								'top' 		=> __( 'Top', 'elementor-extras' ),
								'right' 	=> __( 'Right', 'elementor-extras' ),
								'left' 		=> __( 'Left', 'elementor-extras' ),
							],
							'prefix_class'	=> 'elementor-gallery-slider%s--'
						]
					);

					$this->add_responsive_control(
						'preview_width',
						[
							'label' 	=> __( 'Width (%)', 'elementor-extras' ),
							'type' 		=> Controls_Manager::SLIDER,
							'range' 	=> [
								'px' 	=> [
									'min' => 0,
									'max' => 100,
								],
							],
							'default' 	=> [
								'size' 	=> 70,
							],
							'selectors'		=> [
								'{{WRAPPER}}.elementor-gallery-slider--left .elementor-gallery-slider__preview' => 'width: {{SIZE}}%',
								'{{WRAPPER}}.elementor-gallery-slider--right .elementor-gallery-slider__preview' => 'width: {{SIZE}}%',
								'{{WRAPPER}}.elementor-gallery-slider--left .elementor-gallery-slider__gallery' => 'width: calc(100% - {{SIZE}}%)',
								'{{WRAPPER}}.elementor-gallery-slider--right .elementor-gallery-slider__gallery' => 'width: calc(100% - {{SIZE}}%)',

								// '(tablet){{WRAPPER}}.elementor-gallery-slider--left .elementor-gallery-slider__preview' => 'width: 100%',
								// '(tablet){{WRAPPER}}.elementor-gallery-slider--right .elementor-gallery-slider__preview' => 'width: 100%',
								// '(tablet){{WRAPPER}}.elementor-gallery-slider--left .elementor-gallery-slider__gallery' => 'width: 100%',
								// '(tablet){{WRAPPER}}.elementor-gallery-slider--right .elementor-gallery-slider__gallery' => 'width: 100%',
							]
						]
					);

					$preview_horizontal_margin = is_rtl() ? 'margin-right' : 'margin-left';
					$preview_horizontal_padding = is_rtl() ? 'padding-right' : 'padding-left';

					$this->add_responsive_control(
						'preview_spacing',
						[
							'label' 	=> __( 'Spacing', 'elementor-extras' ),
							'type' 		=> Controls_Manager::SLIDER,
							'range' 	=> [
								'px' 	=> [
									'min' => 0,
									'max' => 200,
								],
							],
							'default' 	=> [
								'size' 	=> 24,
							],
							'selectors' => [
								'{{WRAPPER}}.elementor-gallery-slider--left .elementor-gallery-slider > *,
								{{WRAPPER}}.elementor-gallery-slider--right  .elementor-gallery-slider > *' => $preview_horizontal_padding . ': {{SIZE}}{{UNIT}};',

								'{{WRAPPER}}.elementor-gallery-slider--left .elementor-gallery-slider,
								{{WRAPPER}}.elementor-gallery-slider--right .elementor-gallery-slider' => $preview_horizontal_margin . ': -{{SIZE}}{{UNIT}};',

								'(dekstop){{WRAPPER}}.elementor-gallery-slider--top  .elementor-gallery-slider > .elementor-gallery-slider__preview' => 'margin-bottom: {{SIZE}}{{UNIT}};',
								'(tablet){{WRAPPER}}.elementor-gallery-slider-tablet--top  .elementor-gallery-slider > .elementor-gallery-slider__preview' => 'margin-bottom: {{SIZE}}{{UNIT}};',
								'(mobile){{WRAPPER}}.elementor-gallery-slider-mobile--top  .elementor-gallery-slider > .elementor-gallery-slider__preview' => 'margin-bottom: {{SIZE}}{{UNIT}};',
							],
						]
					);

				$this->end_controls_tab();

				$this->start_controls_tab( 'preview_images', [ 'label' => __( 'Images', 'elementor-extras' ) ] );

					$this->add_group_control(
						Group_Control_Border::get_type(),
						[
							'name' 		=> 'preview_border',
							'label' 	=> __( 'Image Border', 'elementor-extras' ),
							'selector' 	=> '{{WRAPPER}} .slick-slider',
							'separator' => '',
						]
					);

					$this->add_control(
						'preview_border_radius',
						[
							'label' 		=> __( 'Border Radius', 'elementor-extras' ),
							'type' 			=> Controls_Manager::DIMENSIONS,
							'size_units' 	=> [ 'px', '%' ],
							'selectors' 	=> [
								'{{WRAPPER}} .slick-slide' 	=> 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							],
						]
					);

					$this->add_group_control(
						Group_Control_Box_Shadow::get_type(),
						[
							'name' 		=> 'preview_box_shadow',
							'selector' 	=> '{{WRAPPER}} .slick-slider',
							'separator'	=> '',
						]
					);

				$this->end_controls_tab();

			$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_thumbnails',
			[
				'label' 	=> __( 'Thumbnails', 'elementor-extras' ),
				'tab' 		=> Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_control(
				'image_align',
				[
					'label' 		=> __( 'Alignment', 'elementor-extras' ),
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
						'justify' 		=> [
							'title' 	=> __( 'Stretch', 'elementor-extras' ),
							'icon' 		=> 'eicon-h-align-stretch',
						],
					],
					'prefix_class'		=> 'elementor-gallery-extra-'
				]
			);
			
			$this->add_control(
				'image_vertical_align',
				[
					'label' 		=> __( 'Vertical Alignment', 'elementor-extras' ),
					'type' 			=> Controls_Manager::CHOOSE,
					'default' 		=> '',
					'options' 		=> [
						'top'    		=> [
							'title' 	=> __( 'Top', 'elementor-extras' ),
							'icon' 		=> 'eicon-v-align-top',
						],
						'middle' 		=> [
							'title' 	=> __( 'Middle', 'elementor-extras' ),
							'icon' 		=> 'eicon-v-align-middle',
						],
						'bottom' 		=> [
							'title' 	=> __( 'Bottom', 'elementor-extras' ),
							'icon' 		=> 'eicon-v-align-bottom',
						],
						'stretch' 		=> [
							'title' 	=> __( 'Stretch', 'elementor-extras' ),
							'icon' 		=> 'eicon-v-align-stretch',
						],
					],
					'prefix_class'		=> 'elementor-gallery-extra-'
				]
			);

			$this->add_responsive_control(
				'image_stretch_ratio',
				[
					'label' 	=> __( 'Image Size Ratio', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SLIDER,
					'default'	=> [
						'size'	=> '100'
						],
					'range' 	=> [
						'px' 	=> [
							'min'	=> 10,
							'max' 	=> 200,
						],
					],
					'condition' => [
						'image_vertical_align' => 'stretch',
					],
					'selectors' => [
						'{{WRAPPER}} .gallery-item .gallery-icon::before' => 'padding-bottom: {{SIZE}}%;',
					],
				]
			);

			$columns_horizontal_margin = is_rtl() ? 'margin-right' : 'margin-left';
			$columns_horizontal_padding = is_rtl() ? 'padding-right' : 'padding-left';

			$this->add_responsive_control(
				'image_horizontal_spacing',
				[
					'label' 	=> __( 'Horizontal spacing', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SLIDER,
					'range' 	=> [
						'px' 	=> [
							'min' => 0,
							'max' => 200,
						],
					],
					'default' 	=> [
						'size' 	=> 0,
					],
					'selectors' => [
						'{{WRAPPER}} .gallery-item' => $columns_horizontal_padding . ': {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .gallery' 		=> $columns_horizontal_margin . ': -{{SIZE}}{{UNIT}};',

						'(desktop){{WRAPPER}} .gallery-item' 		=> 'width: calc( 100% / {{columns.SIZE}} );',
						'(tablet){{WRAPPER}} .gallery-item' 		=> 'width: calc( 100% / {{columns_tablet.SIZE}} );',
						'(mobile){{WRAPPER}} .gallery-item' 		=> 'width: calc( 100% / {{columns_mobile.SIZE}} );',
					],
				]
			);

			$this->add_responsive_control(
				'image_vertical_spacing',
				[
					'label' 	=> __( 'Vertical spacing', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SLIDER,
					'range' 	=> [
						'px' 	=> [
							'min' => 0,
							'max' => 200,
						],
					],
					'default' 	=> [
						'size' 	=> '',
					],
					'selectors' => [
						'{{WRAPPER}} .gallery-item' => 'padding-bottom: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .gallery' 		=> 'margin-bottom: -{{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' 		=> 'image_border',
					'label' 	=> __( 'Image Border', 'elementor-extras' ),
					'selector' 	=> '{{WRAPPER}} .gallery-item img',
					'separator' => '',
				]
			);

			$this->add_control(
				'image_border_radius',
				[
					'label' 		=> __( 'Border Radius', 'elementor-extras' ),
					'type' 			=> Controls_Manager::DIMENSIONS,
					'size_units' 	=> [ 'px', '%' ],
					'selectors' 	=> [
						'{{WRAPPER}} .gallery-item img' 	=> 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->start_controls_tabs( 'image_style' );

				$this->start_controls_tab( 'image_style_default', [ 'label' => __( 'Default', 'elementor-extras' ), ] );

					$this->add_control(
						'image_background_color',
						[
							'label' 	=> __( 'Background Color', 'elementor-extras' ),
							'type' 		=> Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .gallery-icon' => 'background-color: {{VALUE}};',
							],
						]
					);

					$this->add_responsive_control(
						'image_opacity',
						[
							'label' 	=> __( 'Opacity (%)', 'elementor-extras' ),
							'type' 		=> Controls_Manager::SLIDER,
							'default' 	=> [
								'size' 	=> 0.5,
							],
							'range' 	=> [
								'px' 	=> [
									'max' 	=> 1,
									'min' 	=> 0,
									'step' 	=> 0.01,
								],
							],
							'selectors' => [
								'{{WRAPPER}} .gallery-item img' => 'opacity: {{SIZE}}',
							],
						]
					);

					$this->add_group_control(
						Group_Control_Box_Shadow::get_type(),
						[
							'name' 		=> 'image_box_shadow',
							'selector' 	=> '{{WRAPPER}} .gallery-item img',
							'separator'	=> '',
						]
					);

				$this->end_controls_tab();

				$this->start_controls_tab( 'image_style_hover', [ 'label' 	=> __( 'Hover', 'elementor-extras' ), ] );

					$this->add_control(
						'image_background_color_hover',
						[
							'label' 	=> __( 'Background Color', 'elementor-extras' ),
							'type' 		=> Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .gallery-item:hover .gallery-icon' => 'background-color: {{VALUE}};',
							],
						]
					);

					$this->add_responsive_control(
						'image_opacity_hover',
						[
							'label' 	=> __( 'Opacity (%)', 'elementor-extras' ),
							'type' 		=> Controls_Manager::SLIDER,
							'default' 	=> [
								'size' 	=> 1,
							],
							'range' 	=> [
								'px' 	=> [
									'max' 	=> 1,
									'min' 	=> 0,
									'step' 	=> 0.01,
								],
							],
							'selectors' => [
								'{{WRAPPER}} .gallery-item:hover img' => 'opacity: {{SIZE}}',
							],
						]
					);

					$this->add_group_control(
						Group_Control_Box_Shadow::get_type(),
						[
							'name' 		=> 'image_box_shadow_hover',
							'selector' 	=> '{{WRAPPER}} .gallery-item:hover img',
							'separator'	=> '',
						]
					);

				$this->end_controls_tab();

				$this->start_controls_tab( 'image_style_active', [ 'label' 	=> __( 'Active', 'elementor-extras' ), ] );

					$this->add_control(
						'image_background_color_active',
						[
							'label' 	=> __( 'Background Color', 'elementor-extras' ),
							'type' 		=> Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .gallery-item.is--active .gallery-icon' => 'background-color: {{VALUE}};',
							],
						]
					);

					$this->add_responsive_control(
						'image_opacity_active',
						[
							'label' 	=> __( 'Opacity (%)', 'elementor-extras' ),
							'type' 		=> Controls_Manager::SLIDER,
							'default' 	=> [
								'size' 	=> 1,
							],
							'range' 	=> [
								'px' 	=> [
									'max' 	=> 1,
									'min' 	=> 0,
									'step' 	=> 0.01,
								],
							],
							'selectors' => [
								'{{WRAPPER}} .gallery-item.is--active img' => 'opacity: {{SIZE}}',
							],
						]
					);

					$this->add_group_control(
						Group_Control_Box_Shadow::get_type(),
						[
							'name' 		=> 'image_box_shadow_active',
							'selector' 	=> '{{WRAPPER}} .gallery-item.is--active img',
							'separator'	=> '',
						]
					);

				$this->end_controls_tab();

			$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_overlay',
			[
				'label' 	=> __( 'Overlay', 'elementor-extras' ),
				'tab' 		=> Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'overlay_style' );

			$this->start_controls_tab( 'overlay_style_default', [ 'label' => __( 'Default', 'elementor-extras' ) ] );

					$this->add_control(
						'overlay_background_color',
						[
							'label' 	=> __( 'Background Color', 'elementor-extras' ),
							'type' 		=> Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .gallery-icon:after' => 'background-color: {{VALUE}};',
							],
						]
					);

					$this->add_responsive_control(
						'overlay_opacity',
						[
							'label' 	=> __( 'Opacity (%)', 'elementor-extras' ),
							'type' 		=> Controls_Manager::SLIDER,
							'default' 	=> [
								'size' 	=> 0,
							],
							'range' 	=> [
								'px' 	=> [
									'max' 	=> 1,
									'min' 	=> 0,
									'step' 	=> 0.01,
								],
							],
							'selectors' => [
								'{{WRAPPER}} .gallery-icon:after' => 'opacity: {{SIZE}}',
							],
						]
					);

					$this->add_group_control(
						Group_Control_Border::get_type(),
						[
							'name' 		=> 'overlay_border',
							'label' 	=> __( 'Border', 'elementor-extras' ),
							'selector' 	=> '{{WRAPPER}} .gallery-icon > a:after',
						]
					);

				$this->end_controls_tab();

				$this->start_controls_tab( 'overlay_style_hover', [ 'label' => __( 'Hover', 'elementor-extras' ) ] );

					$this->add_control(
						'overlay_background_color_hover',
						[
							'label' 	=> __( 'Background Color', 'elementor-extras' ),
							'type' 		=> Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .gallery-icon:hover:after' => 'background-color: {{VALUE}};',
							],
						]
					);

					$this->add_responsive_control(
						'overlay_opacity_hover',
						[
							'label' 	=> __( 'Opacity (%)', 'elementor-extras' ),
							'type' 		=> Controls_Manager::SLIDER,
							'default' 	=> [
								'size' 	=> 0,
							],
							'range' 	=> [
								'px' 	=> [
									'max' 	=> 1,
									'min' 	=> 0,
									'step' 	=> 0.01,
								],
							],
							'selectors' => [
								'{{WRAPPER}} .gallery-icon:hover:after' => 'opacity: {{SIZE}}',
							],
						]
					);

					$this->add_group_control(
						Group_Control_Border::get_type(),
						[
							'name' 		=> 'overlay_border_hover',
							'label' 	=> __( 'Border', 'elementor-extras' ),
							'selector' 	=> '{{WRAPPER}} .gallery-icon:hover:after',
						]
					);

				$this->end_controls_tab();

				$this->start_controls_tab( 'overlay_style_active', [ 'label' => __( 'Active', 'elementor-extras' ) ] );

					$this->add_control(
						'overlay_background_color_active',
						[
							'label' 	=> __( 'Background Color', 'elementor-extras' ),
							'type' 		=> Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .gallery-item.is--active .gallery-icon:after' => 'background-color: {{VALUE}};',
							],
						]
					);

					$this->add_responsive_control(
						'overlay_opacity_active',
						[
							'label' 	=> __( 'Opacity (%)', 'elementor-extras' ),
							'type' 		=> Controls_Manager::SLIDER,
							'default' 	=> [
								'size' 	=> 0,
							],
							'range' 	=> [
								'px' 	=> [
									'max' 	=> 1,
									'min' 	=> 0,
									'step' 	=> 0.01,
								],
							],
							'selectors' => [
								'{{WRAPPER}} .gallery-item.is--active .gallery-icon:after' => 'opacity: {{SIZE}}',
							],
						]
					);

					$this->add_group_control(
						Group_Control_Border::get_type(),
						[
							'name' 		=> 'overlay_border_active',
							'label' 	=> __( 'Border', 'elementor-extras' ),
							'selector' 	=> '{{WRAPPER}} .gallery-item.is--active .gallery-icon:after',
						]
					);

				$this->end_controls_tab();

			$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_caption',
			[
				'label' 	=> __( 'Caption', 'elementor-extras' ),
				'tab' 		=> Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_control(
				'gallery_display_caption',
				[
					'label' 	=> __( 'Display', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SELECT,
					'default' 	=> '',
					'options' 	=> [
						'' 		=> __( 'Show', 'elementor-extras' ),
						'none' 	=> __( 'Hide', 'elementor-extras' ),
					],
					'selectors' => [
						'{{WRAPPER}} .gallery-item .gallery-caption' => 'display: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				'align',
				[
					'label' 	=> __( 'Alignment', 'elementor-extras' ),
					'type' 		=> Controls_Manager::CHOOSE,
					'options' 	=> [
						'left' 	=> [
							'title' 	=> __( 'Left', 'elementor-extras' ),
							'icon' 		=> 'fa fa-align-left',
						],
						'center' 	=> [
							'title' 	=> __( 'Center', 'elementor-extras' ),
							'icon' 		=> 'fa fa-align-center',
						],
						'right' 	=> [
							'title' 	=> __( 'Right', 'elementor-extras' ),
							'icon' 		=> 'fa fa-align-right',
						],
						'justify' 	=> [
							'title' 	=> __( 'Justified', 'elementor-extras' ),
							'icon' 		=> 'fa fa-align-justify',
						],
					],
					'default' 	=> 'center',
					'selectors' => [
						'{{WRAPPER}} .gallery-item .gallery-caption' => 'text-align: {{VALUE}};',
					],
					'condition' => [
						'gallery_display_caption' => '',
					],
				]
			);

			$this->add_control(
				'text_color',
				[
					'label' 	=> __( 'Text Color', 'elementor-extras' ),
					'type' 		=> Controls_Manager::COLOR,
					'default' 	=> '',
					'selectors' => [
						'{{WRAPPER}} .gallery-item .gallery-caption' => 'color: {{VALUE}};',
					],
					'condition' => [
						'gallery_display_caption' => '',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' 		=> 'typography',
					'label' 	=> __( 'Typography', 'elementor-extras' ),
					'scheme' 	=> Scheme_Typography::TYPOGRAPHY_4,
					'selector' 	=> '{{WRAPPER}} .gallery-item .gallery-caption',
					'condition' => [
						'gallery_display_caption' => '',
					],
				]
			);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings();

		if ( ! $settings['wp_gallery'] ) {
			return;
		}

		$ids = wp_list_pluck( $settings['wp_gallery'], 'id' );

		$this->add_render_attribute( 'wrapper', 'class', 'elementor-gallery-slider' );
		$this->add_render_attribute( 'wrapper', 'class', 'elementor-gallery-extra' );
		$this->add_render_attribute( 'preview', 'class', 'elementor-gallery-slider__preview' );
		$this->add_render_attribute( 'preview', 'class', 'elementor-slick-slider' );
		$this->add_render_attribute( 'gallery', 'class', 'elementor-gallery-slider__gallery' );
		$this->add_render_attribute( 'gallery', 'class', 'elementor-gallery-extra__gallery' );

		$this->add_render_attribute( 'shortcode', 'ids', implode( ',', $ids ) );
		$this->add_render_attribute( 'shortcode', 'link', 'none' );
		$this->add_render_attribute( 'shortcode', 'size', $settings['thumbnail_size'] );

		$this->add_render_attribute( 'carousel', 'class', 'elementor-image-carousel' );

		if ( $settings['columns'] ) {
			$this->add_render_attribute( 'shortcode', 'columns', $settings['columns'] );
		}

		if ( ! empty( $settings['gallery_rand'] ) ) {
			$this->add_render_attribute( 'shortcode', 'orderby', $settings['gallery_rand'] );
		}

		$slides = [];

		foreach ( $settings['wp_gallery'] as $index => $attachment ) {
			$image_url = Group_Control_Image_Size::get_attachment_image_src( $attachment['id'], 'preview', $settings );

			$image_html = '<img class="slick-slide-image" src="' . esc_attr( $image_url ) . '" alt="' . esc_attr( Control_Media::get_image_alt( $attachment ) ) . '" />';

			$link = $this->get_link_url( $attachment, $settings );

			if ( $link ) {
				$link_key = 'link_' . $index;

				$this->add_render_attribute( $link_key, [
					'href' => $link['url'],
					'class' => 'elementor-clickable',
					'data-elementor-open-lightbox' => $settings['open_lightbox'],
					'data-elementor-lightbox-slideshow' => $this->get_id(),
					'data-elementor-lightbox-index' => $index,
				] );

				if ( ! empty( $link['is_external'] ) ) {
					$this->add_render_attribute( $link_key, 'target', '_blank' );
				}

				if ( ! empty( $link['nofollow'] ) ) {
					$this->add_render_attribute( $link_key, 'rel', 'nofollow' );
				}

				$image_html = '<a ' . $this->get_render_attribute_string( $link_key ) . '>' . $image_html . '</a>';
			}

			$image_caption = $this->get_image_caption( $attachment );

			$slide_html = '<div class="slick-slide"><figure class="slick-slide-inner">' . $image_html;

			if ( ! empty( $image_caption ) ) {
				$slide_html .= '<figcaption class="elementor-image-carousel-caption">' . $image_caption . '</figcaption>';
			}

			$slide_html .= '</figure></div>';

			$slides[] = $slide_html;

		}

		if ( empty( $slides ) ) {
			return;
		}

		if ( 'yes' === $settings['preview_stretch'] ) {
			$this->add_render_attribute( 'carousel', 'class', 'slick-image-stretch' );
		}

		?>
		<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
			<div <?php echo $this->get_render_attribute_string( 'preview' ); ?> dir="<?php echo $settings['direction']; ?>">
				<div <?php echo $this->get_render_attribute_string( 'carousel' ); ?>>
					<?php echo implode( '', $slides ); ?>
				</div>
			</div>
			<div <?php echo $this->get_render_attribute_string( 'gallery' ); ?>>
				<?php echo do_shortcode( '[gallery ' . $this->get_render_attribute_string( 'shortcode' ) . ']' ); ?>
			</div>
		</div>
		<?php
	}

	protected function _content_template() {}

	private function get_link_url( $attachment, $instance ) {
		if ( 'none' === $instance['link_to'] ) {
			return false;
		}

		if ( 'custom' === $instance['link_to'] ) {
			if ( empty( $instance['link']['url'] ) ) {
				return false;
			}
			return $instance['link'];
		}

		return [
			'url' => wp_get_attachment_url( $attachment['id'] ),
		];
	}

	private function get_image_caption( $attachment ) {
		$caption_type = $this->get_settings( 'caption_type' );

		if ( empty( $caption_type ) ) {
			return '';
		}

		$attachment_post = get_post( $attachment['id'] );

		if ( 'caption' === $caption_type ) {
			return $attachment_post->post_excerpt;
		}

		if ( 'title' === $caption_type ) {
			return $attachment_post->post_title;
		}

		return $attachment_post->post_content;
	}
}
