<?php
namespace ElementorExtras\Modules\Posts\Widgets;

use ElementorExtras\Base\Extras_Widget;

// Elementor Classes
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Repeater;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;

// Elementor Pro Classes
use ElementorPro\Modules\QueryControl\Controls\Group_Control_Posts;
use ElementorPro\Modules\QueryControl\Module;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Elementor Timeline
 *
 * @since 0.1.0
 */
class Timeline extends Extras_Widget {

	/**
	 * @var \WP_Query
	 */
	private $_query = null;

	public function get_name() {
		return 'timeline';
	}

	public function get_title() {
		return __( 'Timeline', 'elementor-extras' );
	}

	public function get_icon() {
		return 'nicon nicon-timeline';
	}

	public function get_categories() {
		return [ 'elementor-extras' ];
	}

	public function get_script_depends() {
		return [
			'timeline',
		];
	}

	public function get_query() {
		return $this->_query;
	}

	protected function _register_controls() {

		$posts_control_settings = [
			'label' 		=> __( 'Source', 'elementor-extras' ),
			'type' 			=> Controls_Manager::SELECT,
			'default' 		=> 'custom',
			'options' 		=> [
				'custom' 		=> __( 'Custom', 'elementor-extras' ),
			],
		];

		if ( ! class_exists( 'ElementorPro\Plugin' ) ) {
			$posts_control_settings[ 'options' ][ 'posts_pro' ] = __( 'Posts', 'elementor-extras' );
		} else {
			$posts_control_settings[ 'options' ][ 'posts' ] = __( 'Posts', 'elementor-extras' );
		}

		$this->start_controls_section(
			'section_settings',
			[
				'label' => __( 'Settings', 'elementor-extras' ),
			]
		);

			// $this->add_control(
			// 	'Layout',
			// 	[
			// 		'label' 		=> __( 'Layout', 'elementor-extras' ),
			// 		'type' 			=> Controls_Manager::SELECT,
			// 		'default' 		=> 'vertical',
			// 		'options' 		=> [
			// 			'vertical' 		=> __( 'Vertical', 'elementor-extras' ),
			// 			'horizontal' 	=> __( 'Horizontal', 'elementor-extras' ),
			// 		],
			// 		'prefix_class'		=> 'ee-timeline--'
			// 	]
			// );

			$this->add_control( 'source', $posts_control_settings );

			if ( ! is_elementor_pro_active() ) {

				$this->add_control(
					'posts_go_pro',
					[
						'type' 	=> Controls_Manager::RAW_HTML,
						'raw' 	=> '<div class="elementor-panel-nerd-box">
										<i class="elementor-panel-nerd-box-icon eicon-hypster"></i>
										<div class="elementor-panel-nerd-box-title">' .
											__( 'Oups, hang on!', 'elementor-extras' ) .
										'</div>
										<div class="elementor-panel-nerd-box-message">' .
											__( 'This feature is only available if you have Elementor Pro.', 'elementor-extras' ) .
										'</div>
										<a class="elementor-panel-nerd-box-link elementor-button elementor-button-default elementor-go-pro" href="https://elementor.com/pro/" target="_blank">' .
										__( 'Go Pro', 'elementor-extras' ) .
										'</a>
									</div>',
						'condition'	=> [
							'source'	=> 'posts_pro'
						]
					]
	        	);
			}

			if ( is_elementor_pro_active() ) {

				$this->add_control(
					'posts_per_page',
					[
						'label' 	=> __( 'Posts Per Page', 'elementor-extras' ),
						'type' 		=> Controls_Manager::NUMBER,
						'default' 	=> 6,
						'condition'	=> [
							'source'	=> 'posts'
						]
					]
				);
			}

			$this->add_control(
				'card_links',
				[
					'label' 		=> __( 'Enable Links', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SWITCHER,
					'label_on' 		=> __( 'Yes', 'elementor-extras' ),
					'label_off' 	=> __( 'No', 'elementor-extras' ),
					'return_value' 	=> 'yes',
					'description'   => __( 'Enable links at card level.', 'elementor-extras' ),
					'condition'	=> [
						'source'	=> 'posts'
					]
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_timeline',
			[
				'label' 	=> __( 'Timeline', 'elementor-extras' ),
				'condition'	=> [
					'source!'	=> 'posts',
				]
			]
		);

			$repeater = new Repeater();

			$repeater->start_controls_tabs( 'items_repeater' );

			$repeater->start_controls_tab( 'tab_content', [ 'label' => __( 'Content', 'elementor-extras' ) ] );

				$repeater->add_control(
					'date',
					[
						'label' 		=> __( 'Date', 'elementor-extras' ),
						'type' 			=> Controls_Manager::TEXT,
						'placeholder' 	=> __( '19 January 2000', 'elementor-extras' ),
					]
				);

				$repeater->add_control(
					'link',
					[
						'label' 		=> __( 'Link', 'elementor-extras' ),
						'type' 			=> Controls_Manager::URL,
						'placeholder' 	=> esc_url( home_url( '/' ) ),
						'default' 		=> [
							'url' 		=> '',
						],
					]
				);

				$default_title = '<h2>' . _x( 'The birth of mankind', 'Default title for the content of a hotspot.', 'elementor-extras' ) . '</h2>';
				$default_paragraph = '<p>' . _x( 'Something really big happened around this period of time. It affected all of humanity. That explains everything.', 'Default title for the content of a hotspot.', 'elementor-extras' ) . '</p>';

				$repeater->add_control(
					'content',
					[
						'label' 		=> '',
						'type' 			=> Controls_Manager::WYSIWYG,
						'default' 		=> $default_title . $default_paragraph,
					]
				);

			$repeater->end_controls_tab();

			$repeater->start_controls_tab( 'tab_media', [ 'label' => __( 'Media', 'elementor-extras' ) ] );

				$repeater->add_control(
					'image',
					[
						'label' 	=> __( 'Choose Image', 'elementor-extras' ),
						'type' 		=> Controls_Manager::MEDIA,
					]
				);

				$repeater->add_group_control(
					Group_Control_Image_Size::get_type(),
					[
						'name' 		=> 'image', // Actually its `image_size`
						'label' 	=> __( 'Image Size', 'elementor-extras' ),
						'default' 	=> 'large',
					]
				);

			$repeater->end_controls_tab();

			$repeater->start_controls_tab( 'tab_style', [ 'label' => __( 'Style', 'elementor-extras' ) ] );

				$repeater->add_control(
					'custom_style',
					[
						'label' 		=> __( 'Custom', 'elementor-extras' ),
						'type' 			=> Controls_Manager::SWITCHER,
						'label_on' 		=> __( 'Yes', 'elementor-extras' ),
						'label_off' 	=> __( 'No', 'elementor-extras' ),
						'return_value' 	=> 'yes',
						'description'   => __( 'Set custom styles that will only affect this specific item.', 'elementor-extras' ),
					]
				);

				$repeater->add_control(
					'icon',
					[
						'label' 		=> __( 'Icon', 'elementor-extras' ),
						'type' 			=> Controls_Manager::ICON,
						'default' 		=> 'fa fa-calendar',
						'conditions' => [
							'terms' => [
								[
									'name' => 'custom_style',
									'operator' => '==',
									'value' => 'yes',
								],
							],
						],
					]
				);

				$repeater->add_control(
					'item_default',
					[
						'label' => __( 'Default', 'elementor-extras' ),
						'type' 	=> Controls_Manager::HEADING,
					]
				);

					$repeater->add_control(
						'icon_color',
						[
							'label' 	=> __( 'Icon Color', 'elementor-extras' ),
							'type' 		=> Controls_Manager::COLOR,
							'default'	=> '',
							'selectors' => [
								'{{WRAPPER}} {{CURRENT_ITEM}} .timeline-item__point .fa:before' => 'color: {{VALUE}};',
							],
							'conditions' => [
								'terms' => [
									[
										'name' => 'custom_style',
										'operator' => '==',
										'value' => 'yes',
									],
								],
							],
						]
					);

					$repeater->add_control(
						'point_background',
						[
							'label' 		=> __( 'Point Background', 'elementor-extras' ),
							'type' 			=> Controls_Manager::COLOR,
							'default'		=> '',
							'selectors' 	=> [
								'{{WRAPPER}} .ee-timeline {{CURRENT_ITEM}} .timeline-item__point' => 'background-color: {{VALUE}};',
							],
							'conditions' => [
								'terms' => [
									[
										'name' => 'custom_style',
										'operator' => '==',
										'value' => 'yes',
									],
								],
							],
						]
					);

					$repeater->add_control(
						'card_background',
						[
							'label' 		=> __( 'Card Background', 'elementor-extras' ),
							'type' 			=> Controls_Manager::COLOR,
							'default'		=> '',
							'selectors' 	=> [
								'{{WRAPPER}} {{CURRENT_ITEM}}.timeline-item .timeline-item__card' => 'background-color: {{VALUE}};',
								'{{WRAPPER}} {{CURRENT_ITEM}}.timeline-item .timeline-item__card .timeline-item__card-arrow::after' => 'border-left-color: {{VALUE}}; border-right-color: {{VALUE}};',
							],
							'conditions' => [
								'terms' => [
									[
										'name' => 'custom_style',
										'operator' => '==',
										'value' => 'yes',
									],
								],
							],
						]
					);

					$repeater->add_control(
						'date_color',
						[
							'label' 	=> __( 'Date Color', 'elementor-extras' ),
							'type' 		=> Controls_Manager::COLOR,
							'default'	=> '',
							'selectors' => [
								'{{WRAPPER}} {{CURRENT_ITEM}}.timeline-item .timeline-item__meta' => 'color: {{VALUE}};',
							],
							'conditions' => [
								'terms' => [
									[
										'name' => 'custom_style',
										'operator' => '==',
										'value' => 'yes',
									],
								],
							],
						]
					);

					$repeater->add_control(
						'point_size',
						[
							'label' 		=> __( 'Scale', 'elementor-extras' ),
							'type' 			=> Controls_Manager::SLIDER,
							'default' 		=> [
								'size' 		=> '',
							],
							'range' 		=> [
								'px' 		=> [
									'min' 	=> 0.5,
									'max' 	=> 2,
									'step'	=> 0.01
								],
							],
							'selectors' => [
								// Item
								'{{WRAPPER}} {{CURRENT_ITEM}}.timeline-item .timeline-item__point' => 'transform: scale({{SIZE}})',
							],
							'conditions' => [
								'terms' => [
									[
										'name' 		=> 'custom_style',
										'operator' 	=> '==',
										'value' 	=> 'yes',
									],
								],
							],
						]
					);

				$repeater->add_control(
					'item_hover',
					[
						'label' => __( 'Hover', 'elementor-extras' ),
						'type' 	=> Controls_Manager::HEADING,
					]
				);

					$repeater->add_control(
						'icon_color_hover',
						[
							'label' 	=> __( 'Hovered Icon Color', 'elementor-extras' ),
							'type' 		=> Controls_Manager::COLOR,
							'default'	=> '',
							'selectors' => [
								'{{WRAPPER}} {{CURRENT_ITEM}}.timeline-item:hover .timeline-item__point .fa:before,
								{{WRAPPER}} {{CURRENT_ITEM}}.timeline-item.is--focused:hover .timeline-item__point .fa:before' => 'color: {{VALUE}};',
							],
							'conditions' => [
								'terms' => [
									[
										'name' 		=> 'custom_style',
										'operator' 	=> '==',
										'value' 	=> 'yes',
									],
								],
							],
						]
					);

					$repeater->add_control(
						'point_background_hover',
						[
							'label' 		=> __( 'Hovered Point Background', 'elementor-extras' ),
							'type' 			=> Controls_Manager::COLOR,
							'default'		=> '',
							'selectors' 	=> [
								'{{WRAPPER}} .ee-timeline {{CURRENT_ITEM}}.timeline-item:hover .timeline-item__point,
								{{WRAPPER}} .ee-timeline {{CURRENT_ITEM}}.timeline-item.is--focused:hover .timeline-item__point' => 'background-color: {{VALUE}};',
							],
							'conditions' 	=> [
								'terms' 	=> [
									[
										'name' 		=> 'custom_style',
										'operator' 	=> '==',
										'value' 	=> 'yes',
									],
								],
							],
						]
					);

					$repeater->add_control(
						'card_background_hover',
						[
							'label' 		=> __( 'Hovered Card Background', 'elementor-extras' ),
							'type' 			=> Controls_Manager::COLOR,
							'default'		=> '',
							'selectors' 	=> [
								'{{WRAPPER}} {{CURRENT_ITEM}}.timeline-itemd:hover .timeline-item__card,
								{{WRAPPER}} {{CURRENT_ITEM}}.timeline-item.is--focused:hover .timeline-item__card' => 'background-color: {{VALUE}};',
								'{{WRAPPER}} {{CURRENT_ITEM}}.timeline-item:hover .timeline-item__card .timeline-item__card-arrow::after,
								{{WRAPPER}} {{CURRENT_ITEM}}.timeline-item.is--focused:hover .timeline-item__card .timeline-item__card-arrow::after' => 'border-left-color: {{VALUE}}; border-right-color: {{VALUE}};',
							],
							'conditions' => [
								'terms' => [
									[
										'name' => 'custom_style',
										'operator' => '==',
										'value' => 'yes',
									],
								],
							],
						]
					);

					$repeater->add_control(
						'date_color_hover',
						[
							'label' 	=> __( 'Hovered Date Color', 'elementor-extras' ),
							'type' 		=> Controls_Manager::COLOR,
							'default'	=> '',
							'selectors' => [
								'{{WRAPPER}} {{CURRENT_ITEM}}.timeline-item:hover .timeline-item__meta,
								{{WRAPPER}} {{CURRENT_ITEM}}.timeline-item.is--focused:hover .timeline-item__meta' => 'color: {{VALUE}};',
							],
							'conditions' => [
								'terms' => [
									[
										'name' => 'custom_style',
										'operator' => '==',
										'value' => 'yes',
									],
								],
							],
						]
					);

					$repeater->add_control(
						'point_size_hover',
						[
							'label' 		=> __( 'Hovered Point Scale', 'elementor-extras' ),
							'type' 			=> Controls_Manager::SLIDER,
							'default' 		=> [
								'size' 		=> '',
							],
							'range' 		=> [
								'px' 		=> [
									'min' 	=> 0.5,
									'max' 	=> 2,
									'step'	=> 0.01
								],
							],
							'selectors' => [
								// Item
								'{{WRAPPER}} {{CURRENT_ITEM}}.timeline-item:hover .timeline-item__point,
								{{WRAPPER}} {{CURRENT_ITEM}}.timeline-item.is--focused:hover .timeline-item__point' => 'transform: scale({{SIZE}})',
							],
							'conditions' => [
								'terms' => [
									[
										'name' 		=> 'custom_style',
										'operator' 	=> '==',
										'value' 	=> 'yes',
									],
								],
							],
						]
					);

				$repeater->add_control(
					'item_focused',
					[
						'label' => __( 'Focused', 'elementor-extras' ),
						'type' 	=> Controls_Manager::HEADING,
					]
				);

					$repeater->add_control(
						'icon_color_focused',
						[
							'label' 	=> __( 'Focused Icon Color', 'elementor-extras' ),
							'type' 		=> Controls_Manager::COLOR,
							'default'	=> '',
							'selectors' => [
								'{{WRAPPER}} {{CURRENT_ITEM}}.timeline-item.is--focused .timeline-item__point .fa:before' => 'color: {{VALUE}};',
							],
							'conditions' => [
								'terms' => [
									[
										'name' 		=> 'custom_style',
										'operator' 	=> '==',
										'value' 	=> 'yes',
									],
								],
							],
						]
					);

					$repeater->add_control(
						'point_background_focused',
						[
							'label' 		=> __( 'Focused Point Background', 'elementor-extras' ),
							'type' 			=> Controls_Manager::COLOR,
							'default'		=> '',
							'selectors' 	=> [
								'{{WRAPPER}} .ee-timeline {{CURRENT_ITEM}}.timeline-item.is--focused .timeline-item__point' => 'background-color: {{VALUE}};',
							],
							'conditions' 	=> [
								'terms' 	=> [
									[
										'name' 		=> 'custom_style',
										'operator' 	=> '==',
										'value' 	=> 'yes',
									],
								],
							],
						]
					);

					$repeater->add_control(
						'card_background_focused',
						[
							'label' 		=> __( 'Focused Card Background', 'elementor-extras' ),
							'type' 			=> Controls_Manager::COLOR,
							'default'		=> '',
							'selectors' 	=> [
								'{{WRAPPER}} {{CURRENT_ITEM}}.timeline-item.is--focused .timeline-item__card' => 'background-color: {{VALUE}};',
								'{{WRAPPER}} {{CURRENT_ITEM}}.timeline-item.is--focused .timeline-item__card .timeline-item__card-arrow::after' => 'border-left-color: {{VALUE}}; border-right-color: {{VALUE}};',
							],
							'conditions' => [
								'terms' => [
									[
										'name' => 'custom_style',
										'operator' => '==',
										'value' => 'yes',
									],
								],
							],
						]
					);

					$repeater->add_control(
						'date_color_focused',
						[
							'label' 	=> __( 'Focused Date Color', 'elementor-extras' ),
							'type' 		=> Controls_Manager::COLOR,
							'default'	=> '',
							'selectors' => [
								'{{WRAPPER}} {{CURRENT_ITEM}}.timeline-item.is--focused .timeline-item__meta' => 'color: {{VALUE}};',
							],
							'conditions' => [
								'terms' => [
									[
										'name' => 'custom_style',
										'operator' => '==',
										'value' => 'yes',
									],
								],
							],
						]
					);

					$repeater->add_control(
						'point_size_focused',
						[
							'label' 		=> __( 'Focused Point Scale', 'elementor-extras' ),
							'type' 			=> Controls_Manager::SLIDER,
							'default' 		=> [
								'size' 		=> '',
							],
							'range' 		=> [
								'px' 		=> [
									'min' 	=> 0.5,
									'max' 	=> 2,
									'step'	=> 0.01
								],
							],
							'selectors' => [
								// Item
								'{{WRAPPER}} {{CURRENT_ITEM}}.timeline-item.is--focused .timeline-item__point' => 'transform: scale({{SIZE}})',
							],
							'conditions' => [
								'terms' => [
									[
										'name' 		=> 'custom_style',
										'operator' 	=> '==',
										'value' 	=> 'yes',
									],
								],
							],
						]
					);

			$repeater->end_controls_tab();

			$repeater->end_controls_tabs();

			$this->add_control(
				'items',
				[
					'label' 	=> __( 'Items', 'elementor-extras' ),
					'type' 		=> Controls_Manager::REPEATER,
					'default' 	=> [
						[
							'date' => __( 'February 2, 2014', 'elementor-extras' )
						],
						[
							'date' => __( 'May 10, 2015', 'elementor-extras' )
						],
						[
							'date' => __( 'June 21, 2016', 'elementor-extras' )
						],
					],
					'fields' 		=> array_values( $repeater->get_controls() ),
					'title_field' 	=> '{{{ date }}}',
					'condition'		=> [
						'source'	=> 'custom'
					]
				]
			);

		$this->end_controls_section();

		if ( is_elementor_pro_active() ) {

		$this->start_controls_section(
			'section_query',
			[
				'label' 	=> __( 'Query', 'elementor-extras' ),
				'tab' 		=> Controls_Manager::TAB_CONTENT,
				'condition'	=> [
					'source'	=> 'posts'
				]
			]
		);

			$this->add_group_control(
				Group_Control_Posts::get_type(),
				[
					'name' 	=> 'posts',
					'label' => __( 'Posts', 'elementor-extras' ),
				]
			);

			$this->add_control(
				'advanced',
				[
					'label' => __( 'Advanced', 'elementor-extras' ),
					'type' 	=> Controls_Manager::HEADING,
				]
			);

			$this->add_control(
				'orderby',
				[
					'label' 	=> __( 'Order By', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SELECT,
					'default' 	=> 'post_date',
					'options' 	=> [
						'post_date' 	=> __( 'Date', 'elementor-extras' ),
						'post_title' 	=> __( 'Title', 'elementor-extras' ),
						'menu_order' 	=> __( 'Menu Order', 'elementor-extras' ),
						'rand' 			=> __( 'Random', 'elementor-extras' ),
					],
				]
			);

			$this->add_control(
				'order',
				[
					'label' 	=> __( 'Order', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SELECT,
					'default' 	=> 'desc',
					'options' 	=> [
						'asc' 	=> __( 'ASC', 'elementor-extras' ),
						'desc' 	=> __( 'DESC', 'elementor-extras' ),
					],
				]
			);

			$this->add_control(
				'offset',
				[
					'label' 		=> __( 'Offset', 'elementor-extras' ),
					'type' 			=> Controls_Manager::NUMBER,
					'default' 		=> 0,
					'condition' 	=> [
						'posts_post_type!' => 'by_id',
					],
				]
			);

			Module::add_exclude_controls( $this );

		$this->end_controls_section();

		}

		$this->start_controls_section(
			'section_posts',
			[
				'label' 	=> __( 'Posts', 'elementor-extras' ),
				'tab' 		=> Controls_Manager::TAB_CONTENT,
				'condition'	=> [
					'source'			=> 'posts',
				]
			]
		);

			$this->add_control(
				'post_thumbnail',
				[
					'label' 		=> __( 'Show Image', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SWITCHER,
					'default'		=> 'yes',
					'label_on' 		=> __( 'Yes', 'elementor-extras' ),
					'label_off' 	=> __( 'No', 'elementor-extras' ),
					'return_value' 	=> 'yes',
					'condition'		=> [
						'source'	=> 'posts',
					]
				]
			);

			$this->add_group_control(
				Group_Control_Image_Size::get_type(),
				[
					'name' 			=> 'post_thumbnail_size',
					'label' 		=> __( 'Image Size', 'elementor-extras' ),
					'exclude' 		=> [ 'custom' ],
					'default' 		=> 'medium',
					'prefix_class' 	=> 'elementor-portfolio--thumbnail-size-',
					'condition'		=> [
						'source'			=> 'posts',
						'post_thumbnail'	=> 'yes'
					]
				]
			);

			$this->add_control(
				'post_title',
				[
					'label' 		=> __( 'Show Title', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SWITCHER,
					'default'		=> 'yes',
					'label_on' 		=> __( 'Yes', 'elementor-extras' ),
					'label_off' 	=> __( 'No', 'elementor-extras' ),
					'return_value' 	=> 'yes',
					'condition'		=> [
						'source'	=> 'posts',
					]
				]
			);

			$this->add_control(
				'post_excerpt',
				[
					'label' 		=> __( 'Show Excerpt', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SWITCHER,
					'default'		=> 'yes',
					'label_on' 		=> __( 'Yes', 'elementor-extras' ),
					'label_off' 	=> __( 'No', 'elementor-extras' ),
					'return_value' 	=> 'yes',
					'condition'		=> [
						'source'	=> 'posts',
					]
				]
			);

			if ( is_woocommerce_active() ) {

				$this->add_control(
					'post_product_heading',
					[
						'label' => __( 'Products', 'elementor-extras' ),
						'type' 	=> Controls_Manager::HEADING,
						'condition'		=> [
							'source'			=> 'posts',
							'posts_post_type'	=> 'product'
						]
					]
				);

				$this->add_control(
					'post_buy',
					[
						'label' 		=> __( 'Buy Button', 'elementor-extras' ),
						'type' 			=> Controls_Manager::SWITCHER,
						'default'		=> 'yes',
						'label_on' 		=> __( 'Yes', 'elementor-extras' ),
						'label_off' 	=> __( 'No', 'elementor-extras' ),
						'return_value' 	=> 'yes',
						'condition'		=> [
							'card_links!'		=> 'yes',
							'posts_post_type'	=> 'product'
						]
					]
				);

				$this->add_control(
					'post_product_attributes',
					[
						'label' 		=> __( 'Show Attributes', 'elementor-extras' ),
						'type' 			=> Controls_Manager::SWITCHER,
						'default'		=> 'yes',
						'label_on' 		=> __( 'Yes', 'elementor-extras' ),
						'label_off' 	=> __( 'No', 'elementor-extras' ),
						'return_value' 	=> 'yes',
						'condition'		=> [
							'source'			=> 'posts',
							'posts_post_type'	=> 'product'
						]
					]
				);



				$this->add_control(
					'post_product_attributes_exclude',
					[
						'label' 		=> __( 'Exclude attributes', 'elementor-extras' ),
						'description'	=> __( 'Enter attribute slugs, names or ids, separated by commas', 'elementor-extras' ),
						'type' 			=> Controls_Manager::TEXT,
						'condition'		=> [
							'source'			=> 'posts',
							'posts_post_type'	=> 'product'
						]
					]
				);

			}

		$this->end_controls_section();

		$this->start_controls_section(
			'section_layout',
			[
				'label' => __( 'Layout', 'elementor-extras' ),
				'tab' 		=> Controls_Manager::TAB_STYLE,
			]
		);

			// TODO: Figure out if this is useful
			// $this->add_responsive_control(
			// 	'size',
			// 	[
			// 		'label' 	=> __( 'Size (%)', 'elementor-extras' ),
			// 		'type' 		=> Controls_Manager::SLIDER,
			// 		'default' 	=> [
			// 			'size' 	=> 100,
			// 		],
			// 		'range' 		=> [
			// 			'px' 		=> [
			// 				'min' 	=> 50,
			// 				'max' 	=> 100,
			// 			],
			// 		],
			// 		'selectors' => [
			// 			'{{WRAPPER}} .ee-timeline' => 'max-width: {{SIZE}}%;',
			// 		],
			// 	]
			// );

			$this->add_control(
				'align',
				[
					'label' 		=> __( 'Alignment', 'elementor-extras' ),
					'type' 			=> Controls_Manager::CHOOSE,
					'default'		=> 'center',
					'options' 		=> [
						'left' 		=> [
							'title' => __( 'Left', 'elementor-extras' ),
							'icon' 	=> 'eicon-h-align-left',
						],
						'center' 	=> [
							'title' => __( 'Center', 'elementor-extras' ),
							'icon' 	=> 'eicon-h-align-center',
						],
						'right' 	=> [
							'title' => __( 'Right', 'elementor-extras' ),
							'icon' 	=> 'eicon-h-align-right',
						],
					],
				]
			);

			$this->add_control(
				'horizontal_spacing',
				[
					'label' 	=> __( 'Horizontal Spacing', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SLIDER,
					'default' 	=> [
						'size' 	=> '',
					],
					'range' 		=> [
						'px' 		=> [
							'min' 	=> 0,
							'max' 	=> 100,
						],
					],
					'selectors' => [
						'{{WRAPPER}} .ee-timeline .timeline-item .timeline-item__point' 	=> 'margin-left: {{SIZE}}px; margin-right: {{SIZE}}px;',
					],
				]
			);

			$this->add_control(
				'vertical_spacing',
				[
					'label' 	=> __( 'Vertical Spacing', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SLIDER,
					'default' 	=> [
						'size' 	=> '',
					],
					'range' 		=> [
						'px' 		=> [
							'min' 	=> 0,
							'max' 	=> 100,
						],
					],
					'selectors' => [
						'{{WRAPPER}} .ee-timeline .ee-timeline__item' => 'margin-bottom: {{SIZE}}px;',
					],
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_images',
			[
				'label' 		=> __( 'Images', 'elementor-extras' ),
				'tab' 			=> Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_control(
				'cards_images_spacing',
				[
					'label' 	=> __( 'Spacing', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SLIDER,
					'default' 	=> [
						'size' 	=> '',
					],
					'range' 		=> [
						'px' 		=> [
							'min' 	=> 0,
							'max' 	=> 100,
						],
					],
					'selectors' => [
						'{{WRAPPER}} .ee-timeline .timeline-item .timeline-item__img' 	=> 'margin-bottom: {{SIZE}}px;',
					],
				]
			);

			$this->add_control(
				'images_border_radius',
				[
					'label' 		=> __( 'Border Radius', 'elementor-extras' ),
					'type' 			=> Controls_Manager::DIMENSIONS,
					'size_units' 	=> [ 'px', '%' ],
					'selectors' 	=> [
						'{{WRAPPER}} .ee-timeline .timeline-item__img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_cards',
			[
				'label' => __( 'Cards', 'elementor-extras' ),
				'tab' 		=> Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_responsive_control(
				'cards_padding',
				[
					'label' 		=> __( 'Card Padding', 'elementor-extras' ),
					'type' 			=> Controls_Manager::DIMENSIONS,
					'size_units' 	=> [ 'px' ],
					'selectors' 	=> [
						'{{WRAPPER}} .ee-timeline .timeline-item__card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_responsive_control(
				'cards_content_padding',
				[
					'label' 		=> __( 'Content Padding', 'elementor-extras' ),
					'type' 			=> Controls_Manager::DIMENSIONS,
					'size_units' 	=> [ 'px' ],
					'selectors' 	=> [
						'{{WRAPPER}} .ee-timeline .timeline-item__content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_responsive_control(
				'cards_align',
				[
					'label' 		=> __( 'Arrow Alignment', 'elementor-extras' ),
					'type' 			=> Controls_Manager::CHOOSE,
					'default'		=> 'top',
					'options' 		=> [
						'top' 		=> [
							'title' => __( 'Top', 'elementor-extras' ),
							'icon' 	=> 'eicon-v-align-top',
						],
						'middle' 	=> [
							'title' => __( 'Middle', 'elementor-extras' ),
							'icon' 	=> 'eicon-v-align-middle',
						],
						'bottom' 	=> [
							'title' => __( 'Bottom', 'elementor-extras' ),
							'icon' 	=> 'eicon-v-align-bottom',
						],
					],
				]
			);

			$this->add_control(
				'cards_border_radius',
				[
					'label' 		=> __( 'Border Radius', 'elementor-extras' ),
					'type' 			=> Controls_Manager::DIMENSIONS,
					'size_units' 	=> [ 'px', '%' ],
					'selectors' 	=> [
						'{{WRAPPER}} .ee-timeline .timeline-item__card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_control(
				'animate_in',
				[
					'label' 		=> __( 'Animate Cards', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SWITCHER,
					'default'		=> 'yes',
					'label_on' 		=> __( 'Yes', 'elementor-extras' ),
					'label_off' 	=> __( 'No', 'elementor-extras' ),
					'return_value' 	=> 'yes',
				]
			);

			$this->start_controls_tabs( 'tabs_cards' );

			$this->start_controls_tab( 'tab_cards_default', [ 'label' => __( 'Default', 'elementor-extras' ) ] );

				$this->add_control(
					'cards_color',
					[
						'label' 	=> __( 'Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'default'	=> '',
						'selectors' => [
							'{{WRAPPER}} .ee-timeline .timeline-item__card' 		=> 'color: {{VALUE}};',
							'{{WRAPPER}} .ee-timeline .timeline-item__card *' 	=> 'color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					'cards_background_color',
					[
						'label' 	=> __( 'Background Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'default'	=> '',
						'selectors' => [
							'{{WRAPPER}} .timeline-item .timeline-item__card' 															=> 'background-color: {{VALUE}};',
							'{{WRAPPER}} .timeline-item .timeline-item__card .timeline-item__card-arrow::after' 						=> 'border-left-color: {{VALUE}}; border-right-color: {{VALUE}};',
						],
					]
				);

				$this->add_group_control(
					Group_Control_Box_Shadow::get_type(),
					[
						'name' 		=> 'cards_box_shadow',
						'selector' 	=> '{{WRAPPER}} .timeline-item .timeline-item__card',
					]
				);

			$this->end_controls_tab();

			$this->start_controls_tab( 'tab_cards_hover', [ 'label' => __( 'Hover', 'elementor-extras' ) ] );

				$this->add_control(
					'cards_color_hover',
					[
						'label' 	=> __( 'Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'default'	=> '',
						'selectors' => [
							'{{WRAPPER}} .ee-timeline .timeline-item:hover .timeline-item__card' 		=> 'color: {{VALUE}};',
							'{{WRAPPER}} .ee-timeline .timeline-item:hover .timeline-item__card *' 		=> 'color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					'cards_background_color_hover',
					[
						'label' 	=> __( 'Background Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'default'	=> '',
						'selectors' => [
							'{{WRAPPER}} .ee-timeline .timeline-item:hover .timeline-item__card' 																	=> 'background-color: {{VALUE}};',
							'{{WRAPPER}} .ee-timeline .timeline-item:hover .timeline-item__card .timeline-item__card-arrow::after' 									=> 'border-left-color: {{VALUE}}; border-right-color: {{VALUE}};',
						],
					]
				);

				$this->add_group_control(
					Group_Control_Box_Shadow::get_type(),
					[
						'name' 		=> 'cards_box_shadow_hover',
						'selector' 	=> '{{WRAPPER}} .timeline-item:hover .timeline-item__card',
					]
				);

			$this->end_controls_tab();

			$this->start_controls_tab( 'tab_cards_focused', [ 'label' => __( 'Focused', 'elementor-extras' ) ] );

				$this->add_control(
					'cards_color_focused',
					[
						'label' 	=> __( 'Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'default'	=> '',
						'selectors' => [
							'{{WRAPPER}} .ee-timeline .timeline-item.is--focused .timeline-item__card' 		=> 'color: {{VALUE}};',
							'{{WRAPPER}} .ee-timeline .timeline-item.is--focused .timeline-item__card *' 		=> 'color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					'cards_background_color_focused',
					[
						'label' 	=> __( 'Background Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'default'	=> '',
						'selectors' => [
							'{{WRAPPER}} .timeline-item.is--focused .timeline-item__card' 																	=> 'background-color: {{VALUE}};',
							'{{WRAPPER}} .timeline-item.is--focused .timeline-item__card .timeline-item__card-arrow::after' 								=> 'border-left-color: {{VALUE}}; border-right-color: {{VALUE}};',
						],
					]
				);

				$this->add_group_control(
					Group_Control_Box_Shadow::get_type(),
					[
						'name' 		=> 'cards_box_shadow_focused',
						'selector' 	=> '{{WRAPPER}} .timeline-item.is--focused .timeline-item__card',
					]
				);

			$this->end_controls_tab();

			$this->end_controls_tabs();

			$this->add_group_control(
				Group_Control_Text_Shadow::get_type(),
				[
					'name' 		=> 'cards_text_shadow',
					'selector' 	=> '{{WRAPPER}} .ee-timeline .timeline-item__card',
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' 		=> 'cards_typography',
					'selector' 	=> '{{WRAPPER}} .ee-timeline .timeline-item__card',
					'scheme' 	=> Scheme_Typography::TYPOGRAPHY_3,
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_dates',
			[
				'label' 	=> __( 'Dates', 'elementor-extras' ),
				'tab' 		=> Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_control(
				'dates_padding',
				[
					'label' 		=> __( 'Padding', 'elementor-extras' ),
					'type' 			=> Controls_Manager::DIMENSIONS,
					'size_units' 	=> [ 'px' ],
					'selectors' 	=> [
						'{{WRAPPER}} .ee-timeline .timeline-item__meta' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_control(
				'dates_margin',
				[
					'label' 		=> __( 'Margin', 'elementor-extras' ),
					'type' 			=> Controls_Manager::DIMENSIONS,
					'size_units' 	=> [ 'px' ],
					'selectors' 	=> [
						'{{WRAPPER}} .ee-timeline .timeline-item__meta' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_control(
				'dates_color',
				[
					'label' 	=> __( 'Color', 'elementor-extras' ),
					'type' 		=> Controls_Manager::COLOR,
					'default'	=> '',
					'selectors' => [
						'{{WRAPPER}} .ee-timeline .timeline-item__meta' => 'color: {{VALUE}};',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Text_Shadow::get_type(),
				[
					'name' 		=> 'dates_text_shadow',
					'selector' 	=> '{{WRAPPER}} .ee-timeline .timeline-item__meta',
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' 		=> 'dates_typography',
					'selector' 	=> '{{WRAPPER}} .ee-timeline .timeline-item__meta',
					'scheme' 	=> Scheme_Typography::TYPOGRAPHY_3,
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_points',
			[
				'label' 	=> __( 'Points', 'elementor-extras' ),
				'tab' 		=> Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_control(
				'global_icon',
				[
					'label' 		=> __( 'Choose Icon', 'elementor-extras' ),
					'type' 			=> Controls_Manager::ICON,
					'default' 		=> 'fa fa-calendar',
				]
			);

			$this->start_controls_tabs( 'tabs_points' );

			$this->start_controls_tab( 'points_default', [ 'label' => __( 'Default', 'elementor-extras' ) ] );

				$this->add_responsive_control(
					'points_size',
					[
						'label' 	=> __( 'Size', 'elementor-extras' ),
						'type' 		=> Controls_Manager::SLIDER,
						'default' 	=> [
							'size' 	=> 40,
						],
						'range' 		=> [
							'px' 		=> [
								'min' 	=> 10,
								'max' 	=> 80,
							],
						],
						'selectors' => [
							// General
							'{{WRAPPER}} .timeline-item__point' 		=> 'width: {{SIZE}}px; height: {{SIZE}}px',
							'{{WRAPPER}} .timeline-item__card-arrow' 	=> 'height: {{SIZE}}px;',

							// Left alignment
							'{{WRAPPER}} .ee-timeline-align--left .ee-timeline__line' 				=> 'margin-left: calc( {{SIZE}}px / 2 );',
							'(tablet){{WRAPPER}} .ee-timeline-align--center .ee-timeline__line' 		=> 'margin-left: calc( {{points_size_tablet.SIZE}}px / 2 );',
							'(mobile){{WRAPPER}} .ee-timeline-align--center .ee-timeline__line' 		=> 'margin-left: calc( {{points_size_mobile.SIZE}}px / 2 );',
							'{{WRAPPER}} .ee-timeline-align--right .ee-timeline__line' 				=> 'margin-right: calc( {{SIZE}}px / 2 );',
						],
					]
				);

				$this->add_responsive_control(
					'icons_size',
					[
						'label' 	=> __( 'Icon Size', 'elementor-extras' ),
						'type' 		=> Controls_Manager::SLIDER,
						'default' 	=> [
							'size' 	=> 1,
						],
						'range' 		=> [
							'px' 		=> [
								'min' 	=> 1,
								'max' 	=> 4,
							],
						],
						'selectors' => [
							'{{WRAPPER}} .timeline-item__point .fa:before' 		=> 'font-size: {{SIZE}}em',
						],
					]
				);

				$this->add_control(
					'points_background',
					[
						'label' 	=> __( 'Background Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'scheme' 	=> [
							'type' 		=> Scheme_Color::get_type(),
							'value' 	=> Scheme_Color::COLOR_1,
						],
						'selectors' => [
							'{{WRAPPER}} .timeline-item .timeline-item__point' 	=> 'background-color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					'icons_color',
					[
						'label' 	=> __( 'Icons Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'default'	=> '',
						'selectors' => [
							'{{WRAPPER}} .timeline-item .timeline-item__point' 		=> 'color: {{VALUE}};',
						],
					]
				);

				$this->add_group_control(
					Group_Control_Text_Shadow::get_type(),
					[
						'name' 		=> 'points_text_shadow',
						'selector' 	=> '{{WRAPPER}} .timeline-item .timeline-item__point',
					]
				);

			$this->end_controls_tab();

			$this->start_controls_tab( 'points_hover', [ 'label' => __( 'Hover', 'elementor-extras' ) ] );

				$this->add_control(
					'points_size_hover',
					[
						'label' 	=> __( 'Scale', 'elementor-extras' ),
						'type' 		=> Controls_Manager::SLIDER,
						'default' 	=> [
							'size' 	=> 1,
						],
						'range' 		=> [
							'px' 		=> [
								'min' 	=> 0.5,
								'max' 	=> 2,
								'step'	=> 0.01
							],
						],
						'selectors' => [
							// General
							'{{WRAPPER}} .timeline-item:hover .timeline-item__point,
							{{WRAPPER}} .timeline-item.is--focused:hover .timeline-item__point' 		=> 'transform: scale({{SIZE}})',
						],
					]
				);

				$this->add_control(
					'points_background_hover',
					[
						'label' 	=> __( 'Background Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'default'	=> '',
						'selectors' => [
							'{{WRAPPER}} .timeline-item:hover .timeline-item__point,
							{{WRAPPER}} .timeline-item.is--focused:hover .timeline-item__point' 			=> 'background-color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					'icons_color_hover',
					[
						'label' 	=> __( 'Icons Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'default'	=> '',
						'selectors' => [
							'{{WRAPPER}} .timeline-item:hover .timeline-item__point,
							{{WRAPPER}} .timeline-item.is--focused:hover .timeline-item__point' 		=> 'color: {{VALUE}};',
						],
					]
				);

				$this->add_group_control(
					Group_Control_Text_Shadow::get_type(),
					[
						'name' 		=> 'points_text_shadow_hover',
						'selector' 	=> '{{WRAPPER}} .timeline-item:hover .timeline-item__point, {{WRAPPER}} .timeline-item:hover .timeline-item__point'
					]
				);

			$this->end_controls_tab();

			$this->start_controls_tab( 'points_focused', [ 'label' => __( 'Focused', 'elementor-extras' ) ] );

				$this->add_control(
					'points_size_focused',
					[
						'label' 	=> __( 'Scale', 'elementor-extras' ),
						'type' 		=> Controls_Manager::SLIDER,
						'default' 	=> [
							'size' 	=> 1,
						],
						'range' 		=> [
							'px' 		=> [
								'min' 	=> 0.5,
								'max' 	=> 2,
								'step'	=> 0.01
							],
						],
						'selectors' => [
							// General
							'{{WRAPPER}} .timeline-item.is--focused .timeline-item__point' 		=> 'transform: scale({{SIZE}})',
						],
					]
				);

				$this->add_control(
					'points_background_focused',
					[
						'label' 	=> __( 'Background Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'scheme' 	=> [
							'type' 		=> Scheme_Color::get_type(),
							'value' 	=> Scheme_Color::COLOR_4,
						],
						'selectors' => [
							'{{WRAPPER}} .timeline-item.is--focused .timeline-item__point' 			=> 'background-color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					'icons_color_focused',
					[
						'label' 	=> __( 'Icons Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'default'	=> '',
						'selectors' => [
							'{{WRAPPER}} .timeline-item.is--focused .timeline-item__point' 		=> 'color: {{VALUE}};',
						],
					]
				);

				$this->add_group_control(
					Group_Control_Text_Shadow::get_type(),
					[
						'name' 		=> 'points_text_shadow_focused',
						'selector' 	=> '{{WRAPPER}} .timeline-item.is--focused .timeline-item__point',
					]
				);

			$this->end_controls_tab();

			$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_line',
			[
				'label' 	=> __( 'Line', 'elementor-extras' ),
				'tab' 		=> Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_control(
				'line_background',
				[
					'label' 	=> __( 'Background Color', 'elementor-extras' ),
					'type' 		=> Controls_Manager::COLOR,
					'scheme' 	=> [
						'type' 		=> Scheme_Color::get_type(),
						'value' 	=> Scheme_Color::COLOR_1,
					],
					'selectors' => [
						'{{WRAPPER}} .ee-timeline__line' => 'background-color: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				'progress_background',
				[
					'label' 	=> __( 'Progress Color', 'elementor-extras' ),
					'type' 		=> Controls_Manager::COLOR,
					'scheme' 	=> [
						'type' 		=> Scheme_Color::get_type(),
						'value' 	=> Scheme_Color::COLOR_4,
					],
					'selectors' => [
						'{{WRAPPER}} .ee-timeline__line__inner' => 'background-color: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				'line_thickness',
				[
					'label' 	=> __( 'Thickness', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SLIDER,
					'default' 	=> [
						'size' 	=> 4,
					],
					'range' 		=> [
						'px' 		=> [
							'min' 	=> 1,
							'max' 	=> 8,
						],
					],
					'selectors' => [
						'{{WRAPPER}} .ee-timeline__line' => 'width: {{SIZE}}px;',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' 		=> 'line_border',
					'label' 	=> __( 'Image Border', 'elementor-extras' ),
					'selector' 	=> '{{WRAPPER}} .ee-timeline__line',
				]
			);

			$this->add_control(
				'line_border_radius',
				[
					'label' 		=> __( 'Border Radius', 'elementor-extras' ),
					'type' 			=> Controls_Manager::DIMENSIONS,
					'size_units' 	=> [ 'px', '%' ],
					'selectors' 	=> [
						'{{WRAPPER}} .ee-timeline__line' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

		$this->end_controls_section();
		
	}

	protected function get_horizontal_aligment() {

		if ( '' === $this->get_settings( 'align' ) )
			return 'center';

		return $this->get_settings( 'align' );
	}

	protected function get_vertical_aligment() {

		if ( '' === $this->get_settings( 'cards_align' ) )
			return 'top';
		
		return $this->get_settings( 'cards_align' );
	}

	protected function add_global_render_attributes() {
		$settings = $this->get_settings();

		$this->add_render_attribute( 'wrapper', 'class', [
			'ee-timeline',
			'ee-timeline--vertical',
			'ee-timeline-align--' . $this->get_horizontal_aligment(),
			'ee-timeline-align--' . $this->get_vertical_aligment(),
		] );

		$this->add_render_attribute( 'item', 'class', [
			'ee-timeline__item',
			'timeline-item',
		] );

		if ( $settings['animate_in'] === 'yes' ) {
			$this->add_render_attribute( 'item', 'class', 'is--hidden' );
		}

		$this->add_render_attribute( 'line', 'class', 'ee-timeline__line' );
		$this->add_render_attribute( 'line-inner', 'class', 'ee-timeline__line__inner' );

		$this->add_render_attribute( 'card-wrapper', 'class', 'timeline-item__card-wrapper' );
		$this->add_render_attribute( 'icon', 'class', $settings['global_icon'] );
		$this->add_render_attribute( 'point', 'class', [
			'timeline-item__point',
			'point',
		] );

		$this->add_render_attribute( 'meta', 'class', [
			'timeline-item__meta',
			'meta',
		] );

		$this->add_render_attribute( 'image', 'class', [
			'timeline-item__img',
			'elementor-post__thumbnail',
		] );

		$this->add_render_attribute( 'content', 'class', 'timeline-item__content' );
		$this->add_render_attribute( 'arrow', 'class', 'timeline-item__card__arrow' );
		$this->add_render_attribute( 'meta-wrapper', 'class', 'timeline-item__meta-wrapper' );
	}

	protected function render() {
		$settings = $this->get_settings();

		$this->add_global_render_attributes();

		?>

		<section <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>

			<?php

			$this->render_line();

			if ( $settings['source'] === 'custom' || ! is_elementor_pro_active() ) {

				$this->render_custom_cards();

			} else {

				$this->query_posts();

				$wp_query = $this->get_query();

				if ( ! $wp_query->found_posts )
					return;

				while ( $wp_query->have_posts() ) {

					$wp_query->the_post();

					$this->render_post_card();			
				}

				wp_reset_postdata();
			}

			?>
		</section><?php
	}

	protected function render_custom_cards() {

		$settings = $this->get_settings();

		$counter = 1;

		foreach ( $settings['items'] as $index => $item ) {

			$card_tag 	= 'div';
			$point 		= '';
			$meta 		= '';

			$this->add_render_attribute( 'item-' . $index, 'class', [
				'elementor-repeater-item-' . $item['_id'],
				'ee-timeline__item',
				'timeline-item',
			] );

			if ( $settings['animate_in'] === 'yes' ) {
				$this->add_render_attribute( 'item-' . $index, 'class', 'is--hidden' );
			}

			if ( isset( $item['icon'] ) && $item['custom_style'] === 'yes' && $item['icon'] !== '' ) {
				$this->add_render_attribute( 'item-icon-' . $index, 'class', $item['icon'] );
			} else {
				$this->add_render_attribute( 'item-icon-' . $index, 'class', $settings['global_icon'] );
			}

			$this->add_render_attribute( 'card-' . $index, 'class', [
				'timeline-item__card',
				'card',
			] );

			if ( ! empty( $item['link']['url'] ) ) {

				$card_tag = 'a';

				$this->add_render_attribute( 'card-' . $index, 'href', $item['link']['url'] );

				if ( $item['link']['is_external'] ) {
					$this->add_render_attribute( 'card-' . $index, 'target', '_blank' );
				}

				if ( $item['link']['nofollow'] ) {
					$this->add_render_attribute( 'card-' . $index, 'rel', 'nofollow' );
				}
			}

			$wysiwyg_key = $this->get_repeater_setting_key( 'content', 'items', $index );
			$meta_key = $this->get_repeater_setting_key( 'date', 'items', $index );

			$this->add_render_attribute( $wysiwyg_key, 'class', 'timeline-item__content__wysiwyg' );
			$this->add_render_attribute( $meta_key, 'class', [
				'timeline-item__meta',
				'meta',
			] );

			$this->add_inline_editing_attributes( $wysiwyg_key, 'advanced' );
			$this->add_inline_editing_attributes( $meta_key, 'basic' );

			$point  = '<div ' . $this->get_render_attribute_string( 'point' ) . '>';
			$point .= '<i ' . $this->get_render_attribute_string( 'item-icon-' . $index ) . '></i>';
			$point .= '</div>';

			$meta .= '<div ' . $this->get_render_attribute_string( $meta_key ) . '>';
			$meta .= $this->parse_text_editor( $item['date'] );
			$meta .= '</div>';
		?>
		
			<div <?php echo $this->get_render_attribute_string( 'item-' . $index ); ?>>

				<?php if ( $this->get_horizontal_aligment() === 'center' ) echo $point; ?>

				<div <?php echo $this->get_render_attribute_string( 'card-wrapper' ); ?>>
					<<?php echo $card_tag; ?> <?php echo $this->get_render_attribute_string( 'card-' . $index ); ?>>

						<?php if ( ! empty( $item['image']['url'] ) ) { ?>

						<!-- image -->
						<div <?php echo $this->get_render_attribute_string( 'image' ); ?>>
							<?php echo Group_Control_Image_Size::get_attachment_image_html( $item ); ?>
						</div>

						<?php } ?>

						<?php if ( '' !== $item['content'] ) { ?>

						<!-- content -->
						<div <?php echo $this->get_render_attribute_string( 'content' ); ?>>

							<!-- meta -->
							<?php echo $meta; ?>

							<!-- body -->
							<div <?php echo $this->get_render_attribute_string( $wysiwyg_key ); ?>>
								<?php echo $this->parse_text_editor( $item['content'] ); ?>
							</div>

						</div>

						<?php } ?>
						
						<!-- arrow -->
						<?php echo $this->render_card_arrow(); ?>

					</<?php echo $card_tag; ?>>
				</div>

				<div <?php echo $this->get_render_attribute_string( 'meta-wrapper' ); ?>>
					<?php if ( $this->get_horizontal_aligment() !== 'center' ) { echo $point; } else { echo $meta; } ?>
				</div>
			</div>

		<?php

		$counter++;

		}
	}

	protected function render_post_card() {

		$settings = $this->get_settings();

		$card_tag 	= 'div';
		$point 		= '';
		$meta 		= '';

		$this->add_render_attribute( 'card-' . get_the_ID(), 'class', [
			'timeline-item__card',
			'card',
			implode( ' ', get_post_class() ),
		] );

		if ( $settings['card_links'] === 'yes' ) {

			$card_tag = 'a';

			$this->add_render_attribute( 'card-' . get_the_ID(), 'href', get_permalink() );
		}

		$point  = '<div ' . $this->get_render_attribute_string( 'point' ) . '>';
		$point .= '<i ' . $this->get_render_attribute_string( 'icon' ) . '></i>';
		$point .= '</div>';

		$meta .= '<div ' . $this->get_render_attribute_string( 'meta' ) . '>';
		$meta .= $this->render_date( false );
		$meta .= '</div>';

		?>
		<div <?php echo $this->get_render_attribute_string( 'item' ); ?>>

			<?php if ( $this->get_horizontal_aligment() === 'center' ) echo $point; ?>

			<div <?php echo $this->get_render_attribute_string( 'card-wrapper' ); ?>>
				<<?php echo $card_tag; ?> <?php echo $this->get_render_attribute_string( 'card-' . get_the_ID() ); ?>>

					<?php if ( $settings['post_thumbnail'] === 'yes' && has_post_thumbnail() ) {
						$this->render_thumbnail( 'yes' !== $settings['card_links'] );
					} ?>

					<!-- content -->
					<div <?php echo $this->get_render_attribute_string( 'content' ); ?>>

						<?php

						if ( 'product' !== $settings['posts_post_type'] )
							echo $meta;

						if ( $settings['post_title'] === 'yes' ) {
							$this->render_title( 'yes' !== $settings['card_links'] );	
						}

						if ( is_woocommerce_active() && $settings['post_buy'] === 'yes' && $settings['card_links'] !== 'yes' )
							$this->render_product_attributes();

						if ( $settings['post_excerpt'] )
							the_excerpt();

						if ( is_woocommerce_active() && $settings['post_buy'] === 'yes' && $settings['card_links'] !== 'yes' )
							echo do_shortcode('[add_to_cart id="' . get_the_ID() . '" style="border:0px;padding:0px"]');

						?>

					</div>

					<?php echo $this->render_card_arrow(); ?>

				</<?php echo $card_tag; ?>>

			</div>

			<div <?php echo $this->get_render_attribute_string( 'meta-wrapper' ); ?>>
				<?php if ( $this->get_horizontal_aligment() !== 'center' ) { echo $point; } else { echo $meta; } ?>
			</div>
		</div>
		<?php	
	}

	protected function render_line() {
		?><div <?php echo $this->get_render_attribute_string( 'line' ); ?>>
			<div <?php echo $this->get_render_attribute_string( 'line-inner' ); ?>></div>
		</div><?php
	}

	protected function render_card_arrow() {
		?><!-- arrow -->
		<div <?php echo $this->get_render_attribute_string( 'arrow' ); ?>></div><?php
	}

	protected function render_product_attributes() {
		global $post;

		$product = wc_get_product( get_the_ID() );

		if ( ! $product->has_attributes() )
			return;

		echo '<table class="shop_attributes">';
		echo '<tbody>';

		$attributes = $product->get_attributes();
		$excluded_attributes = $this->get_settings( 'post_product_attributes_exclude' );

		$excluded_attributes = array_map( 'trim', explode(',', strtolower( $excluded_attributes ) ) );

		foreach ($attributes as $attribute) {

			$label 	= wc_attribute_label( $attribute->get_name() );
			$id 	= $attribute->get_id();

			if ( ! empty( $excluded_attributes ) && (
				in_array( strtolower( $label ), $excluded_attributes ) ||
				in_array( strtolower( $id ), $excluded_attributes ) ||
				( ! empty( $id ) && in_array( $id, $excluded_attributes ) )
			) )
				continue;

			$values = array();

			if ( $attribute->is_taxonomy() ) {
				$attribute_taxonomy = $attribute->get_taxonomy_object();
				$attribute_values = wc_get_product_terms( $product->get_id(), $attribute->get_name(), array( 'fields' => 'all' ) );

				foreach ( $attribute_values as $attribute_value ) {
					$value_name = esc_html( $attribute_value->name );
					$values[] = $value_name;
				}
			} else {
				$values = $attribute->get_options();

				foreach ( $values as &$value ) {
					$value = make_clickable( esc_html( $value ) );
				}
			}

			echo '<tr>';
			echo '<th>' . $label . '</th>';
			echo '<td>' . apply_filters( 'woocommerce_attribute', wpautop( wptexturize( implode( ', ', $values ) ) ), $attribute, $values ) . '</td>';
			echo '</tr>';
		}

		echo '</tbody>';
		echo '</table>';
	}

	protected function render_thumbnail( $link = true ) {
		global $post;

		$settings = $this->get_settings();

		$settings['post_thumbnail_size'] = [
			'id' => get_post_thumbnail_id(),
		];

		$thumbnail_html = Group_Control_Image_Size::get_attachment_image_html( $settings, 'post_thumbnail_size' );

		?>

		<div <?php echo $this->get_render_attribute_string( 'image' ); ?>>
			<?php if ( $link ) : ?><a href="<?php echo the_permalink(); ?>"><?php endif; ?>
				<?php echo $thumbnail_html; ?>
			<?php if ( $link ) : ?></a><?php endif; ?>
		</div><?php
	}

	protected function render_title( $link = true, $echo = true ) {
		global $post;

		$title_before = ( $link ) ? '<a href="' . get_permalink() . '">' : '';
		$title_after = ( $link ) ? '</a>' : '';

		$title = '<h2>' . $title_before . $post->post_title . $title_after . '</h2>';

		if ( $echo )
			echo $title;
		else return $title;
	}

	protected function render_date( $echo = true ) {
		
		$date = apply_filters( 'the_date', get_the_date(), get_option( 'date_format' ), '', '' );

		if ( $echo )
			echo $date;
		else return $date;
	}

	public function query_posts() {

		if ( ! is_elementor_pro_active() )
			return;

		$query_args = Module::get_query_args( 'posts', $this->get_settings() );

		$query_args['posts_per_page'] = $this->get_settings( 'posts_per_page' );

		$this->_query = new \WP_Query( $query_args );
	}

	protected function _content_template() {
		
	}
}
