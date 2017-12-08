<?php
namespace ElementorExtras\Modules\Posts\Widgets;

use ElementorExtras\Base\Extras_Widget;
use ElementorExtras\Group_Control_Button_Effects;

// Elementor Classes
use Elementor\Repeater;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Scheme_Typography;

// Elementor Pro Classes
use ElementorPro\Modules\QueryControl\Controls\Group_Control_Posts;
use ElementorPro\Modules\QueryControl\Module;

// Elementor Extras Classes
use ElementorExtras\Modules\Posts\Skins;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Elementor Posts
 *
 * @since 1.6.0
 */
class Products extends Extras_Widget {

	/**
	 * @var \WP_Query
	 */
	private $_query 	= null;
	private $_filters 	= [];
	private $_ee 		= null;

	protected $_has_template_content = false;

	/**
	 * @var array
	 */
	public $_content_parts = [
		'terms',
		'title',
		'price',
		'sale',
		'rating',
		'add_to_cart',
	];

	public function get_name() {
		return 'products';
	}

	public function get_title() {
		return __( 'Products', 'elementor-extras' );
	}

	public function get_icon() {
		return 'nicon nicon-posts';
	}

	public function get_categories() {
		return [ 'elementor-extras' ];
	}

	public function get_script_depends() {
		return [
			'jquery-resize',
			'infinite-scroll',
			'isotope',
			'filtery',
		];
	}

	protected function _register_skins() {
		// $this->add_skin( new Skins\Skin_Classic( $this ) );
		// $this->add_skin( new Skins\Skin_Carousel( $this ) );
	}

	public function get_query() {
		return $this->_query;
	}

	protected function get_taxonomies() {

		$options = [];

		$taxonomies = get_taxonomies( array(
			'show_in_nav_menus' => true
		), 'objects' );

		if ( empty( $taxonomies ) ) {
			$options[ '' ] = __( 'No taxonomies found', 'elementor-extras' );
			return $options;
		}

		foreach ( $taxonomies as $taxonomy ) {
			$options[ $taxonomy->name ] = $taxonomy->label;
		}

		return $options;
	}

	public function set_filters( $taxonomy = null ) {
		global $post;

		if ( ! $taxonomy )
			return;

		foreach ( $this->_query->posts as $post ) {
			$terms = wp_get_post_terms( $post->ID, $taxonomy );

			$filters = [];

			foreach ($terms as $term) {
				if ( ! array_key_exists( $term->term_id, $this->_filters ) ) {
					$this->_filters[ $term->term_id ] = $term;
				}

				$filters[ $term->term_id ] = $term;
			}

			$post->filters = $filters;
		}
	}

	public function get_filters() {
		return $this->_filters;
	}

	public function get_terms() {

		$settings 	= $this->get_settings();
		$taxonomy 	= $settings['post_terms_taxonomy'];

		if ( empty( $taxonomy ) )
			return false;

		$terms = get_the_terms( get_the_ID(), $taxonomy );

		if ( empty( $terms ) )
			return false;

		return $terms;

	}

	public function get_current_page() {
		if ( '' === $this->get_settings( 'pagination_infinite_scroll' ) && '' === $this->get_settings( 'pagination' ) ) {
			return 1;
		}

		return max( 1, get_query_var( 'paged' ), get_query_var( 'page' ) );
	}

	protected function _register_controls() {
		$this->_ee = \ElementorExtras\ElementorExtrasPlugin::$instance;

		$this->register_layout_content_controls();
		$this->register_query_content_controls();

		$this->register_media_content_controls();
		$this->register_terms_content_controls();
		$this->register_title_content_controls();
		$this->register_excerpt_content_controls();
		$this->register_price_content_controls();
		$this->register_sale_content_controls();
		$this->register_rating_content_controls();
		$this->register_add_to_cart_content_controls();

		$this->register_post_style_controls();
		$this->register_header_style_controls();
		$this->register_media_style_controls();
		$this->register_body_style_controls();
		$this->register_footer_style_controls();

		$this->register_terms_style_controls();
		$this->register_title_style_controls();
		$this->register_add_to_cart_style_controls();
	}

	protected function register_layout_content_controls() {

		$this->start_controls_section(
			'section_layout',
			[
				'label' => __( 'Layout', 'elementor-extras' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

			$this->add_responsive_control(
				'columns',
				[
					'label' 	=> __( 'Columns', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SELECT,
					'default' 	=> '3',
					'tablet_default' => '2',
					'mobile_default' => '1',
					'options' => [
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

		$this->end_controls_section();

	}

	protected function register_query_content_controls() {
		
		$this->start_controls_section(
			'section_query',
			[
				'label' => __( 'Query', 'elementor-extras' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
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
				'posts_per_page',
				[
					'label' => __( 'Posts Per Page', 'elementor-extras' ),
					'type' => Controls_Manager::NUMBER,
					'default' => 6,
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

	protected function register_media_content_controls() {

		$this->start_controls_section(
			'section_media',
			[
				'label' => __( 'Media', 'elementor-extras' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

			$this->add_control(
				'post_media',
				[
					'label' 		=> __( 'Show', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SWITCHER,
					'default'		=> 'yes',
					'return_value' 	=> 'yes',
				]
			);

			$this->add_control(
				'post_media_position',
				[
					'label' 		=> __( 'Position', 'elementor-extras' ),
					'type' 			=> Controls_Manager::CHOOSE,
					'default' 		=> 'left',
					'options' 		=> [
						'left' 		=> [
							'title' => __( 'Left', 'elementor-extras' ),
							'icon' 	=> 'eicon-h-align-left',
						],
						'right' 	=> [
							'title' => __( 'Right', 'elementor-extras' ),
							'icon' 	=> 'eicon-h-align-right',
						],
					],
					'label_block'	=> false,
					'condition'		=> [
						'post_media!' => '',
						'columns' => '1',
					],
				]
			);

			$this->add_responsive_control(
				'post_media_width',
				[
					'label' 		=> __( 'Width', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SLIDER,
					'range' 		=> [
						'px' 		=> [
							'min' => 20,
							'max' => 80,
						],
					],
					'selectors' 	=> [
						'{{WRAPPER}} .ee-post__media' => 'flex-basis: {{SIZE}}%;',
						'{{WRAPPER}} .ee-post__content' => 'flex-basis: calc( 100% - {{SIZE}}% );',
					],
					'condition'		=> [
						'columns' => '1',
						'post_media!' => '',
						'post_media_position!' => '',
					],
				]
			);

			$this->add_control(
				'post_media_custom_height',
				[
					'label' 		=> __( 'Custom Height', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SWITCHER,
					'default'		=> '',
					'return_value' 	=> 'ratio',
					'prefix_class'	=> 'ee-posts-thumbnail-',
					'condition'		=> [
						'post_media!' => '',
					],
				]
			);

			$this->add_responsive_control(
				'post_media_height',
				[
					'label' 		=> __( 'Height', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SLIDER,
					'range' 		=> [
						'px' 		=> [
							'min' => 40,
							'max' => 200,
						],
					],
					'selectors' 	=> [
						'{{WRAPPER}} .ee-post__media:before' => 'padding-bottom: {{SIZE}}%',
					],
					'condition'		=> [
						'post_media!' => '',
						'post_media_custom_height!' => '',
					],
				]
			);

			$this->add_control(
				'post_media_thumbnail_heading',
				[
					'label' 	=> __( 'Thumbnail', 'elementor-extras' ),
					'type' 		=> Controls_Manager::HEADING,
					'separator' => 'before',
					'condition'		=> [
						'post_media!' => '',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Image_Size::get_type(),
				[
					'name' 			=> 'post_media_thumbnail_size',
					'label' 		=> __( 'Thumbnail Size', 'elementor-extras' ),
					'default' 		=> 'large',
					'exclude' 		=> [ 'custom' ],
					'condition'		=> [
						'post_media!' => '',
					],
				]
			);

		$this->end_controls_section();

	}

	protected function register_terms_content_controls() {

		$this->start_controls_section(
			'section_terms',
			[
				'label' => __( 'Terms', 'elementor-extras' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

			$this->add_control(
				'post_terms_position',
				[
					'label' 		=> __( 'Position', 'elementor-extras' ),
					'type' 			=> Controls_Manager::CHOOSE,
					'default' 		=> 'header',
					'label_block'	=> false,
					'options' 		=> [
						'header'    		=> [
							'title' 	=> __( 'Header', 'elementor-extras' ),
							'icon' 		=> 'nicon nicon-position-header',
						],
						'media'    		=> [
							'title' 	=> __( 'Media', 'elementor-extras' ),
							'icon' 		=> 'nicon nicon-position-media',
						],
						'body'    		=> [
							'title' 	=> __( 'Body', 'elementor-extras' ),
							'icon' 		=> 'nicon nicon-position-body',
						],
						'footer'    		=> [
							'title' 	=> __( 'Footer', 'elementor-extras' ),
							'icon' 		=> 'nicon nicon-position-footer',
						],
					],
				]
			);

			$this->add_control(
				'post_terms_order',
				[
					'label' 	=> __( 'Order', 'elementor-extras' ),
					'description' => __( 'Enter an order number higher or lower than another elements order number.', 'elementor-extras' ),
					'type' 		=> Controls_Manager::NUMBER,
					'default' 	=> '',
					'min'     	=> 0,
					'condition' => [
						'post_terms_position!' => '',
					],
					'selectors' => [
						'{{WRAPPER}} .ee-post__terms' => 'order: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				'post_terms_taxonomy',
				[
					'label' 		=> __( 'Taxonomy', 'elementor-pro' ),
					'type' 			=> Controls_Manager::SELECT2,
					'label_block' 	=> true,
					'default' 		=> 'category',
					'options' 		=> $this->get_taxonomies(),
					'condition' 	=> [
						'post_terms_position!' => '',
					],
				]
			);

			$this->add_control(
				'post_terms_count',
				[
					'label'   		=> __( 'Count', 'elementor-pro' ),
					'description' 	=> __( 'How many terms to show (enter 0 to show all terms)', 'elementor-extras' ),
					'type'    		=> Controls_Manager::NUMBER,
					'default' 		=> 1,
					'condition' 	=> [
						'post_terms_position!' => '',
					],
				]
			);

			$this->add_control(
				'post_terms_prefix',
				[
					'label' 		=> __( 'Prefix', 'elementor-extras' ),
					'type' 			=> Controls_Manager::TEXT,
					'default' 		=> '',
					'placeholder' 	=> __( 'Posted in', 'elementor-extras' ),
					'condition' => [
						'post_terms_position!' => ''
					],
				]
			);

			$this->add_control(
				'post_terms_separator',
				[
					'label' 		=> __( 'Separator', 'elementor-extras' ),
					'type' 			=> Controls_Manager::TEXT,
					'default' 		=> '·',
				]
			);

		$this->end_controls_section();

	}

	protected function register_title_content_controls() {

		$this->start_controls_section(
			'section_title',
			[
				'label' => __( 'Title', 'elementor-extras' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

			$this->add_control(
				'post_title_position',
				[
					'label' 		=> __( 'Position', 'elementor-extras' ),
					'type' 			=> Controls_Manager::CHOOSE,
					'default' 		=> 'body',
					'label_block'	=> false,
					'options' 		=> [
						'header'    		=> [
							'title' 	=> __( 'Header', 'elementor-extras' ),
							'icon' 		=> 'nicon nicon-position-header',
						],
						'media'    		=> [
							'title' 	=> __( 'Media', 'elementor-extras' ),
							'icon' 		=> 'nicon nicon-position-media',
						],
						'body'    		=> [
							'title' 	=> __( 'Body', 'elementor-extras' ),
							'icon' 		=> 'nicon nicon-position-body',
						],
					],
				]
			);

			$this->add_control(
				'post_title_element',
				[
					'label' 	=> __( 'HTML Element', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SELECT,
					'options' 	=> [
						'h1' 	=> __( 'H1', 'elementor-extras' ),
						'h2' 	=> __( 'H2', 'elementor-extras' ),
						'h3' 	=> __( 'H3', 'elementor-extras' ),
						'h4' 	=> __( 'H4', 'elementor-extras' ),
						'h5' 	=> __( 'H5', 'elementor-extras' ),
						'h6' 	=> __( 'H6', 'elementor-extras' ),
						'div'	=> __( 'div', 'elementor-extras' ),
						'span' 	=> __( 'span', 'elementor-extras' ),
					],
					'default' => 'h2',
				]
			);

			$this->add_control(
				'post_title_order',
				[
					'label' 	=> __( 'Order', 'elementor-extras' ),
					'description' => __( 'Enter an order number higher or lower than another elements order number.', 'elementor-extras' ),
					'type' 		=> Controls_Manager::NUMBER,
					'default' 	=> '',
					'min'     	=> 0,
					'condition' => [
						'post_title_position!' => '',
					],
					'selectors' => [
						'{{WRAPPER}} .ee-post__title' => 'order: {{VALUE}};',
					],
				]
			);

		$this->end_controls_section();

	}

	protected function register_excerpt_content_controls() {

		$this->start_controls_section(
			'section_excerpt',
			[
				'label' => __( 'Excerpt', 'elementor-extras' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

			$this->add_control(
				'post_excerpt_position',
				[
					'label' 		=> __( 'Position', 'elementor-extras' ),
					'type' 			=> Controls_Manager::CHOOSE,
					'default' 		=> 'body',
					'label_block'	=> false,
					'options' 		=> [
						'body'    		=> [
							'title' 	=> __( 'Body', 'elementor-extras' ),
							'icon' 		=> 'nicon nicon-position-body',
						],
						'footer'    		=> [
							'title' 	=> __( 'Footer', 'elementor-extras' ),
							'icon' 		=> 'nicon nicon-position-footer',
						],
					],
				]
			);

			$this->add_control(
				'post_excerpt_length',
				[
					'label' 	=> __( 'Excerpt Length', 'elementor-extras' ),
					'type' 		=> Controls_Manager::NUMBER,
					'default' 	=> apply_filters( 'excerpt_length', 25 ),
					'condition' => [
						'post_excerpt_position!' => '',
					],
				]
			);

			$this->add_control(
				'post_excerpt_order',
				[
					'label' 	=> __( 'Order', 'elementor-extras' ),
					'description' => __( 'Enter an order number higher or lower than another elements order number.', 'elementor-extras' ),
					'type' 		=> Controls_Manager::NUMBER,
					'default' 	=> '',
					'min'     	=> 0,
					'condition' => [
						'post_excerpt_position!' => '',
					],
					'selectors' => [
						'{{WRAPPER}} .ee-post__excerpt' => 'order: {{VALUE}};',
					],
				]
			);

		$this->end_controls_section();

	}

	protected function register_metas_content_controls() {

		$this->start_controls_section(
			'section_metas',
			[
				'label' => __( 'Metas', 'elementor-extras' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

			$this->add_control(
				'post_metas_separator',
				[
					'label' 		=> __( 'Separator', 'elementor-extras' ),
					'type' 			=> Controls_Manager::TEXT,
					'default' 		=> '·',
				]
			);

			$this->add_control(
				'post_metas_order',
				[
					'label' 	=> __( 'Order', 'elementor-extras' ),
					'description' => __( 'Enter an order number higher or lower than another elements order number.', 'elementor-extras' ),
					'type' 		=> Controls_Manager::NUMBER,
					'default' 	=> '',
					'min'     	=> 0,
					'selectors' => [
						'{{WRAPPER}} .ee-post__metas' => 'order: {{VALUE}};',
					],
				]
			);

			$this->register_author_content_controls();
			$this->register_date_content_controls();
			$this->register_comments_content_controls();

		$this->end_controls_section();
	}

	protected function register_author_content_controls() {

		$this->add_control(
			'post_author_heading',
			[
				'label' => __( 'Author', 'elementor-extras' ),
				'type' 	=> Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'post_author_position',
			[
				'label' 		=> __( 'Position', 'elementor-extras' ),
				'type' 			=> Controls_Manager::CHOOSE,
				'default' 		=> 'footer',
				'label_block'	=> false,
				'options' 		=> [
					'header'    		=> [
						'title' 	=> __( 'Header', 'elementor-extras' ),
						'icon' 		=> 'nicon nicon-position-header',
					],
					'media'    		=> [
						'title' 	=> __( 'Media', 'elementor-extras' ),
						'icon' 		=> 'nicon nicon-position-media',
					],
					'body'    		=> [
						'title' 	=> __( 'Body', 'elementor-extras' ),
						'icon' 		=> 'nicon nicon-position-body',
					],
					'footer'    		=> [
						'title' 	=> __( 'Footer', 'elementor-extras' ),
						'icon' 		=> 'nicon nicon-position-footer',
					],
				],
			]
		);

		$this->add_control(
			'post_author_order',
			[
				'label' 	=> __( 'Name Order', 'elementor-extras' ),
				'description' => __( 'Enter an order number higher or lower than another elements order number.', 'elementor-extras' ),
				'type' 		=> Controls_Manager::NUMBER,
				'default' 	=> 1,
				'min'     	=> 0,
				'condition' => [
					'post_author_position!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .ee-post__meta--author' => 'order: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'post_author_prefix',
			[
				'label' 		=> __( 'Prefix', 'elementor-extras' ),
				'type' 			=> Controls_Manager::TEXT,
				'default' 		=> '',
				'placeholder' 	=> __( 'Posted by', 'elementor-extras' ),
				'condition' => [
					'post_author_position!' => ''
				],
			]
		);

		$this->add_control(
			'post_avatar_heading',
			[
				'label' => __( 'Avatar', 'elementor-extras' ),
				'type' 	=> Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'post_avatar_position',
			[
				'label' 		=> __( 'Position', 'elementor-extras' ),
				'type' 			=> Controls_Manager::CHOOSE,
				'default' 		=> 'footer',
				'label_block'	=> false,
				'options' 		=> [
					'header'    		=> [
						'title' 	=> __( 'Header', 'elementor-extras' ),
						'icon' 		=> 'nicon nicon-position-header',
					],
					'media'    		=> [
						'title' 	=> __( 'Media', 'elementor-extras' ),
						'icon' 		=> 'nicon nicon-position-media',
					],
					'body'    		=> [
						'title' 	=> __( 'Body', 'elementor-extras' ),
						'icon' 		=> 'nicon nicon-position-body',
					],
					'footer'    		=> [
						'title' 	=> __( 'Footer', 'elementor-extras' ),
						'icon' 		=> 'nicon nicon-position-footer',
					],
				],
			]
		);

	}

	protected function register_date_content_controls() {

		$this->add_control(
			'post_date_heading',
			[
				'label' => __( 'Date', 'elementor-extras' ),
				'type' 	=> Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'post_date_position',
			[
				'label' 		=> __( 'Position', 'elementor-extras' ),
				'type' 			=> Controls_Manager::CHOOSE,
				'default' 		=> 'footer',
				'label_block'	=> false,
				'options' 		=> [
					'header'    		=> [
						'title' 	=> __( 'Header', 'elementor-extras' ),
						'icon' 		=> 'nicon nicon-position-header',
					],
					'media'    		=> [
						'title' 	=> __( 'Media', 'elementor-extras' ),
						'icon' 		=> 'nicon nicon-position-media',
					],
					'body'    		=> [
						'title' 	=> __( 'Body', 'elementor-extras' ),
						'icon' 		=> 'nicon nicon-position-body',
					],
					'footer'    		=> [
						'title' 	=> __( 'Footer', 'elementor-extras' ),
						'icon' 		=> 'nicon nicon-position-footer',
					],
				],
			]
		);

		$this->add_control(
			'post_date_order',
			[
				'label' 	=> __( 'Order', 'elementor-extras' ),
				'description' => __( 'Enter an order number higher or lower than another elements order number.', 'elementor-extras' ),
				'type' 		=> Controls_Manager::NUMBER,
				'default' 	=> 2,
				'min'     	=> 0,
				'condition' => [
					'post_date_position!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .ee-post__meta--date' => 'order: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'post_date_prefix',
			[
				'label' 		=> __( 'Date Prefix', 'elementor-extras' ),
				'type' 			=> Controls_Manager::TEXT,
				'default' 		=> '',
				'placeholder' 	=> __( 'on', 'elementor-extras' ),
				'condition' => [
					'post_date_position!' => ''
				],
			]
		);

		$this->add_control(
			'post_date_time',
			[
				'label' 		=> __( 'Show Time', 'elementor-extras' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default'		=> 'yes',
				'return_value' 	=> 'yes',
				'condition' 	=> [
					'post_date_position!' => ''
				],
				'separator'		=> 'before',
			]
		);

		$this->add_control(
			'post_date_time_prefix',
			[
				'label' 		=> __( 'Time Prefix', 'elementor-extras' ),
				'type' 			=> Controls_Manager::TEXT,
				'default' 		=> '',
				'placeholder' 	=> __( 'at', 'elementor-extras' ),
				'condition' => [
					'post_date_time' 		=> 'yes',
					'post_date_position!' 	=> '',
				],
			]
		);

	}

	protected function register_comments_content_controls() {

		$this->add_control(
			'post_comments_heading',
			[
				'label' => __( 'Comments', 'elementor-extras' ),
				'type' 	=> Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'post_comments_position',
			[
				'label' 		=> __( 'Position', 'elementor-extras' ),
				'type' 			=> Controls_Manager::CHOOSE,
				'default' 		=> 'footer',
				'label_block'	=> false,
				'options' 		=> [
					'header'    		=> [
						'title' 	=> __( 'Header', 'elementor-extras' ),
						'icon' 		=> 'nicon nicon-position-header',
					],
					'media'    		=> [
						'title' 	=> __( 'Media', 'elementor-extras' ),
						'icon' 		=> 'nicon nicon-position-media',
					],
					'body'    		=> [
						'title' 	=> __( 'Body', 'elementor-extras' ),
						'icon' 		=> 'nicon nicon-position-body',
					],
					'footer'    		=> [
						'title' 	=> __( 'Footer', 'elementor-extras' ),
						'icon' 		=> 'nicon nicon-position-footer',
					],
				],
			]
		);

		$this->add_control(
			'post_comments_order',
			[
				'label' 	=> __( 'Order', 'elementor-extras' ),
				'description' => __( 'Enter an order number higher or lower than another elements order number.', 'elementor-extras' ),
				'type' 		=> Controls_Manager::NUMBER,
				'default' 	=> 4,
				'min'     	=> 0,
				'condition' => [
					'post_comments_position!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .ee-post__meta--comments' => 'order: {{VALUE}};',
				],
			]
		);

	}

	public function register_sale_content_controls() {

		$this->start_controls_section(
			'section_sale',
			[
				'label' => __( 'Sale', 'elementor-extras' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
				'condition' => [
					'posts_post_type' => 'product',
				]
			]
		);

			$this->add_control(
				'post_sale_position',
				[
					'label' 		=> __( 'Position', 'elementor-extras' ),
					'type' 			=> Controls_Manager::CHOOSE,
					'default' 		=> 'media',
					'label_block'	=> false,
					'options' 		=> [
						'header'    		=> [
							'title' 	=> __( 'Header', 'elementor-extras' ),
							'icon' 		=> 'nicon nicon-position-header',
						],
						'media'    		=> [
							'title' 	=> __( 'Media', 'elementor-extras' ),
							'icon' 		=> 'nicon nicon-position-media',
						],
						'body'    		=> [
							'title' 	=> __( 'Body', 'elementor-extras' ),
							'icon' 		=> 'nicon nicon-position-body',
						],
					],
				]
			);

		$this->end_controls_section();

	}

	public function register_price_content_controls() {

		$this->start_controls_section(
			'section_price',
			[
				'label' => __( 'Price', 'elementor-extras' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
				'condition' => [
					'posts_post_type' => 'product',
				]
			]
		);

			$this->add_control(
				'post_price_position',
				[
					'label' 		=> __( 'Position', 'elementor-extras' ),
					'type' 			=> Controls_Manager::CHOOSE,
					'default' 		=> 'body',
					'label_block'	=> false,
					'options' 		=> [
						'header'    		=> [
							'title' 	=> __( 'Header', 'elementor-extras' ),
							'icon' 		=> 'nicon nicon-position-header',
						],
						'media'    		=> [
							'title' 	=> __( 'Media', 'elementor-extras' ),
							'icon' 		=> 'nicon nicon-position-media',
						],
						'body'    		=> [
							'title' 	=> __( 'Body', 'elementor-extras' ),
							'icon' 		=> 'nicon nicon-position-body',
						],
					],
				]
			);

		$this->end_controls_section();

	}

	public function register_rating_content_controls() {

		$this->start_controls_section(
			'section_rating',
			[
				'label' => __( 'Rating', 'elementor-extras' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
				'condition' => [
					'posts_post_type' => 'product',
				]
			]
		);

			$this->add_control(
				'post_rating_position',
				[
					'label' 		=> __( 'Position', 'elementor-extras' ),
					'type' 			=> Controls_Manager::CHOOSE,
					'default' 		=> 'body',
					'label_block'	=> false,
					'options' 		=> [
						'header'    		=> [
							'title' 	=> __( 'Header', 'elementor-extras' ),
							'icon' 		=> 'nicon nicon-position-header',
						],
						'media'    		=> [
							'title' 	=> __( 'Media', 'elementor-extras' ),
							'icon' 		=> 'nicon nicon-position-media',
						],
						'body'    		=> [
							'title' 	=> __( 'Body', 'elementor-extras' ),
							'icon' 		=> 'nicon nicon-position-body',
						],
						'footer'    	=> [
							'title' 	=> __( 'Footer', 'elementor-extras' ),
							'icon' 		=> 'nicon nicon-position-footer',
						],
					],
				]
			);

		$this->end_controls_section();

	}

	public function register_add_to_cart_content_controls() {

		$this->start_controls_section(
			'section_add_to_cart',
			[
				'label' => __( 'Add to Cart', 'elementor-extras' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
				'condition' => [
					'posts_post_type' => 'product',
				]
			]
		);

			$this->add_control(
				'post_add_to_cart_position',
				[
					'label' 		=> __( 'Position', 'elementor-extras' ),
					'type' 			=> Controls_Manager::CHOOSE,
					'default' 		=> 'footer',
					'label_block'	=> false,
					'options' 		=> [
						'header'    		=> [
							'title' 	=> __( 'Header', 'elementor-extras' ),
							'icon' 		=> 'nicon nicon-position-header',
						],
						'media'    		=> [
							'title' 	=> __( 'Media', 'elementor-extras' ),
							'icon' 		=> 'nicon nicon-position-media',
						],
						'body'    		=> [
							'title' 	=> __( 'Body', 'elementor-extras' ),
							'icon' 		=> 'nicon nicon-position-body',
						],
						'footer'    	=> [
							'title' 	=> __( 'Footer', 'elementor-extras' ),
							'icon' 		=> 'nicon nicon-position-footer',
						],
					],
				]
			);

		$this->end_controls_section();

	}

	public function register_post_style_controls() {

		$this->start_controls_section(
			'section_style_posts',
			[
				'label' => __( 'Posts', 'elementor-extras' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_responsive_control(
				'posts_text_align',
				[
					'label' 		=> __( 'Align Text', 'elementor-extras' ),
					'type' 			=> Controls_Manager::CHOOSE,
					'default' 		=> '',
					'options' 		=> [
						'left'    		=> [
							'title' 	=> __( 'Left', 'elementor-extras' ),
							'icon' 		=> 'fa fa-align-left',
						],
						'center' 		=> [
							'title' 	=> __( 'Center', 'elementor-extras' ),
							'icon' 		=> 'fa fa-align-center',
						],
						'right' 		=> [
							'title' 	=> __( 'Right', 'elementor-extras' ),
							'icon' 		=> 'fa fa-align-right',
						],
					],
					'selectors'		=> [
						'{{WRAPPER}} .ee-post' 	=> 'text-align: {{VALUE}};',
					]
				]
			);

			$this->start_controls_tabs( 'posts_tabs_hover' );

			$this->start_controls_tab( 'posts_tab_default', [ 'label' => __( 'Default', 'elementor-extras' ) ] );

				$this->add_control(
					'post_background_color',
					[
						'label' 	=> __( 'Background Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ee-post' => 'background-color: {{VALUE}};',
						],
					]
				);

			$this->end_controls_tab();

			$this->start_controls_tab( 'posts_tab_hover', [ 'label' => __( 'Hover', 'elementor-extras' ) ] );

				$this->add_control(
					'post_background_color_hover',
					[
						'label' 	=> __( 'Background Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ee-post:hover' => 'background-color: {{VALUE}};',
						],
					]
				);

			$this->end_controls_tab();

			$this->end_controls_tabs();

		$this->end_controls_section();
	}

	public function register_header_style_controls() {

		$this->start_controls_section(
			'section_style_header',
			[
				'label' => __( 'Header', 'elementor-extras' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_responsive_control(
				'header_text_align',
				[
					'label' 		=> __( 'Align Text', 'elementor-extras' ),
					'type' 			=> Controls_Manager::CHOOSE,
					'default' 		=> '',
					'options' 		=> [
						'left'    		=> [
							'title' 	=> __( 'Left', 'elementor-extras' ),
							'icon' 		=> 'fa fa-align-left',
						],
						'center' 		=> [
							'title' 	=> __( 'Center', 'elementor-extras' ),
							'icon' 		=> 'fa fa-align-center',
						],
						'right' 		=> [
							'title' 	=> __( 'Right', 'elementor-extras' ),
							'icon' 		=> 'fa fa-align-right',
						],
					],
					'selectors'		=> [
						'{{WRAPPER}} .ee-post__header' 	=> 'text-align: {{VALUE}};',
					]
				]
			);

			$this->add_responsive_control(
				'header_padding',
				[
					'label' 		=> __( 'Padding', 'elementor-extras' ),
					'type' 			=> Controls_Manager::DIMENSIONS,
					'size_units' 	=> [ 'px', 'em', '%' ],
					'selectors' 	=> [
						'{{WRAPPER}} .ee-post__header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_control(
				'header_border_radius',
				[
					'type' 			=> Controls_Manager::DIMENSIONS,
					'label' 		=> __( 'Border Radius', 'elementor-extras' ),
					'size_units' 	=> [ 'px', '%' ],
					'selectors' 	=> [
						'{{WRAPPER}} .ee-post__header' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->start_controls_tabs( 'header_tabs_hover' );

			$this->start_controls_tab( 'header_tab_default', [ 'label' => __( 'Default', 'elementor-extras' ) ] );

				$this->add_control(
					'header_background_color',
					[
						'label' 	=> __( 'Background Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ee-post__header' => 'background-color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					'header_color',
					[
						'label' 	=> __( 'Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ee-post__header' => 'color: {{VALUE}};',
						],
					]
				);

			$this->end_controls_tab();

			$this->start_controls_tab( 'header_tab_hover', [ 'label' => __( 'Hover', 'elementor-extras' ) ] );

				$this->add_control(
					'header_background_color_hover',
					[
						'label' 	=> __( 'Background Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ee-post:hover .ee-post__header' => 'background-color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					'header_color_hover',
					[
						'label' 	=> __( 'Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ee-post:hover .ee-post__header' => 'color: {{VALUE}};',
						],
					]
				);

			$this->end_controls_tab();

			$this->end_controls_tabs();

			$this->add_control(
				'header_separator_heading',
				[
					'separator' => 'before',
					'label' 	=> __( 'Separator', 'elementor-extras' ),
					'type' 		=> Controls_Manager::HEADING,
					'condition' => [
						'post_media' => ''
					]
				]
			);

			$this->add_control(
				'header_separator_color',
				[
					'label' 	=> __( 'Color', 'elementor-extras' ),
					'type' 		=> Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ee-post__header' => 'border-color: {{VALUE}};',
					],
				]
			);

			$this->add_responsive_control(
				'header_separator_size',
				[
					'label' 		=> __( 'Separator Size', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SLIDER,
					'range' 		=> [
						'px' 		=> [
							'min' => 0,
							'max' => 10,
						],
					],
					'selectors' 	=> [
						'{{WRAPPER}} .ee-post__header' => 'border-bottom-width: {{SIZE}}px',
					],
					'condition' => [
						'post_media' => ''
					]
				]
			);

			$this->add_control(
				'header_metas',
				[
					'separator' => 'before',
					'label' 	=> __( '↳ Header Metas', 'elementor-extras' ),
					'type' 		=> Controls_Manager::HEADING,
					// 'conditions'=> [
					// 	'relation' => 'or',
					// 	'terms' => [
					// 		[
					// 			'name' => 'post_author_position',
					// 			'operator' => '==',
					// 			'value' => 'header',
					// 		],
					// 		[
					// 			'name' => 'post_date_position',
					// 			'operator' => '==',
					// 			'value' => 'header',
					// 		],
					// 		[
					// 			'name' => 'post_comments_position',
					// 			'operator' => '==',
					// 			'value' => 'header',
					// 		]
					// 	]
					// ]
				]
			);

			$this->add_control(
				'header_metas_description',
				[
					'type' 				=> Controls_Manager::RAW_HTML,
					'raw' 				=> __( 'Use these to style metas that appear only in the Header area', 'elementor-extras' ),
					'content_classes' 	=> 'ee-raw-html',
				]
			);

			$this->add_control(
				'header_metas_spacing',
				[
					'label' 		=> __( 'Spacing', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SLIDER,
					'range' 		=> [
						'px' 		=> [
							'min' => 0,
							'max' => 48,
						],
					],
					'selectors' 	=> [
						'{{WRAPPER}} .ee-post__header .ee-post__metas__list' => 'margin-left: -{{SIZE}}px',
						'{{WRAPPER}} .ee-post__header .ee-post__meta,
						 {{WRAPPER}} .ee-post__header .ee-post__meta__separator' => 'margin-left: {{SIZE}}px',
					],
				]
			);

			$this->add_control(
				'header_metas_distance',
				[
					'label' 		=> __( 'Distance', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SLIDER,
					'range' 		=> [
						'px' 		=> [
							'min' => 0,
							'max' => 48,
						],
					],
					'selectors' 	=> [
						'{{WRAPPER}} .ee-post__header .ee-post__metas' => 'margin-bottom: {{SIZE}}px',
					],
				]
			);

			$this->add_responsive_control(
				'header_metas_text_align',
				[
					'label' 		=> __( 'Align Text', 'elementor-extras' ),
					'type' 			=> Controls_Manager::CHOOSE,
					'default' 		=> '',
					'options' 		=> [
						'left'    		=> [
							'title' 	=> __( 'Left', 'elementor-extras' ),
							'icon' 		=> 'fa fa-align-left',
						],
						'center' 		=> [
							'title' 	=> __( 'Center', 'elementor-extras' ),
							'icon' 		=> 'fa fa-align-center',
						],
						'right' 		=> [
							'title' 	=> __( 'Right', 'elementor-extras' ),
							'icon' 		=> 'fa fa-align-right',
						],
					],
					'selectors'		=> [
						'{{WRAPPER}} .ee-post__header .ee-post__metas--has-metas' => 'text-align: {{VALUE}};',
					],
					// 'conditions'=> [
					// 	'relation' => 'or',
					// 	'terms' => [
					// 		[
					// 			'name' => 'post_author_position',
					// 			'operator' => '==',
					// 			'value' => 'header',
					// 		],
					// 		[
					// 			'name' => 'post_date_position',
					// 			'operator' => '==',
					// 			'value' => 'header',
					// 		],
					// 		[
					// 			'name' => 'post_comments_position',
					// 			'operator' => '==',
					// 			'value' => 'header',
					// 		]
					// 	]
					// ]
				]
			);

			$this->add_responsive_control(
				'header_metas_padding',
				[
					'label' 		=> __( 'Padding', 'elementor-extras' ),
					'type' 			=> Controls_Manager::DIMENSIONS,
					'size_units' 	=> [ 'px', 'em', '%' ],
					'selectors' 	=> [
						'{{WRAPPER}} .ee-post__header .ee-post__metas--has-metas' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_control(
				'header_metas_color',
				[
					'label' 	=> __( 'Color', 'elementor-extras' ),
					'type' 		=> Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ee-post__header .ee-post__metas--has-metas' => 'color: {{VALUE}};',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' 		=> 'header_metas_typography',
					'label' 	=> __( 'Typography', 'elementor-extras' ),
					'scheme' 	=> Scheme_Typography::TYPOGRAPHY_3,
					'selector' 	=> '{{WRAPPER}} .ee-post__header .ee-post__metas--has-metas .ee-post__meta',
				]
			);

		$this->end_controls_section();

	}

	public function register_media_style_controls() {

		$this->start_controls_section(
			'section_style_media',
			[
				'label' => __( 'Media', 'elementor-extras' ),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => [
					'post_media!' => ''
				]
			]
		);

			$this->add_responsive_control(
				'media_margin',
				[
					'label' 		=> __( 'Margin', 'elementor-extras' ),
					'type' 			=> Controls_Manager::DIMENSIONS,
					'size_units' 	=> [ 'px', 'em', '%' ],
					'selectors' 	=> [
						'{{WRAPPER}} .ee-post__media' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'condition' => [
						'post_media!' => ''
					]
				]
			);

			$this->add_control(
				'media_border_radius',
				[
					'type' 			=> Controls_Manager::DIMENSIONS,
					'label' 		=> __( 'Border Radius', 'elementor-extras' ),
					'size_units' 	=> [ 'px', '%' ],
					'selectors' 	=> [
						'{{WRAPPER}} .ee-post__media' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_control(
				'media_z_index',
				[
					'label' 		=> __( 'Z-Index', 'elementor-extras' ),
					'type' 			=> Controls_Manager::NUMBER,
					'default' 		=> 1,
					'selectors'		=> [
						'{{WRAPPER}} .ee-post__media' => 'z-index: {{VALUE}};'
					]
				]
			);

			$this->add_control(
				'media_content_style_heading',
				[
					'label' 	=> __( 'Content', 'elementor-extras' ),
					'type' 		=> Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);

			$this->add_control(
				'media_content_vertical_aligment',
				[
					'label' 		=> __( 'Vertical Align', 'elementor-extras' ),
					'label_block' 	=> false,
					'type' 			=> Controls_Manager::CHOOSE,
					'options' 		=> [
						'top' 	=> [
							'title' 	=> __( 'Initial', 'elementor-extras' ),
							'icon' 		=> 'eicon-v-align-top',
						],
						'middle' => [
							'title' => __( 'Center', 'elementor-extras' ),
							'icon' => 'eicon-v-align-middle',
						],
						'bottom' 		=> [
							'title' 	=> __( 'Opposite', 'elementor-extras' ),
							'icon' 		=> 'eicon-v-align-bottom',
						],
						'stretch' => [
							'title' 	=> __( 'Stretch', 'elementor-extras' ),
							'icon' 		=> 'eicon-v-align-stretch',
						],
					],
					'default' 		=> 'top',
					'prefix_class' 	=> 'ee-posts-align-',
				]
			);

			$this->add_responsive_control(
				'media_content_text_align',
				[
					'label' 		=> __( 'Align Text', 'elementor-extras' ),
					'type' 			=> Controls_Manager::CHOOSE,
					'default' 		=> '',
					'options' 		=> [
						'left'    		=> [
							'title' 	=> __( 'Left', 'elementor-extras' ),
							'icon' 		=> 'fa fa-align-left',
						],
						'center' 		=> [
							'title' 	=> __( 'Center', 'elementor-extras' ),
							'icon' 		=> 'fa fa-align-center',
						],
						'right' 		=> [
							'title' 	=> __( 'Right', 'elementor-extras' ),
							'icon' 		=> 'fa fa-align-right',
						],
					],
					'selectors'		=> [
						'{{WRAPPER}} .ee-post__media__content' 	=> 'text-align: {{VALUE}};',
					]
				]
			);

			$this->add_responsive_control(
				'media_content_padding',
				[
					'label' 		=> __( 'Padding', 'elementor-extras' ),
					'type' 			=> Controls_Manager::DIMENSIONS,
					'size_units' 	=> [ 'px', 'em', '%' ],
					'selectors' 	=> [
						'{{WRAPPER}} .ee-post__media__content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_control(
				'media_content_effect',
				[
					'label' 	=> __( 'Effect', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SELECT,
					'default' 	=> '',
					'options' => [
						''					=> __( 'None', 'elementor-extras' ),
						'fade-in'			=> __( 'Fade In', 'elementor-extras' ),
						'fade-out'			=> __( 'Fade Out', 'elementor-extras' ),
						'from-top'			=> __( 'From Top', 'elementor-extras' ),
						'from-right'		=> __( 'From Right', 'elementor-extras' ),
						'from-bottom'		=> __( 'From Bottom', 'elementor-extras' ),
						'from-left'			=> __( 'From Left', 'elementor-extras' ),
						'fade-from-top'		=> __( 'Fade From Top', 'elementor-extras' ),
						'fade-from-right'	=> __( 'Fade From Right', 'elementor-extras' ),
						'fade-from-bottom'	=> __( 'Fade From Bottom', 'elementor-extras' ),
						'fade-from-left'	=> __( 'Fade From Left', 'elementor-extras' ),
						'to-top'			=> __( 'To Top', 'elementor-extras' ),
						'to-right'			=> __( 'To Right', 'elementor-extras' ),
						'to-bottom'			=> __( 'To Bottom', 'elementor-extras' ),
						'to-left'			=> __( 'To Left', 'elementor-extras' ),
						'fade-to-top'		=> __( 'Fade To Top', 'elementor-extras' ),
						'fade-to-right'		=> __( 'Fade To Right', 'elementor-extras' ),
						'fade-to-bottom'	=> __( 'Fade To Bottom', 'elementor-extras' ),
						'fade-to-left'		=> __( 'Fade To Left', 'elementor-extras' ),
					],
					'prefix_class'	=> 'ee-posts-effect__content--',
				]
			);

			$this->add_control(
				'media_content_effect_easing',
				[
					'label'			=> _x( 'Easing', 'Effect Control', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SELECT,
					'default' 		=> 'ease-in-out',
					'options'		=> Group_Control_Button_Effects::get_easings(),
					'selectors' 	=> [
						 '{{WRAPPER}} .ee-post__media,
						  {{WRAPPER}} .ee-post__media__content,
						  {{WRAPPER}} .ee-post__media__content > *,
						  {{WRAPPER}} .ee-post__media__overlay,
						  {{WRAPPER}} .ee-post__media__thumbnail' => 'transition-timing-function: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				'media_content_effect_duration',
				[
					'label'			=> __( 'Duration', 'elementor-extras' ),
					'type' 			=> Controls_Manager::NUMBER,
					'default' 		=> 0.2,
					'min' 			=> 0.05,
					'max' 			=> 2,
					'step' 			=> 0.05,
					'condition' 	=> [
						'media_content_effect!' => ''
					],
					'label_block' 	=> false,
					'selectors' 	=> [
						 '{{WRAPPER}} .ee-post__media,
						  {{WRAPPER}} .ee-post__media__content,
						  {{WRAPPER}} .ee-post__media__content > *,
						  {{WRAPPER}} .ee-post__media__overlay,
						  {{WRAPPER}} .ee-post__media__thumbnail' => 'transition-duration: {{VALUE}}s;',
					],
				]
			);

			// $this->add_control(
			// 	'media_thumbnail_style_heading',
			// 	[
			// 		'label' 	=> __( 'Thumbnail', 'elementor-extras' ),
			// 		'type' 		=> Controls_Manager::HEADING,
			// 		'separator' => 'before',
			// 	]
			// );

			// $this->add_control(
			// 	'media_thumbnail_effect',
			// 	[
			// 		'separator'	=> 'after',
			// 		'label' 	=> __( 'Effect', 'elementor-extras' ),
			// 		'type' 		=> Controls_Manager::SELECT,
			// 		'default' 	=> '',
			// 		'options' => [
			// 			''					=> __( 'None', 'elementor-extras' ),
			// 			'rotate-to-left'	=> __( 'Rotate To Left', 'elementor-extras' ),
			// 			'rotate-to-right'	=> __( 'Rotate To Right', 'elementor-extras' ),
			// 			'rotate-from-left'	=> __( 'Rotate From Left', 'elementor-extras' ),
			// 			'rotate-from-right'	=> __( 'Rotate From Right', 'elementor-extras' ),
			// 		],
			// 		'prefix_class'	=> 'ee-posts-effect__thumbnail--',
			// 	]
			// );

			$this->start_controls_tabs( 'media_tabs_hover' );

			$this->start_controls_tab( 'media_tab_default', [ 'label' => __( 'Default', 'elementor-extras' ) ] );

				$this->add_control(
					'media_area_heading',
					[
						'label' 	=> __( 'Area', 'elementor-extras' ),
						'type' 		=> Controls_Manager::HEADING,
					]
				);

				$this->add_responsive_control(
					'media_area_scale',
					[
						'label' 		=> __( 'Scale', 'elementor-extras' ),
						'type' 			=> Controls_Manager::SLIDER,
						'range' 		=> [
							'px' 		=> [
								'min' => 0.7,
								'max' => 1.3,
								'step'=> 0.01,
							],
						],
						'selectors' 	=> [
							'{{WRAPPER}} .ee-post__media' => 'transform: scale({{SIZE}});',
						],
					]
				);

				$this->add_control(
					'media_area_color',
					[
						'label' 	=> __( 'Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ee-post__media__content *' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_group_control(
					Group_Control_Box_Shadow::get_type(),
					[
						'name' 		=> 'media_area_box_shadow',
						'selector' 	=> '{{WRAPPER}} .ee-post__media',
						'separator'	=> '',
					]
				);

				$this->add_control(
					'media_thumbnail_heading',
					[
						'label' 	=> __( 'Thumbnail', 'elementor-extras' ),
						'type' 		=> Controls_Manager::HEADING,
					]
				);

				$this->add_responsive_control(
					'media_thumbnail_scale',
					[
						'label' 		=> __( 'Scale', 'elementor-extras' ),
						'type' 			=> Controls_Manager::SLIDER,
						'range' 		=> [
							'px' 		=> [
								'min' => 1,
								'max' => 1.3,
								'step'=> 0.01,
							],
						],
						'selectors' 	=> [
							'{{WRAPPER}} .ee-post__media__thumbnail' => 'transform: scale({{SIZE}});',
						],
					]
				);

				$this->add_control(
					'media_overlay_heading',
					[
						'label' 	=> __( 'Overlay', 'elementor-extras' ),
						'type' 		=> Controls_Manager::HEADING,
					]
				);

				$this->add_group_control(
					Group_Control_Background::get_type(),
					[
						'name' 		=> 'media_overlay_background_color',
						'types' 	=> [ 'classic', 'gradient' ],
						'selector' 	=> '{{WRAPPER}} .ee-post__media__overlay',
						'default'	=> 'classic',
						'exclude'	=> [
							'image',
						]
					]
				);

				$this->add_responsive_control(
					'media_overlay_opacity',
					[
						'label' 		=> __( 'Opacity', 'elementor-extras' ),
						'type' 			=> Controls_Manager::SLIDER,
						'range' 		=> [
							'px' 		=> [
								'min' => 0,
								'max' => 1,
								'step'=> 0.1,
							],
						],
						'selectors' 	=> [
							'{{WRAPPER}} .ee-post__media__overlay' => 'opacity: {{SIZE}};',
						],
					]
				);

			$this->end_controls_tab();

			$this->start_controls_tab( 'media_tab_hover', [ 'label' => __( 'Hover', 'elementor-extras' ) ] );

				$this->add_control(
					'media_area_heading_hover',
					[
						'label' 	=> __( 'Area', 'elementor-extras' ),
						'type' 		=> Controls_Manager::HEADING,
					]
				);

				$this->add_responsive_control(
					'media_area_scale_hover',
					[
						'label' 		=> __( 'Scale', 'elementor-extras' ),
						'type' 			=> Controls_Manager::SLIDER,
						'range' 		=> [
							'px' 		=> [
								'min' => 0.7,
								'max' => 1.3,
								'step'=> 0.01,
							],
						],
						'selectors' 	=> [
							'{{WRAPPER}} .ee-post__media:hover' => 'transform: scale({{SIZE}});',
						],
					]
				);

				$this->add_control(
					'media_area_color_hover',
					[
						'label' 	=> __( 'Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ee-post__media:hover .ee-post__media__content *' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_group_control(
					Group_Control_Box_Shadow::get_type(),
					[
						'name' 		=> 'media_area_box_shadow_hover',
						'selector' 	=> '{{WRAPPER}} .ee-post__media:hover',
						'separator'	=> '',
					]
				);

				$this->add_control(
					'media_thumbnail_heading_hover',
					[
						'label' 	=> __( 'Thumbnail', 'elementor-extras' ),
						'type' 		=> Controls_Manager::HEADING,
					]
				);

				$this->add_responsive_control(
					'media_thumbnail_scale_hover',
					[
						'label' 		=> __( 'Scale', 'elementor-extras' ),
						'type' 			=> Controls_Manager::SLIDER,
						'range' 		=> [
							'px' 		=> [
								'min' => 1,
								'max' => 1.3,
								'step'=> 0.01,
							],
						],
						'selectors' 	=> [
							'{{WRAPPER}} .ee-post__media:hover .ee-post__media__thumbnail' => 'transform: scale({{SIZE}});',
						],
					]
				);

				$this->add_control(
					'media_overlay_heading_hover',
					[
						'label' 	=> __( 'Overlay', 'elementor-extras' ),
						'type' 		=> Controls_Manager::HEADING,
					]
				);

				$this->add_group_control(
					Group_Control_Background::get_type(),
					[
						'name' 		=> 'media_overlay_background_color_hover',
						'types' 	=> [ 'classic', 'gradient' ],
						'selector' 	=> '{{WRAPPER}} .ee-post__media:hover .ee-post__media__overlay',
						'default'	=> 'classic',
						'exclude'	=> [
							'image',
						]
					]
				);

				$this->add_responsive_control(
					'media_overlay_opacity_hover',
					[
						'label' 		=> __( 'Opacity', 'elementor-extras' ),
						'type' 			=> Controls_Manager::SLIDER,
						'range' 		=> [
							'px' 		=> [
								'min' => 0,
								'max' => 1,
								'step'=> 0.1,
							],
						],
						'selectors' 	=> [
							'{{WRAPPER}} .ee-post__media:hover .ee-post__media__overlay' => 'opacity: {{SIZE}};',
						],
					]
				);

			$this->end_controls_tab();

			$this->end_controls_tabs();

			$this->add_control(
				'media_metas',
				[
					'separator' => 'before',
					'label' 	=> __( '↳ Media Metas', 'elementor-extras' ),
					'type' 		=> Controls_Manager::HEADING,
				]
			);

			$this->add_control(
				'media_metas_description',
				[
					'type' 				=> Controls_Manager::RAW_HTML,
					'raw' 				=> __( 'Use these to style metas that appear only in the Media area', 'elementor-extras' ),
					'content_classes' 	=> 'ee-raw-html',
				]
			);

			$this->add_control(
				'media_metas_spacing',
				[
					'label' 		=> __( 'Spacing', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SLIDER,
					'range' 		=> [
						'px' 		=> [
							'min' => 0,
							'max' => 48,
						],
					],
					'selectors' 	=> [
						'{{WRAPPER}} .ee-post__media .ee-post__metas__list' => 'margin-left: -{{SIZE}}px',
						'{{WRAPPER}} .ee-post__media .ee-post__meta,
						 {{WRAPPER}} .ee-post__media .ee-post__meta__separator' => 'margin-left: {{SIZE}}px',
					],
				]
			);

			$this->add_control(
				'media_metas_distance',
				[
					'label' 		=> __( 'Distance', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SLIDER,
					'range' 		=> [
						'px' 		=> [
							'min' => 0,
							'max' => 48,
						],
					],
					'selectors' 	=> [
						'{{WRAPPER}} .ee-post__media .ee-post__metas' => 'margin-bottom: {{SIZE}}px',
					],
				]
			);

			$this->add_responsive_control(
				'media_metas_text_align',
				[
					'label' 		=> __( 'Align Text', 'elementor-extras' ),
					'type' 			=> Controls_Manager::CHOOSE,
					'default' 		=> '',
					'options' 		=> [
						'left'    		=> [
							'title' 	=> __( 'Left', 'elementor-extras' ),
							'icon' 		=> 'fa fa-align-left',
						],
						'center' 		=> [
							'title' 	=> __( 'Center', 'elementor-extras' ),
							'icon' 		=> 'fa fa-align-center',
						],
						'right' 		=> [
							'title' 	=> __( 'Right', 'elementor-extras' ),
							'icon' 		=> 'fa fa-align-right',
						],
					],
					'selectors'		=> [
						'{{WRAPPER}} .ee-post__media .ee-post__metas--has-metas' => 'text-align: {{VALUE}};',
					]
				]
			);

			$this->add_responsive_control(
				'media_metas_padding',
				[
					'label' 		=> __( 'Padding', 'elementor-extras' ),
					'type' 			=> Controls_Manager::DIMENSIONS,
					'size_units' 	=> [ 'px', 'em', '%' ],
					'selectors' 	=> [
						'{{WRAPPER}} .ee-post__media .ee-post__metas--has-metas' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_control(
				'media_metas_color',
				[
					'label' 	=> __( 'Color', 'elementor-extras' ),
					'type' 		=> Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ee-post__media .ee-post__metas--has-metas' => 'color: {{VALUE}};',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' 		=> 'media_metas_typography',
					'label' 	=> __( 'Typography', 'elementor-extras' ),
					'scheme' 	=> Scheme_Typography::TYPOGRAPHY_3,
					'selector' 	=> '{{WRAPPER}} .ee-post__media .ee-post__metas--has-metas .ee-post__meta',
				]
			);

		$this->end_controls_section();

	}

	public function register_body_style_controls() {

		$this->start_controls_section(
			'section_style_body',
			[
				'label' => __( 'Body', 'elementor-extras' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_responsive_control(
				'body_text_align',
				[
					'label' 		=> __( 'Align Text', 'elementor-extras' ),
					'type' 			=> Controls_Manager::CHOOSE,
					'default' 		=> '',
					'options' 		=> [
						'left'    		=> [
							'title' 	=> __( 'Left', 'elementor-extras' ),
							'icon' 		=> 'fa fa-align-left',
						],
						'center' 		=> [
							'title' 	=> __( 'Center', 'elementor-extras' ),
							'icon' 		=> 'fa fa-align-center',
						],
						'right' 		=> [
							'title' 	=> __( 'Right', 'elementor-extras' ),
							'icon' 		=> 'fa fa-align-right',
						],
					],
					'selectors'		=> [
						'{{WRAPPER}} .ee-post__body' 	=> 'text-align: {{VALUE}};',
					],
				]
			);

			$this->add_responsive_control(
				'body_padding',
				[
					'label' 		=> __( 'Padding', 'elementor-extras' ),
					'type' 			=> Controls_Manager::DIMENSIONS,
					'size_units' 	=> [ 'px', 'em', '%' ],
					'selectors' 	=> [
						'{{WRAPPER}} .ee-post__body' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_responsive_control(
				'body_margin',
				[
					'label' 		=> __( 'Margin', 'elementor-extras' ),
					'type' 			=> Controls_Manager::DIMENSIONS,
					'size_units' 	=> [ 'px', 'em', '%' ],
					'selectors' 	=> [
						'{{WRAPPER}} .ee-post__body' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_control(
				'body_border_radius',
				[
					'type' 			=> Controls_Manager::DIMENSIONS,
					'label' 		=> __( 'Border Radius', 'elementor-extras' ),
					'size_units' 	=> [ 'px', '%' ],
					'selectors' 	=> [
						'{{WRAPPER}} .ee-post__body' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->start_controls_tabs( 'body_tabs_hover' );

			$this->start_controls_tab( 'body_tab_default', [ 'label' => __( 'Default', 'elementor-extras' ) ] );

				$this->add_control(
					'body_background_color',
					[
						'label' 	=> __( 'Background Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ee-post__body' => 'background-color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					'body_color',
					[
						'label' 	=> __( 'Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ee-post__body' => 'color: {{VALUE}};',
						],
					]
				);

			$this->end_controls_tab();

			$this->start_controls_tab( 'body_tab_hover', [ 'label' => __( 'Hover', 'elementor-extras' ) ] );

				$this->add_control(
					'body_background_color_hover',
					[
						'label' 	=> __( 'Background Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ee-post:hover .ee-post__body' => 'background-color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					'body_color_hover',
					[
						'label' 	=> __( 'Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ee-post:hover .ee-post__body' => 'color: {{VALUE}};',
						],
					]
				);

			$this->end_controls_tab();

			$this->end_controls_tabs();

			$this->add_control(
				'body_metas',
				[
					'separator' => 'before',
					'label' 	=> __( '↳ Body Metas', 'elementor-extras' ),
					'type' 		=> Controls_Manager::HEADING,
				]
			);

			$this->add_control(
				'body_metas_description',
				[
					'type' 				=> Controls_Manager::RAW_HTML,
					'raw' 				=> __( 'Use these to style metas that appear only in the Body area', 'elementor-extras' ),
					'content_classes' 	=> 'ee-raw-html',
				]
			);

			$this->add_control(
				'body_metas_spacing',
				[
					'label' 		=> __( 'Spacing', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SLIDER,
					'range' 		=> [
						'px' 		=> [
							'min' => 0,
							'max' => 48,
						],
					],
					'selectors' 	=> [
						'{{WRAPPER}} .ee-post__body .ee-post__metas__list' => 'margin-left: -{{SIZE}}px',
						'{{WRAPPER}} .ee-post__body .ee-post__meta,
						 {{WRAPPER}} .ee-post__body .ee-post__meta__separator' => 'margin-left: {{SIZE}}px',
					],
				]
			);

			$this->add_control(
				'body_metas_distance',
				[
					'label' 		=> __( 'Distance', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SLIDER,
					'range' 		=> [
						'px' 		=> [
							'min' => 0,
							'max' => 48,
						],
					],
					'selectors' 	=> [
						'{{WRAPPER}} .ee-post__body .ee-post__metas' => 'margin-bottom: {{SIZE}}px',
					],
				]
			);

			$this->add_responsive_control(
				'body_metas_text_align',
				[
					'label' 		=> __( 'Align Text', 'elementor-extras' ),
					'type' 			=> Controls_Manager::CHOOSE,
					'default' 		=> '',
					'options' 		=> [
						'left'    		=> [
							'title' 	=> __( 'Left', 'elementor-extras' ),
							'icon' 		=> 'fa fa-align-left',
						],
						'center' 		=> [
							'title' 	=> __( 'Center', 'elementor-extras' ),
							'icon' 		=> 'fa fa-align-center',
						],
						'right' 		=> [
							'title' 	=> __( 'Right', 'elementor-extras' ),
							'icon' 		=> 'fa fa-align-right',
						],
					],
					'selectors'		=> [
						'{{WRAPPER}} .ee-post__body .ee-post__metas--has-metas' => 'text-align: {{VALUE}};',
					]
				]
			);

			$this->add_responsive_control(
				'body_metas_padding',
				[
					'label' 		=> __( 'Padding', 'elementor-extras' ),
					'type' 			=> Controls_Manager::DIMENSIONS,
					'size_units' 	=> [ 'px', 'em', '%' ],
					'selectors' 	=> [
						'{{WRAPPER}} .ee-post__body .ee-post__metas--has-metas' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_control(
				'body_metas_color',
				[
					'label' 	=> __( 'Color', 'elementor-extras' ),
					'type' 		=> Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ee-post__body .ee-post__metas--has-metas' => 'color: {{VALUE}};',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' 		=> 'body_metas_typography',
					'label' 	=> __( 'Typography', 'elementor-extras' ),
					'scheme' 	=> Scheme_Typography::TYPOGRAPHY_3,
					'selector' 	=> '{{WRAPPER}} .ee-post__body .ee-post__metas--has-metas .ee-post__meta',
				]
			);

		$this->end_controls_section();

	}

	public function register_footer_style_controls() {

		$this->start_controls_section(
			'section_style_footer',
			[
				'label' => __( 'Footer', 'elementor-extras' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_responsive_control(
				'footer_text_align',
				[
					'label' 		=> __( 'Align Text', 'elementor-extras' ),
					'type' 			=> Controls_Manager::CHOOSE,
					'default' 		=> '',
					'options' 		=> [
						'left'    		=> [
							'title' 	=> __( 'Left', 'elementor-extras' ),
							'icon' 		=> 'fa fa-align-left',
						],
						'center' 		=> [
							'title' 	=> __( 'Center', 'elementor-extras' ),
							'icon' 		=> 'fa fa-align-center',
						],
						'right' 		=> [
							'title' 	=> __( 'Right', 'elementor-extras' ),
							'icon' 		=> 'fa fa-align-right',
						],
					],
					'selectors'		=> [
						'{{WRAPPER}} .ee-post__footer' 	=> 'text-align: {{VALUE}};',
					]
				]
			);

			$this->add_responsive_control(
				'footer_padding',
				[
					'label' 		=> __( 'Padding', 'elementor-extras' ),
					'type' 			=> Controls_Manager::DIMENSIONS,
					'size_units' 	=> [ 'px', 'em', '%' ],
					'selectors' 	=> [
						'{{WRAPPER}} .ee-post__footer' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_responsive_control(
				'footer_margin',
				[
					'label' 		=> __( 'Margin', 'elementor-extras' ),
					'type' 			=> Controls_Manager::DIMENSIONS,
					'size_units' 	=> [ 'px', 'em', '%' ],
					'selectors' 	=> [
						'{{WRAPPER}} .ee-post__footer' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_control(
				'footer_border_radius',
				[
					'type' 			=> Controls_Manager::DIMENSIONS,
					'label' 		=> __( 'Border Radius', 'elementor-extras' ),
					'size_units' 	=> [ 'px', '%' ],
					'selectors' 	=> [
						'{{WRAPPER}} .ee-post__footer' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->start_controls_tabs( 'footer_tabs_hover' );

			$this->start_controls_tab( 'footer_tab_default', [ 'label' => __( 'Default', 'elementor-extras' ) ] );

				$this->add_control(
					'footer_background_color',
					[
						'label' 	=> __( 'Background Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ee-post__footer' => 'background-color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					'footer_color',
					[
						'label' 	=> __( 'Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ee-post__footer' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					'footer_separator_heading',
					[
						'label' 	=> __( 'Separator', 'elementor-extras' ),
						'type' 		=> Controls_Manager::HEADING,
					]
				);

				$this->add_control(
					'footer_separator_color',
					[
						'label' 	=> __( 'Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ee-post__footer' => 'border-color: {{VALUE}};',
						],
					]
				);

			$this->end_controls_tab();

			$this->start_controls_tab( 'footer_tab_hover', [ 'label' => __( 'Hover', 'elementor-extras' ) ] );

				$this->add_control(
					'footer_background_color_hover',
					[
						'label' 	=> __( 'Background Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ee-post:hover .ee-post__footer' => 'background-color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					'footer_color_hover',
					[
						'label' 	=> __( 'Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ee-post__footer' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					'footer_separator_heading_hover',
					[
						'label' 	=> __( 'Separator', 'elementor-extras' ),
						'type' 		=> Controls_Manager::HEADING,
					]
				);

				$this->add_control(
					'footer_separator_color_hover',
					[
						'label' 	=> __( 'Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ee-post__footer' => 'border-color: {{VALUE}};',
						],
					]
				);

			$this->end_controls_tab();

			$this->end_controls_tabs();

			$this->add_responsive_control(
				'footer_separator_size',
				[
					'separator'		=> 'before',
					'label' 		=> __( 'Separator Size', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SLIDER,
					'range' 		=> [
						'px' 		=> [
							'min' => 0,
							'max' => 10,
						],
					],
					'selectors' 	=> [
						'{{WRAPPER}} .ee-post__footer' => 'border-width: {{SIZE}}px',
					],
				]
			);

			$this->add_control(
				'footer_metas',
				[
					'separator' => 'before',
					'label' 	=> __( '↳ Footer Metas', 'elementor-extras' ),
					'type' 		=> Controls_Manager::HEADING,
				]
			);

			$this->add_control(
				'footer_metas_description',
				[
					'type' 				=> Controls_Manager::RAW_HTML,
					'raw' 				=> __( 'Use these to style metas that appear only in the Footer area', 'elementor-extras' ),
					'content_classes' 	=> 'ee-raw-html',
				]
			);

			$this->add_control(
				'footer_metas_spacing',
				[
					'label' 		=> __( 'Spacing', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SLIDER,
					'range' 		=> [
						'px' 		=> [
							'min' => 0,
							'max' => 48,
						],
					],
					'selectors' 	=> [
						'{{WRAPPER}} .ee-post__footer .ee-post__metas__list' => 'margin-left: -{{SIZE}}px',
						'{{WRAPPER}} .ee-post__footer .ee-post__meta,
						 {{WRAPPER}} .ee-post__footer .ee-post__meta__separator' => 'margin-left: {{SIZE}}px',
					],
				]
			);

			$this->add_control(
				'footer_metas_distance',
				[
					'label' 		=> __( 'Distance', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SLIDER,
					'range' 		=> [
						'px' 		=> [
							'min' => 0,
							'max' => 48,
						],
					],
					'selectors' 	=> [
						'{{WRAPPER}} .ee-post__footer .ee-post__metas' => 'margin-bottom: {{SIZE}}px',
					],
				]
			);

			$this->add_responsive_control(
				'footer_metas_text_align',
				[
					'label' 		=> __( 'Align Text', 'elementor-extras' ),
					'type' 			=> Controls_Manager::CHOOSE,
					'default' 		=> '',
					'options' 		=> [
						'left'    		=> [
							'title' 	=> __( 'Left', 'elementor-extras' ),
							'icon' 		=> 'fa fa-align-left',
						],
						'center' 		=> [
							'title' 	=> __( 'Center', 'elementor-extras' ),
							'icon' 		=> 'fa fa-align-center',
						],
						'right' 		=> [
							'title' 	=> __( 'Right', 'elementor-extras' ),
							'icon' 		=> 'fa fa-align-right',
						],
					],
					'selectors'		=> [
						'{{WRAPPER}} .ee-post__footer .ee-post__metas--has-metas' => 'text-align: {{VALUE}};',
					]
				]
			);

			$this->add_responsive_control(
				'footer_metas_padding',
				[
					'label' 		=> __( 'Padding', 'elementor-extras' ),
					'type' 			=> Controls_Manager::DIMENSIONS,
					'size_units' 	=> [ 'px', 'em', '%' ],
					'selectors' 	=> [
						'{{WRAPPER}} .ee-post__footer .ee-post__metas--has-metas' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_control(
				'footer_metas_color',
				[
					'label' 	=> __( 'Color', 'elementor-extras' ),
					'type' 		=> Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ee-post__footer .ee-post__metas--has-metas' => 'color: {{VALUE}};',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' 		=> 'footer_metas_typography',
					'label' 	=> __( 'Typography', 'elementor-extras' ),
					'scheme' 	=> Scheme_Typography::TYPOGRAPHY_3,
					'selector' 	=> '{{WRAPPER}} .ee-post__footer .ee-post__metas--has-metas .ee-post__meta',
				]
			);

		$this->end_controls_section();

	}

	public function register_metas_style_controls() {

		$this->start_controls_section(
			'section_style_metas',
			[
				'label' => __( 'Metas', 'elementor-extras' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_control(
				'metas_description',
				[
					'type' 				=> Controls_Manager::RAW_HTML,
					'raw' 				=> __( 'The effects of the controls below can be overriden at an area level by using the options inside each separate area.', 'elementor-extras' ),
					'content_classes' 	=> 'ee-raw-html',
				]
			);

			$this->add_control(
				'metas_spacing',
				[
					'label' 		=> __( 'Spacing', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SLIDER,
					'range' 		=> [
						'px' 		=> [
							'min' => 0,
							'max' => 48,
						],
					],
					'selectors' 	=> [
						'{{WRAPPER}} .ee-post__metas__list' => 'margin-left: -{{SIZE}}px',
						'{{WRAPPER}} .ee-post__meta,
						 {{WRAPPER}} .ee-post__meta__separator' => 'margin-left: {{SIZE}}px',
					],
				]
			);

			// $this->add_control(
			// 	'metas_display',
			// 	[
			// 		'label' 		=> __( 'Display', 'elementor-extras' ),
			// 		'type' 			=> Controls_Manager::CHOOSE,
			// 		'default' 		=> 'inline',
			// 		'options' 		=> [
			// 			'inline' 	=> [
			// 				'title' => __( 'Inline', 'elementor-extras' ),
			// 				'icon' 	=> 'nicon nicon-inline',
			// 			],
			// 			'block' 	=> [
			// 				'title' => __( 'Block', 'elementor-extras' ),
			// 				'icon' 	=> 'nicon nicon-inline',
			// 			],
			// 		],
			// 		'prefix_class'	=> 'ee-posts-metas-display-',
			// 		'label_block'	=> false,
			// 	]
			// );

			$this->add_control(
				'author_name_heading',
				[
					'separator' => 'before',
					'label' 	=> __( 'Name', 'elementor-extras' ),
					'type' 		=> Controls_Manager::HEADING,
				]
			);

			$this->add_control(
				'author_name_color',
				[
					'label' 	=> __( 'Color', 'elementor-extras' ),
					'type' 		=> Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ee-post__meta--author' => 'color: {{VALUE}};',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' 		=> 'author_name_typography',
					'label' 	=> __( 'Typography', 'elementor-extras' ),
					'scheme' 	=> Scheme_Typography::TYPOGRAPHY_3,
					'selector' 	=> '{{WRAPPER}} .ee-post__meta--date',
					'exclude'	=> [
						'font_family',
						'font_size',
						'line_height',
						'letter_spacing',
					],
				]
			);

			$this->add_control(
				'author_avatar_heading',
				[
					'separator' => 'before',
					'label' 	=> __( 'Avatar', 'elementor-extras' ),
					'type' 		=> Controls_Manager::HEADING,
				]
			);

			$this->add_control(
				'author_avatar_display',
				[
					'label' 		=> __( 'Display', 'elementor-extras' ),
					'type' 			=> Controls_Manager::CHOOSE,
					'default' 		=> 'left',
					'options' 		=> [
						'left' 		=> [
							'title' => __( 'Left', 'elementor-extras' ),
							'icon' 	=> 'eicon-h-align-left',
						],
						'top' 	=> [
							'title' => __( 'Top', 'elementor-extras' ),
							'icon' 	=> 'eicon-v-align-top',
						],
						'right' 	=> [
							'title' => __( 'Right', 'elementor-extras' ),
							'icon' 	=> 'eicon-h-align-right',
						],
					],
					'prefix_class'	=> 'ee-posts-avatar-position-',
					'label_block'	=> false,
				]
			);

			$this->add_control(
				'author_avatar_vertical_align',
				[
					'label' 		=> __( 'Align', 'elementor-extras' ),
					'type' 			=> Controls_Manager::CHOOSE,
					'default' 		=> 'center',
					'options' 		=> [
						'flex-start'=> [
							'title' => __( 'Top', 'elementor-extras' ),
							'icon' 	=> 'eicon-v-align-top',
						],
						'center' 	=> [
							'title' => __( 'Center', 'elementor-extras' ),
							'icon' 	=> 'eicon-v-align-middle',
						],
						'flex-end' 	=> [
							'title' => __( 'Bottom', 'elementor-extras' ),
							'icon' 	=> 'eicon-v-align-bottom',
						],
					],
					'selectors'		=> [
						'{{WRAPPER}} .ee-post__metas' => 'align-items: {{VALUE}};',
					],
					'label_block'	=> false,
					'condition' => [
						'author_avatar_display!' => 'top',
					]
				]
			);

			$this->add_responsive_control(
				'author_avatar_size',
				[
					'label' 		=> __( 'Size', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SLIDER,
					'range' 		=> [
						'px' 		=> [
							'min' => 12,
							'max' => 100,
						],
					],
					'selectors' 	=> [
						'{{WRAPPER}} .ee-post__metas__avatar' => 'max-width: {{SIZE}}px !important;',
					],
				]
			);

			$this->add_control(
				'author_avatar_spacing',
				[
					'label' 		=> __( 'Spacing', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SLIDER,
					'range' 		=> [
						'px' 		=> [
							'min' => 0,
							'max' => 48,
						],
					],
					'selectors' 	=> [
						'{{WRAPPER}}.ee-posts-avatar-position-top .ee-post__metas--has-metas .ee-post__metas__avatar' => 'margin: 0 0 {{SIZE}}px 0',
						'{{WRAPPER}}.ee-posts-avatar-position-right .ee-post__metas--has-metas .ee-post__metas__avatar' => 'margin: 0 0 0 {{SIZE}}px',
						'{{WRAPPER}} .ee-post__metas--has-metas .ee-post__metas__avatar' => 'margin: 0 {{SIZE}}px 0 0',
					],
				]
			);

			$this->add_control(
				'author_avatar_border_radius',
				[
					'label' 		=> __( 'Border Radius', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SLIDER,
					'range' 		=> [
						'%' 		=> [
							'min' => 0,
							'max' => 100,
							'step'=> 1,
						],
						'px' 		=> [
							'min' => 0,
							'max' => 100,
							'step'=> 1,
						],
					],
					'size_units' 	=> [ '%', 'px' ],
					'selectors' 	=> [
						'{{WRAPPER}} .ee-post__metas__avatar' => 'border-radius: {{SIZE}}{{UNIT}}',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name' 		=> 'author_avatar_box_shadow',
					'selector' 	=> '{{WRAPPER}} .ee-post__metas__avatar',
					'separator'	=> '',
				]
			);

			$this->add_control(
				'date_heading',
				[
					'separator' => 'before',
					'label' 	=> __( 'Date', 'elementor-extras' ),
					'type' 		=> Controls_Manager::HEADING,
				]
			);

			$this->add_control(
				'date_color',
				[
					'label' 	=> __( 'Color', 'elementor-extras' ),
					'type' 		=> Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ee-post__meta--date' => 'color: {{VALUE}};',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' 		=> 'date_typography',
					'label' 	=> __( 'Typography', 'elementor-extras' ),
					'scheme' 	=> Scheme_Typography::TYPOGRAPHY_3,
					'selector' 	=> '{{WRAPPER}} .ee-post__meta--date',
					'exclude'	=> [
						'font_family',
						'font_size',
						'line_height',
						'letter_spacing',
					],
				]
			);

			$this->add_control(
				'comments_heading',
				[
					'separator' => 'before',
					'label' 	=> __( 'Comments', 'elementor-extras' ),
					'type' 		=> Controls_Manager::HEADING,
				]
			);

			$this->add_control(
				'comments_color',
				[
					'label' 	=> __( 'Color', 'elementor-extras' ),
					'type' 		=> Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ee-post__meta--comments' => 'color: {{VALUE}};',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' 		=> 'comments_typography',
					'label' 	=> __( 'Typography', 'elementor-extras' ),
					'scheme' 	=> Scheme_Typography::TYPOGRAPHY_3,
					'selector' 	=> '{{WRAPPER}} .ee-post__meta--comments',
					'exclude'	=> [
						'font_family',
						'font_size',
						'line_height',
						'letter_spacing',
					],
				]
			);

		$this->end_controls_section();

	}

	public function register_terms_style_controls() {

		$this->start_controls_section(
			'section_style_terms',
			[
				'label' => __( 'Terms', 'elementor-extras' ),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => [
					'post_terms_position!' => '',
				]
			]
		);

			$this->add_control(
				'terms_terms_heading',
				[
					'separator' => 'before',
					'label' 	=> __( 'Terms', 'elementor-extras' ),
					'type' 		=> Controls_Manager::HEADING,
				]
			);

			$this->add_responsive_control(
				'terms_distance',
				[
					'label' 		=> __( 'Distance', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SLIDER,
					'range' 		=> [
						'px' 		=> [
							'min' => 0,
							'max' => 48,
						],
					],
					'selectors' 	=> [
						'{{WRAPPER}} .ee-post__terms' => 'margin-bottom: {{SIZE}}px',
					],
					'condition' => [
						'post_terms_position!' => '',
					]
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' 		=> 'terms_typography',
					'label' 	=> __( 'Typography', 'elementor-extras' ),
					'scheme' 	=> Scheme_Typography::TYPOGRAPHY_3,
					'selector' 	=> '{{WRAPPER}} .ee-post__terms__term',
					'condition' => [
						'post_terms_position!' => '',
					]
				]
			);

			$this->add_control(
				'terms_term_heading',
				[
					'separator' => 'before',
					'label' 	=> __( 'Term', 'elementor-extras' ),
					'type' 		=> Controls_Manager::HEADING,
				]
			);

			$this->add_responsive_control(
				'terms_spacing',
				[
					'label' 		=> __( 'Horzontal Spacing', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SLIDER,
					'range' 		=> [
						'px' 		=> [
							'min' => 0,
							'max' => 48,
						],
					],
					'selectors' 	=> [
						'{{WRAPPER}} .ee-post__terms' => 'margin-left: -{{SIZE}}px',
						'{{WRAPPER}} .ee-post__terms__term' => 'margin-left: {{SIZE}}px',
						'{{WRAPPER}} .ee-post__terms__separator' => 'margin-left: {{SIZE}}px',
					],
					'condition' => [
						'post_terms_count!' => '1',
						'post_terms_position!' => '',
					]
				]
			);

			$this->add_responsive_control(
				'terms_vertical_spacing',
				[
					'label' 		=> __( 'Vertical Spacing', 'elementor-extras' ),
					'description'	=> __( 'If you have multuple lines of terms, this will help you distance them from one another', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SLIDER,
					'range' 		=> [
						'px' 		=> [
							'min' => 0,
							'max' => 48,
						],
					],
					'selectors' 	=> [
						'{{WRAPPER}} .ee-post__terms__term' => 'margin-bottom: {{SIZE}}px',
					],
					'condition' => [
						'post_terms_count!' => '1',
					]
				]
			);

			$this->add_responsive_control(
				'terms_padding',
				[
					'label' 		=> __( 'Padding', 'elementor-extras' ),
					'type' 			=> Controls_Manager::DIMENSIONS,
					'size_units' 	=> [ 'px', 'em', '%' ],
					'selectors' 	=> [
						'{{WRAPPER}} .ee-post__terms__item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'condition' => [
						'post_terms_position!' => '',
					]
				]
			);

			$this->add_control(
				'terms_border_radius',
				[
					'type' 			=> Controls_Manager::DIMENSIONS,
					'label' 		=> __( 'Border Radius', 'elementor-extras' ),
					'size_units' 	=> [ 'px', '%' ],
					'selectors' 	=> [
						'{{WRAPPER}} .ee-post__terms__item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'condition' => [
						'post_terms_position!' => '',
					]
				]
			);

			$this->add_control(
				'terms_separator_heading',
				[
					'separator' => 'before',
					'label' 	=> __( 'Separator', 'elementor-extras' ),
					'type' 		=> Controls_Manager::HEADING,
				]
			);

			$this->add_control(
				'terms_separator_color',
				[
					'label' 	=> __( 'Color', 'elementor-extras' ),
					'type' 		=> Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ee-post__terms__separator' => 'color: {{VALUE}};',
					],
				]
			);

			$this->start_controls_tabs( 'terms_tabs_hover' );

			$this->start_controls_tab( 'terms_tab_default', [ 'label' => __( 'Default', 'elementor-extras' ) ] );

				$this->add_control(
					'terms_color',
					[
						'label' 	=> __( 'Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ee-post__terms__item' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					'terms_background_color',
					[
						'label' 	=> __( 'Background Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ee-post__terms__item' => 'background-color: {{VALUE}};',
						],
					]
				);

			$this->end_controls_tab();

			$this->start_controls_tab( 'terms_tab_hover', [
				'label' => __( 'Hover', 'elementor-extras' ),
				'condition'	 => [
					'post_terms_position!' => 'media',
				]
			] );

				$this->add_control(
					'terms_color_hover',
					[
						'label' 	=> __( 'Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ee-post__terms__item:hover' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					'terms_background_color_hover',
					[
						'label' 	=> __( 'Background Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ee-post__terms__item:hover' => 'background-color: {{VALUE}};',
						],
					]
				);

			$this->end_controls_tab();

			$this->end_controls_tabs();

		$this->end_controls_section();

	}

	public function register_title_style_controls() {

		$this->start_controls_section(
			'section_style_title',
			[
				'label' => __( 'Title', 'elementor-extras' ),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => [
					'post_title_position!' => '',
				]
			]
		);

			$this->add_responsive_control(
				'title_align',
				[
					'label' 		=> __( 'Align Text', 'elementor-extras' ),
					'type' 			=> Controls_Manager::CHOOSE,
					'default' 		=> '',
					'options' 		=> [
						'left'    		=> [
							'title' 	=> __( 'Left', 'elementor-extras' ),
							'icon' 		=> 'fa fa-align-left',
						],
						'center' 		=> [
							'title' 	=> __( 'Center', 'elementor-extras' ),
							'icon' 		=> 'fa fa-align-center',
						],
						'right' 		=> [
							'title' 	=> __( 'Right', 'elementor-extras' ),
							'icon' 		=> 'fa fa-align-right',
						],
					],
					'selectors'		=> [
						'{{WRAPPER}} .ee-post__title' 	=> 'text-align: {{VALUE}};',
					],
					'condition' => [
						'post_title_position!' => '',
					]
				]
			);

			$this->add_control(
				'title_color',
				[
					'label' 	=> __( 'Color', 'elementor-extras' ),
					'type' 		=> Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ee-post__title' => 'color: {{VALUE}};',
					],
					'condition' => [
						'post_title_position!' => '',
					]
				]
			);

			$this->add_control(
				'title_background_color',
				[
					'label' 	=> __( 'Background Color', 'elementor-extras' ),
					'type' 		=> Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ee-post__title' => 'background-color: {{VALUE}};',
					],
				]
			);

			$this->add_responsive_control(
				'title_margin',
				[
					'label' 		=> __( 'Margin', 'elementor-extras' ),
					'type' 			=> Controls_Manager::DIMENSIONS,
					'size_units' 	=> [ 'px', 'em', '%' ],
					'selectors' 	=> [
						'{{WRAPPER}} .ee-post__title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'condition' => [
						'post_title_position!' => '',
					]
				]
			);

			$this->add_responsive_control(
				'title_padding',
				[
					'label' 		=> __( 'Padding', 'elementor-extras' ),
					'type' 			=> Controls_Manager::DIMENSIONS,
					'size_units' 	=> [ 'px', 'em', '%' ],
					'selectors' 	=> [
						'{{WRAPPER}} .ee-post__title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'condition' => [
						'post_title_position!' => '',
					]
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' 		=> 'title_typography',
					'label' 	=> __( 'Typography', 'elementor-extras' ),
					'scheme' 	=> Scheme_Typography::TYPOGRAPHY_3,
					'selector' 	=> '{{WRAPPER}} .ee-post__title__heading',
					'condition' => [
						'post_title_position!' => '',
					]
				]
			);

			$this->add_group_control(
				Group_Control_Text_Shadow::get_type(),
				[
					'name' 		=> 'title_shadow',
					'selector' 	=> '{{WRAPPER}} .ee-post__title__heading',
					'condition' => [
						'post_title_position!' => '',
					]
				]
			);

		$this->end_controls_section();

	}

	public function register_excerpt_style_controls() {

		$this->start_controls_section(
			'section_style_excerpt',
			[
				'label' => __( 'Excerpt', 'elementor-extras' ),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => [
					'post_excerpt_position!' => '',
				]
			]
		);

			$this->add_responsive_control(
				'excerpt_align',
				[
					'label' 		=> __( 'Align Text', 'elementor-extras' ),
					'type' 			=> Controls_Manager::CHOOSE,
					'default' 		=> '',
					'options' 		=> [
						'left'    		=> [
							'title' 	=> __( 'Left', 'elementor-extras' ),
							'icon' 		=> 'fa fa-align-left',
						],
						'center' 		=> [
							'title' 	=> __( 'Center', 'elementor-extras' ),
							'icon' 		=> 'fa fa-align-center',
						],
						'right' 		=> [
							'title' 	=> __( 'Right', 'elementor-extras' ),
							'icon' 		=> 'fa fa-align-right',
						],
					],
					'selectors'		=> [
						'{{WRAPPER}} .ee-post__excerpt' => 'text-align: {{VALUE}};',
					],
					'condition' => [
						'post_excerpt_position!' => '',
					]
				]
			);

			$this->add_control(
				'excerpt_color',
				[
					'label' 	=> __( 'Color', 'elementor-extras' ),
					'type' 		=> Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ee-post__excerpt' => 'color: {{VALUE}};',
					],
					'condition' => [
						'post_excerpt_position!' => '',
					]
				]
			);

			$this->add_responsive_control(
				'excerpt_margin',
				[
					'label' 		=> __( 'Margin', 'elementor-extras' ),
					'type' 			=> Controls_Manager::DIMENSIONS,
					'size_units' 	=> [ 'px', 'em', '%' ],
					'selectors' 	=> [
						'{{WRAPPER}} .ee-post__excerpt' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'condition' => [
						'post_excerpt_position!' => '',
					]
				]
			);

			$this->add_responsive_control(
				'excerpt_padding',
				[
					'label' 		=> __( 'Padding', 'elementor-extras' ),
					'type' 			=> Controls_Manager::DIMENSIONS,
					'size_units' 	=> [ 'px', 'em', '%' ],
					'selectors' 	=> [
						'{{WRAPPER}} .ee-post__excerpt' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'condition' => [
						'post_excerpt_position!' => '',
					]
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' 		=> 'excerpt_typography',
					'label' 	=> __( 'Typography', 'elementor-extras' ),
					'scheme' 	=> Scheme_Typography::TYPOGRAPHY_3,
					'selector' 	=> '{{WRAPPER}} .ee-post__excerpt',
				]
			);

		$this->end_controls_section();

	}

	public function register_add_to_cart_style_controls() {

		$this->start_controls_section(
			'section_style_add_to_cart',
			[
				'label' => __( 'Add to Cart', 'elementor-extras' ),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => [
					'post_add_to_cart_position!' => '',
				]
			]
		);

			$this->add_responsive_control(
				'add_to_cart_distance',
				[
					'label' 		=> __( 'Distance', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SLIDER,
					'range' 		=> [
						'px' 		=> [
							'min' => 0,
							'max' => 48,
						],
					],
					'selectors' 	=> [
						'{{WRAPPER}} .ee-post__add-to-cart' => 'margin-bottom: {{SIZE}}px',
					],
					'condition' => [
						'post_add_to_cart_position!' => '',
					]
				]
			);

			$this->add_responsive_control(
				'add_to_cart_align',
				[
					'label' 		=> __( 'Align', 'elementor-extras' ),
					'type' 			=> Controls_Manager::CHOOSE,
					'default' 		=> '',
					'options' 		=> [
						'flex-start' 	=> [
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
					'selectors' 	=> [
						'{{WRAPPER}} .ee-post__add-to-cart' => 'justify-content: {{VALUE}};'
					],
					'condition' => [
						'post_add_to_cart_position!' => '',
					]
				]
			);

			$this->add_responsive_control(
				'add_to_cart_padding',
				[
					'label' 		=> __( 'Padding', 'elementor-extras' ),
					'type' 			=> Controls_Manager::DIMENSIONS,
					'size_units' 	=> [ 'px', 'em', '%' ],
					'selectors' 	=> [
						'{{WRAPPER}} .ee-post__add-to-cart .add_to_cart_button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'condition' => [
						'post_add_to_cart_position!' => '',
					]
				]
			);

			$this->add_control(
				'add_to_cart_border_radius',
				[
					'separator'		=> 'after',
					'type' 			=> Controls_Manager::DIMENSIONS,
					'label' 		=> __( 'Border Radius', 'elementor-extras' ),
					'size_units' 	=> [ 'px', '%' ],
					'selectors' 	=> [
						'{{WRAPPER}}  .ee-post__add-to-cart .add_to_cart_button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'condition' => [
						'post_add_to_cart_position!' => '',
					]
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' 		=> 'add_to_cart_typography',
					'label' 	=> __( 'Typography', 'elementor-extras' ),
					'scheme' 	=> Scheme_Typography::TYPOGRAPHY_3,
					'selector' 	=> '{{WRAPPER}} .ee-post__add-to-cart .add_to_cart_button',
					'condition' => [
						'post_add_to_cart_position!' => '',
					]
				]
			);

			$this->start_controls_tabs( 'add_to_cart_tabs_hover' );

			$this->start_controls_tab( 'add_to_cart_tab_default', [
				'label' 	=> __( 'Default', 'elementor-extras' ),
				'condition' => [
						'post_add_to_cart_position!' => '',
					]
			] );

				$this->add_control(
					'add_to_cart_color',
					[
						'label' 	=> __( 'Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ee-post__add-to-cart .add_to_cart_button' => 'color: {{VALUE}};',
						],
						'condition' => [
							'post_add_to_cart_position!' => '',
						]
					]
				);

				$this->add_control(
					'add_to_cart_background_color',
					[
						'label' 	=> __( 'Background Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ee-post__add-to-cart .add_to_cart_button' => 'background-color: {{VALUE}};',
						],
						'condition' => [
							'post_add_to_cart_position!' => '',
						]
					]
				);

			$this->end_controls_tab();

			$this->start_controls_tab( 'add_to_cart_tab_hover', [
				'label' 	=> __( 'Hover', 'elementor-extras' ),
				'condition' => [
					'post_add_to_cart_position!' => '',
				]
			] );

				$this->add_control(
					'add_to_cart_color_hover',
					[
						'label' 	=> __( 'Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ee-post__add-to-cart .add_to_cart_button:hover' => 'color: {{VALUE}};',
						],
						'condition' => [
							'post_add_to_cart_position!' => '',
						]
					]
				);

				$this->add_control(
					'add_to_cart_background_color_hover',
					[
						'label' 	=> __( 'Background Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ee-post__add-to-cart .add_to_cart_button:hover' => 'background-color: {{VALUE}};',
						],
						'condition' => [
							'post_add_to_cart_position!' => '',
						]
					]
				);

			$this->end_controls_tab();

			$this->end_controls_tabs();

	}

	public function is_in_area( $setting_key, $area ) {

		if ( empty( $setting_key ) || empty( $area ) )
			return;

		$settings = $this->get_settings();

		if( $settings[ $setting_key ] === $area )
			return true;

		return false;
	}

	/**
	 * Checks if a particular area of the layout has any content
	 *
	 * @since 1.6.0
	 * @return bool
	 * 
	 */
	public function is_empty_area( $area ) {

		if ( empty( $area ) )
			return;

		$settings = $this->get_settings();

		foreach ( $this->_content_parts as $_part ) {
			if ( $settings['post_' . $_part . '_position'] === $area ) {

				// Additional check to see if we have any terms in this area
				if ( 'terms' === $_part ) {
					if ( false !== $this->get_terms() )
						return false;
				} else {
					return false;
				}
			}
		}

		return true;
	}

	public function render() {}

	protected function _content_template() {}

	public function render_previous_nav_link( $limit = null ) {

		$page = $this->get_current_page();
		$output = '';

		if ( $page > 1 ) {
			$prev_page = intval( $page ) - 1;
			$prev_page = ( $prev_page < 1 ) ? 1 : $prev_page;

			printf( '<a class="ee-pagination__previous page-numbers" href="%1$s">%2$s</a>', get_pagenum_link( $prev_page ), $this->get_current_skin()->get_instance_value( 'pagination_previous_label' ) );
		}
	}

	public function render_next_nav_link( $limit = null ) {
		if ( ! $limit )
			$limit = $this->_query->max_num_pages;

		$page = $this->get_current_page();
		$output = '';
		$next_page = intval( $page ) + 1;

		if ( $next_page <= $limit ) {
			printf( '<a class="ee-pagination__next page-numbers" href="%1$s">%2$s</a>', get_pagenum_link( $next_page ), $this->get_current_skin()->get_instance_value( 'pagination_next_label' ) );
		}
	}

	public function query_posts() {
		$query_args = \ElementorPro\Modules\QueryControl\Module::get_query_args( 'posts', $this->get_settings() );

		$query_args['posts_per_page'] = $this->get_settings( 'posts_per_page' );
		$query_args['paged'] = $this->get_current_page();

		$this->_query = new \WP_Query( $query_args );
	}
}
