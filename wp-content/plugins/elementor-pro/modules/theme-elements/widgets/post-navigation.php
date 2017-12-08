<?php
namespace ElementorPro\Modules\ThemeElements\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use ElementorPro\Base\Base_Widget;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Post_Navigation extends Base_Widget {

	public function get_name() {
		return 'post-navigation';
	}

	public function get_title() {
		return __( 'Post Navigation', 'elementor-pro' );
	}

	public function get_icon() {
		return 'eicon-arrow';
	}

	public function get_script_depends() {
		return [ 'post-navigation' ];
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'section_post_navigation_content',
			[
				'label' => __( 'Post Navigation', 'elementor-pro' ),
			]
		);

		$this->add_control(
			'show_label',
			[
				'label' => __( 'Label', 'elementor-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'elementor-pro' ),
				'label_off' => __( 'Hide', 'elementor-pro' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'prev_label',
			[
				'label' => __( 'Previous Label', 'elementor-pro' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Previous', 'elementor-pro' ),
				'condition' => [
					'show_label' => 'yes',
				],
			]
		);

		$this->add_control(
			'next_label',
			[
				'label' => __( 'Next Label', 'elementor-pro' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Next', 'elementor-pro' ),
				'condition' => [
					'show_label' => 'yes',
				],
			]
		);

		$this->add_control(
			'show_icon',
			[
				'label' => __( 'Icon', 'elementor-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'elementor-pro' ),
				'label_off' => __( 'Hide', 'elementor-pro' ),
				'default' => 'no',
			]
		);

		$this->add_control(
			'show_title',
			[
				'label' => __( 'Post Title', 'elementor-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'elementor-pro' ),
				'label_off' => __( 'Hide', 'elementor-pro' ),
				'default' => 'no',
			]
		);

		$this->add_control(
			'show_seperator',
			[
				'label' => __( 'Seperator', 'elementor-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'elementor-pro' ),
				'label_off' => __( 'Hide', 'elementor-pro' ),
				'default' => 'no',
			]
		);
		$this->add_responsive_control(
			'align',
			[
				'label' => __( 'Alignment', 'elementor-pro' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'elementor-pro' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'elementor-pro' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'elementor-pro' ),
						'icon' => 'fa fa-align-right',
					],
					'justify' => [
						'title' => __( 'Justified', 'elementor-pro' ),
						'icon' => 'fa fa-align-justify',
					],
				],
				'prefix_class' => 'elementor-post-navigation--align-',
				'default' => 'justify',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style',
			[
				'label' => __( 'Post Navigation', 'elementor-pro' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'typography',
				'label' => __( 'Typography', 'elementor-pro' ),
				'selector' => '{{WRAPPER}}',
				'exclude' => [ 'line_height' ],
			]
		);

		$this->start_controls_tabs( 'tabs_post_navigation_style' );

		$this->start_controls_tab(
			'tab_color_normal',
			[
				'label' => __( 'Normal', 'elementor-pro' ),
			]
		);

		$this->add_control(
			'text_color',
			[
				'label' => __( 'Color', 'elementor-pro' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_color_hover',
			[
				'label' => __( 'Hover', 'elementor-pro' ),
			]
		);

		$this->add_control(
			'hover_color',
			[
				'label' => __( 'Color', 'elementor-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} a:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_active_settings();
		$prev_label = $next_label = $prev_icon = $next_icon = '';

		if ( 'yes' === $settings['show_label'] ) {
			$prev_label = $settings['prev_label'] . ' ';
			$next_label = $settings['next_label'] . ' ';
		}

		if ( 'yes' === $settings['show_icon'] ) {
			$prev_icon = '<i class="fa fa-arrow-left" aria-hidden="true"></i> ';
			$next_icon = ' <i class="fa fa-arrow-right" aria-hidden="true"></i>';
		}

		$prev_format = $next_format = '%link';
		$prev_title = $prev_icon . $prev_label;
		$next_title = $next_label . $next_icon;

		if ( 'yes' === $settings['show_title'] ) {
			$prev_title .=  '%title';
		    $next_title = $next_label . '%title' . $next_icon;
        }
        ?>
        <div class="elementor-post-navigation elementor-grid">
		    <div class="elementor-post-navigation__prev elementor-post-navigation__link">
			    <?php previous_post_link( $prev_format, $prev_title ); ?>
            </div>
            <?php if ( 'yes' === $settings['show_seperator'] ) : ?>
                <div class="elementor-post-navigation__seperator"> | </div>
            <?php endif; ?>
            <div class="elementor-post-navigation__next elementor-post-navigation__link">
                <?php next_post_link( $next_format, $next_title ); ?>
            </div>
		</div>
		<?php
	}
}
