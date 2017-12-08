<?php
namespace ElementorExtras\Modules\Gallery\Widgets;

use ElementorExtras\Base\Extras_Widget;

// Elementor Classes
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Scheme_Typography;
use Elementor\Scheme_Color;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Elementor Gallery
 *
 * @since 0.1.0
 */
class Gallery extends Extras_Widget {

	public function get_name() {
		return 'gallery-extra';
	}

	public function get_title() {
		return __( 'Gallery Extra', 'elementor-extras' );
	}

	public function get_icon() {
		return 'nicon nicon-image-gallery';
	}

	public function get_categories() {
		return [ 'elementor-extras' ];
	}

	public function get_script_depends() {
		return [ 'parallax-gallery' ];
	}

	/**
	 * Add lightbox data to image link.
	 *
	 * Used to add lightbox data attributes to image link HTML.
	 *
	 * @access public
	 *
	 * @param string $link_html Image link HTML.
	 *
	 * @return string Image link HTML with lightbox data attributes.
	 */
	public function add_lightbox_data_to_image_link( $link_html ) {
		return preg_replace( '/^<a/', '<a ' . $this->get_render_attribute_string( 'link' ), $link_html );
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
			'section_gallery_settings',
			[
				'label' => __( 'Settings', 'elementor-extras' ),
			]
		);

			$this->add_group_control(
				Group_Control_Image_Size::get_type(),
				[
					'name' 		=> 'thumbnail',
					'exclude' 	=> [ 'custom' ],
				]
			);

			$this->add_responsive_control(
				'columns',
				[
					'label' 	=> __( 'Columns', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SELECT,
					'default' 	=> '3',
					'tablet_default' 	=> '2',
					'mobile_default' 	=> '1',
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
				'gallery_link',
				[
					'label' 	=> __( 'Link to', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SELECT,
					'default' 	=> 'file',
					'options' 	=> [
						'file' 			=> __( 'Media File', 'elementor-extras' ),
						'attachment' 	=> __( 'Attachment Page', 'elementor-extras' ),
						'none' 			=> __( 'None', 'elementor-extras' ),
					],
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
						'gallery_link' => 'file',
					],
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

			$this->add_control(
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

			$this->add_control(
				'parallax_disable_on',
				[
					'label' 	=> __( 'Disable for', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SELECT,
					'default' 	=> 'mobile',
					'options' 			=> [
						'none' 		=> __( 'None', 'elementor-extras' ),
						'tablet' 	=> __( 'Mobile and tablet', 'elementor-extras' ),
						'mobile' 	=> __( 'Mobile only', 'elementor-extras' ),
					],
					'condition' => [
						'parallax_enable' => 'yes',
					],
					'frontend_available' => true,
				]
			);

			$this->add_responsive_control(
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

			$this->add_responsive_control(
				'image_distance',
				[
					'label' 	=> __( 'Parallax Distance (%)', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SLIDER,
					'range' 	=> [
						'px' 	=> [
							'min' => 0,
							'max' => 100,
						],
					],
					'default' 	=> [
						'size' 	=> '10',
					],
					'selectors' => [
						'{{WRAPPER}} .gallery-item.is--3d .gallery-icon' => 'padding-left: calc({{SIZE}}%/2); padding-right: calc({{SIZE}}%/2);',
					],
					'condition' => [
						'parallax_enable' 		=> 'yes',
						'image_vertical_align!' => 'stretch',
					],
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_gallery_images',
			[
				'label' 	=> __( 'Images', 'elementor-extras' ),
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
							'title' 	=> __( 'Equalize', 'elementor-extras' ),
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

			$columns_horizontal_margin = is_rtl() ? 'margin-left' : 'margin-right';
			$columns_horizontal_padding = is_rtl() ? 'padding-left' : 'padding-right';
			$captions_horizontal_position = is_rtl() ? 'left' : 'right';

			$this->add_control(
				'image_horizontal_space',
				[
					'label' 	=> __( 'Horizontal Spacing', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SELECT,
					'default' 	=> 'custom',
					'options' 	=> [
						'none' 		=> __( 'None', 'elementor-extras' ),
						'custom' 	=> __( 'Custom', 'elementor-extras' ),
						'overlap' 	=> __( 'Overlap', 'elementor-extras' ),
					],
				]
			);

			$this->add_responsive_control(
				'image_horizontal_spacing',
				[
					'label' 	=> __( 'Horizontal Spacing', 'elementor-extras' ),
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
						'{{WRAPPER}} .gallery' 						=> $columns_horizontal_margin . ': -{{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .gallery-item' 				=> $columns_horizontal_padding . ': {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .gallery-caption' 				=> $captions_horizontal_position . ': {{SIZE}}{{UNIT}};',

						'(desktop){{WRAPPER}} .gallery-item' 		=> 'max-width: calc( 100% / {{columns.SIZE}} );',
						'(tablet){{WRAPPER}} .gallery-item' 		=> 'max-width: calc( 100% / {{columns_tablet.SIZE}} );',
						'(mobile){{WRAPPER}} .gallery-item' 		=> 'max-width: calc( 100% / {{columns_mobile.SIZE}} );',
					],
					'condition'	=> [
						'image_horizontal_space' => 'custom'
					]
				]
			);

			$this->add_responsive_control(
				'image_overlap',
				[
					'label' 	=> __( 'Horizontal Overlap', 'elementor-extras' ),
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
						'{{WRAPPER}} .gallery' 						=> 'margin-left: {{SIZE}}{{UNIT}}; margin-right: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .gallery-icon' 				=> 'margin-left: -{{SIZE}}{{UNIT}}; margin-right: -{{SIZE}}{{UNIT}};',

						'(desktop){{WRAPPER}} .gallery-item' 		=> 'max-width: calc( 100% / {{columns.SIZE}} );',
						'(tablet){{WRAPPER}} .gallery-item' 		=> 'max-width: calc( 100% / {{columns_tablet.SIZE}} );',
						'(mobile){{WRAPPER}} .gallery-item' 		=> 'max-width: calc( 100% / {{columns_mobile.SIZE}} );',
					],
					'condition'	=> [
						'image_horizontal_space' => 'overlap'
					]
				]
			);

			$this->add_responsive_control(
				'image_vertical_spacing',
				[
					'label' 	=> __( 'Vertical Spacing', 'elementor-extras' ),
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
						'{{WRAPPER}} .gallery' 			=> 'margin-top: -{{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .gallery-item' 	=> 'padding-top: {{SIZE}}{{UNIT}};',
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
						'{{WRAPPER}} .gallery-item a' 		=> 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->start_controls_tabs( 'image_style' );

				$this->start_controls_tab(
					'image_style_default',
					[
						'label' => __( 'Default', 'elementor-extras' ),
					]
				);

					$this->add_control(
						'image_background_color',
						[
							'label' 	=> __( 'Background Color', 'elementor-extras' ),
							'type' 		=> Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .gallery-icon a' => 'background-color: {{VALUE}};',
							],
							'condition'	=> [
								'gallery_link!' => 'none'
							]
						]
					);

					$this->add_responsive_control(
						'image_opacity',
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
								'{{WRAPPER}} .gallery-item img' => 'opacity: {{SIZE}}',
							],
						]
					);

					$this->add_group_control(
						Group_Control_Box_Shadow::get_type(),
						[
							'name' 		=> 'image_box_shadow',
							'selector' 	=> '{{WRAPPER}} .gallery-item a',
							'separator'	=> '',
						]
					);

				$this->end_controls_tab();

				$this->start_controls_tab(
					'image_style_hover',
					[
						'label' 	=> __( 'Hover', 'elementor-extras' ),
						'condition'	=> [
							'gallery_link!' => 'none'
						]
					]
				);

					$this->add_control(
						'image_background_color_hover',
						[
							'label' 	=> __( 'Background Color', 'elementor-extras' ),
							'type' 		=> Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .gallery-icon a:hover' => 'background-color: {{VALUE}};',
							],
							'condition'	=> [
								'gallery_link!' => 'none'
							]
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
								'{{WRAPPER}} .gallery-item a:hover img' => 'opacity: {{SIZE}}',
							],
							'condition'	=> [
								'gallery_link!' => 'none'
							]
						]
					);

					$this->add_group_control(
						Group_Control_Box_Shadow::get_type(),
						[
							'name' 		=> 'image_box_shadow_hover',
							'selector' 	=> '{{WRAPPER}} .gallery-item a:hover',
							'separator'	=> '',
							'condition'	=> [
								'gallery_link!' => 'none'
							]
						]
					);

				$this->end_controls_tab();

			$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_overlay',
			[
				'label' 	=> __( 'Overlay', 'elementor-extras' ),
				'tab' 		=> Controls_Manager::TAB_STYLE,
				'condition'	=> [
					'gallery_link!' => 'none'
				]
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
								'{{WRAPPER}} .gallery-icon > a:after' => 'background-color: {{VALUE}};',
							],
							'condition'	=> [
								'gallery_link!' => 'none'
							]
						]
					);

					$this->add_responsive_control(
						'overlay_opacity',
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
								'{{WRAPPER}} .gallery-icon > a:after' => 'opacity: {{SIZE}}',
							],
							'condition'	=> [
								'gallery_link!' => 'none'
							]
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
								'{{WRAPPER}} .gallery-icon > a:hover:after' => 'background-color: {{VALUE}};',
							],
							'condition'	=> [
								'gallery_link!' => 'none'
							]
						]
					);

					$this->add_responsive_control(
						'overlay_opacity_hover',
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
								'{{WRAPPER}} .gallery-icon > a:hover:after' => 'opacity: {{SIZE}}',
							],
						]
					);

					$this->add_group_control(
						Group_Control_Border::get_type(),
						[
							'name' 		=> 'overlay_border_hover',
							'label' 	=> __( 'Border', 'elementor-extras' ),
							'selector' 	=> '{{WRAPPER}} .gallery-icon > a:hover:after',
						]
					);

				$this->end_controls_tab();

			$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_caption',
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

			$this->add_control(
				'text_background_color',
				[
					'label' 	=> __( 'Background Color', 'elementor-extras' ),
					'type' 		=> Controls_Manager::COLOR,
					'default' 	=> '',
					'selectors' => [
						'{{WRAPPER}} .gallery-item .gallery-caption' => 'background-color: {{VALUE}};',
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

			$this->add_control(
				'text_padding',
				[
					'label' 		=> __( 'Padding', 'elementor-extras' ),
					'type' 			=> Controls_Manager::DIMENSIONS,
					'size_units' 	=> [ 'px', '%' ],
					'selectors' 	=> [
						'{{WRAPPER}} .gallery-item .gallery-caption' 	=> 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
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

		$this->add_render_attribute( 'wrapper', 'class', 'elementor-gallery-extra elementor-image-gallery' );
		$this->add_render_attribute( 'gallery', 'class', 'elementor-gallery-extra__gallery' );

		$this->add_render_attribute( 'shortcode', 'ids', implode( ',', $ids ) );
		$this->add_render_attribute( 'shortcode', 'size', $settings['thumbnail_size'] );

		if ( $settings['columns'] ) {
			$this->add_render_attribute( 'shortcode', 'columns', $settings['columns'] );
		}

		if ( $settings['gallery_link'] ) {
			$this->add_render_attribute( 'shortcode', 'link', $settings['gallery_link'] );
		}

		if ( ! empty( $settings['gallery_rand'] ) ) {
			$this->add_render_attribute( 'shortcode', 'orderby', $settings['gallery_rand'] );
		}
		?>
		<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
			<div <?php echo $this->get_render_attribute_string( 'gallery' ); ?>>
				<?php

				$this->add_render_attribute( 'link', [
					'class' 							=> 'elementor-clickable',
					'data-elementor-open-lightbox' 		=> $settings['open_lightbox'],
					'data-elementor-lightbox-slideshow' => $this->get_id(),
				] );

				add_filter( 'wp_get_attachment_link', [ $this, 'add_lightbox_data_to_image_link' ] );

				echo do_shortcode( '[gallery ' . $this->get_render_attribute_string( 'shortcode' ) . ']' );

				remove_filter( 'wp_get_attachment_link', [ $this, 'add_lightbox_data_to_image_link' ] );

				?>
			</div>
		</div>
		<?php
	}

	protected function _content_template() {}
}
