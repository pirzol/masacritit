<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Pojo_Berlin_Customize_Register_Fields {

	public function section_style( $sections = array() ) {
		$fields = array();

		$fields[] = array(
			'id'    => 'primary_color',
			'title' => __( 'Primary Color', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std'   => '#CCB768',
		);

		$fields[] = array(
			'id'    => 'secondary_color',
			'title' => __( 'Secondary Color', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std'   => '#878787',
		);

		$fields[] = array(
			'id'    => 'layout_site',
			'title' => __( 'Layout Site', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_SELECT,
			'choices' => array(
				'normal' => __( 'Normal', 'pojo' ),
				'wide' => __( 'Wide', 'pojo' ),
			),
			'std' => 'wide',
		);

		$fields[] = array(
			'id'    => 'bg_body',
			'title' => __( 'Background Site', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_BACKGROUND,
			'std' => array(
				'color' => '#F7F7F7',
				'image'  => '',
				'position'  => 'top center',
				'repeat' => 'repeat',
				'size' => 'auto',
				'attachment' => 'scroll',
			),
			'selector' => 'body',
			'change_type' => 'background',
		);

		$sections[] = array(
			'id' => 'style',
			'title' => __( 'Style', 'pojo' ),
			'desc' => '',
			'fields' => $fields,
		);

		return $sections;
	}

	public function section_logo( $sections = array() ) {
		$fields = array();

		$fields[] = array(
			'id'    => 'typo_site_title',
			'title' => __( 'Site Name', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '37px',
				'family'  => 'Rokkitt',
				'weight' => 'normal',
				'color' => '#CCB768',
				'line_height' => '1em',
			),
			'selector' => 'div.logo-text a',
			'change_type' => 'typography',
		);

		$fields[] = array(
			'id'    => 'image_logo',
			'title' => __( 'Choose Logo', 'pojo' ),
			'type' => Pojo_Theme_Customize::FIELD_IMAGE,
			'std'   => get_template_directory_uri() . '/assets/images/logo.png',
		);

		$fields[] = array(
			'id'    => 'image_logo_margin_top',
			'title' => __( 'Logo Margin Top', 'pojo' ),
			'std'   => '30px',
			'selector' => '.logo',
			'change_type' => 'margin_top',
		);

		$fields[] = array(
			'id'    => 'image_logo_margin_bottom',
			'title' => __( 'Logo Margin Bottom', 'pojo' ),
			'std'   => '30px',
			'selector' => '.logo',
			'change_type' => 'margin_bottom',
		);

		$fields[] = array(
			'id'    => 'image_sticky_header_logo',
			'title' => __( 'Logo Sticky Header (and Mobile)', 'pojo' ),
			'type' => Pojo_Theme_Customize::FIELD_IMAGE,
			'std'   => get_template_directory_uri() . '/assets/images/logo-sticky.png',
		);

		$sections[] = array(
			'id' => 'logo',
			'title' => __( 'Logo', 'pojo' ),
			'desc' => '',
			'fields' => $fields,
		);

		return $sections;
	}

	public function section_header( $sections = array() ) {
		$fields = array();

		$fields[] = array(
			'id' => 'bg_header',
			'title' => __( 'Background', 'pojo' ),
			'type' => Pojo_Theme_Customize::FIELD_BACKGROUND,
			'std' => array(
				'color' => '#ffffff',
				'image'  => '',
				'position'  => 'top center',
				'repeat' => 'repeat-x',
				'size' => 'auto',
				'attachment' => 'scroll',
			),
			'skip_transport' => true,
			'selector' => '#header, .sticky-header',
			'change_type' => 'background',
		);

		$fields[] = array(
			'id'    => 'header_layout',
			'title' => __( 'Header Layout', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_SELECT,
			'choices' => array(
				'logo_right' => __( 'Menu Left - Logo Right', 'pojo' ),
				'logo_left' => __( 'Logo Left - Menu Right', 'pojo' ),
			),
			'std' => 'logo_left',
			'skip_transport' => true,
		);

		$fields[] = array(
			'id' => 'chk_enable_sticky_header',
			'title' => __( 'Sticky Header', 'pojo' ),
			'type' => Pojo_Theme_Customize::FIELD_CHECKBOX,
			'std' => true,
		);

		$sections[] = array(
			'id' => 'header',
			'title' => __( 'Header', 'pojo' ),
			'desc' => '',
			'fields' => $fields,
		);

		return $sections;
	}

	public function section_menus( $sections = array() ) {
		$fields = array();

		$fields = apply_filters( 'pojo_customizer_section_menus_before', $fields );

		$fields[] = array(
			'id' => 'height_menu',
			'title' => __( 'Height', 'pojo' ),
			'std' => '90px',
		);

		$fields[] = array(
			'id'    => 'typo_menu_primary',
			'title' => __( 'Menu Primary', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '19px',
				'family'  => 'Rokkitt',
				'weight' => 'normal',
				'color' => '#838383',
				'line_height' => false, // Skip for that's value !
			),
			'selector' => '.sf-menu a, .mobile-menu a,.sf-menu a:hover,.sf-menu li.active a, .sf-menu li.current-menu-item > a,.sf-menu .sfHover > a,.sf-menu .sfHover > li.current-menu-item > a,.sf-menu li.current-menu-ancestor > a,.mobile-menu a:hover,.mobile-menu li.current-menu-item > a',
			'change_type' => 'typography',
			'skip_transport' => true,
		);

		$fields[] = array(
			'id'    => 'color_menu_primary_hover',
			'title' => __( 'Menu Hover', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std'   => '#CCB768',
			'selector' => '',
			'change_type' => 'color',
			'skip_transport' => true,
		);

		$fields[] = array(
			'id'    => 'color_bg_sub_menu',
			'title' => __( 'Sub Menu - Background', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std' => '#ffffff',
			'selector' => '.nav-main .sf-menu .sub-menu',
			'change_type' => 'bg_color',
		);

		$fields[] = array(
			'id'    => 'color_bg_sub_menu_hover',
			'title' => __( 'Sub Menu - BG Hover', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std' => '#EEEEEE',
			'selector' => '.nav-main .sf-menu .sub-menu li:hover > a,.nav-main .sf-menu .sub-menu li.current-menu-item > a',
			'change_type' => 'bg_color',
		);

		$fields[] = array(
			'id'    => 'typo_sub_menu_link',
			'title' => __( 'Sub Menu', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '16px',
				'family'  => 'Rokkitt',
				'weight' => 'normal',
				'color' => '#878787',
				'line_height' => '3em',
			),
			'selector' => '.nav-main .sf-menu .sub-menu li a',
			'change_type' => 'typography',
		);

		$fields[] = array(
			'id'    => 'color_sub_menu_link_hover',
			'title' => __( 'Sub Menu - Hover', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std'   => '#CCB768',
			'selector' => '.nav-main .sf-menu .sub-menu li:hover > a,.nav-main .sf-menu .sub-menu li.current-menu-item > a',
			'change_type' => 'color',
			'skip_transport' => true,
		);

		$fields = apply_filters( 'pojo_customizer_section_menus_after', $fields );

		$sections[] = array(
			'id' => 'menus',
			'title'      => __( 'Navigation', 'pojo' ),
			'desc'       => '',
			'fields'     => $fields,
		);

		return $sections;
	}

	public function section_search_bar( $sections = array() ) {
		$fields = array();

		$fields[] = array(
			'id'    => 'chk_enable_menu_search',
			'title' => __( 'Add Search Button to Primary Menu', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_CHECKBOX,
			'std'   => true,
		);

		$fields[] = array(
			'id'    => 'bg_bar_search',
			'title' => __( 'Background Search Bar', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std'   => '#343434',
			'selector' => '.search-section',
			'change_type' => 'bg_color',
			'skip_transport' => true,
		);

		$fields[] = array(
			'id'    => 'color_search_field',
			'title' => __( 'Color Field', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std'   => '#ffffff',
			'selector' => '.search-section,.search-section .form-search .field',
			'change_type' => 'color',
			'skip_transport' => true,
		);

		$sections[] = array(
			'id' => 'search_bar',
			'title' => __( 'Search Bar', 'pojo' ),
			'desc' => '',
			'fields' => $fields,
		);

		return $sections;
	}

	public function section_title_bar( $sections = array() ) {
		$fields = array();

		$fields[] = array(
			'id'    => 'ph_height',
			'title' => __( 'Height', 'pojo' ),
			'std'   => '350px',
		);

		$fields[] = array(
			'id'    => 'ph_style',
			'title' => __( 'Style', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_SELECT,
			'choices' => array(
				'custom_bg' => __( 'Custom Background', 'pojo' ),
				'transparent' => __( 'Transparent Background', 'pojo' ),
			),
			'std' => 'custom_bg',
		);

		$fields[] = array(
			'id' => 'ph_background',
			'title' => __( 'Background', 'pojo' ),
			'type' => Pojo_Theme_Customize::FIELD_BACKGROUND,
			'std' => array(
				'color' => '#474747',
				'image'  => '',
				'position'  => 'center center',
				'repeat' => 'repeat',
				'size' => 'cover',
				'attachment' => 'scroll',
			),
			'selector' => '#page-header.page-header-style-custom_bg',
			'change_type' => 'background',
		);

		$fields[] = array(
			'id'    => 'ph_typo_title',
			'title' => __( 'Title', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '60px',
				'family'  => 'Rokkitt',
				'weight' => 'normal',
				'color' => '#ffffff',
				'transform' => 'uppercase',
				'line_height' => '70px',
			),
			'selector' => '#page-header',
			'change_type' => 'typography',
		);

		$fields[] = array(
			'id'    => 'ph_typo_breadcrumbs',
			'title' => __( 'Breadcrumbs', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '12px',
				'family'  => 'Rokkitt',
				'weight' => 'normal',
				'color' => '#CCB768',
				'line_height' => '1em',
			),
			'selector' => '#page-header div.breadcrumbs, #page-header div.breadcrumbs a',
			'change_type' => 'typography',
		);

		$sections[] = array(
			'id' => 'title_bar',
			'title' => __( 'Title Bar', 'pojo' ),
			'desc' => '',
			'fields' => $fields,
		);

		return $sections;
	}

	public function section_typography( $sections = array() ) {
		$fields = array();

		$fields[] = array(
			'id'    => 'typo_body_text',
			'title' => __( 'Body Text', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '15px',
				'family'  => 'PT Sans',
				'weight' => '300',
				'color' => '#878787',
				'line_height' => '1.8em',
			),
			'selector' => 'body',
			'change_type' => 'typography',
		);

		$fields[] = array(
			'id'    => 'color_link',
			'title' => __( 'Link', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std'   => '#CCB768',
			'selector' => 'a, .entry-meta, #breadcrumbs',
			'change_type' => 'color',
			'skip_transport' => true,
		);

		$fields[] = array(
			'id'    => 'color_link_hover',
			'title' => __( 'Link Hover', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std'   => '#878787',
			'selector' => 'a:hover, a:focus',
			'change_type' => 'color',
			'skip_transport' => true,
		);

		$fields[] = array(
			'id'    => 'color_text_selection',
			'title' => __( 'Text Selection', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std'   => '#ffffff',
			'selector' => 'selection',
			'change_type' => 'text_selection',
		);

		$fields[] = array(
			'id'    => 'color_text_bg_selection',
			'title' => __( 'Text Background Selection', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std'   => '#CCB768',
			'selector' => 'selection',
			'change_type' => 'bg_selection',
		);

		$fields[] = array(
			'id'    => 'typo_h1',
			'title' => __( 'H1 - Page Title', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '40px',
				'family'  => 'Rokkitt',
				'weight' => 'bold',
				'color' => '#4c4c4c',
				'transform' => 'uppercase',
				'line_height' => '1.3em',
			),
			'selector' => 'h1',
			'change_type' => 'typography',
		);

		$fields[] = array(
			'id'    => 'typo_h2',
			'title' => __( 'H2', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '36px',
				'family'  => 'Rokkitt',
				'weight' => 'bold',
				'color' => '#4c4c4c',
				'transform' => 'uppercase',
				'line_height' => '1.3em',
			),
			'selector' => 'h2',
			'change_type' => 'typography',
		);

		$fields[] = array(
			'id'    => 'typo_h3',
			'title' => __( 'H3 - List Title', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '27px',
				'family'  => 'Rokkitt',
				'weight' => 'bold',
				'color' => '#4c4c4c',
				'transform' => 'uppercase',
				'line_height' => '1.5em',
			),
			'selector' => 'h3',
			'change_type' => 'typography',
		);


		$fields[] = array(
			'id'    => 'typo_h4',
			'title' => __( 'H4 - Grid Title', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '20px',
				'family'  => 'Rokkitt',
				'weight' => 'bold',
				'color' => '#4c4c4c',
				'transform' => 'uppercase',
				'line_height' => '1.5em',
			),
			'selector' => 'h4',
			'change_type' => 'typography',
		);

		$fields[] = array(
			'id'    => 'typo_h5',
			'title' => __( 'H5', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '21px',
				'family'  => 'Rokkitt',
				'weight' => 'normal',
				'color' => '#5b5b5b',
				'line_height' => '1.5em',
			),
			'selector' => 'h5',
			'change_type' => 'typography',
		);

		$fields[] = array(
			'id'    => 'typo_h6',
			'title' => __( 'H6', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '17px',
				'family'  => 'Rokkitt',
				'weight' => '400',
				'color' => '#6d6d6d',
				'line_height' => '1.5em',
			),
			'selector' => 'h6',
			'change_type' => 'typography',
		);

		$sections[] = array(
			'id' => 'typography',
			'title'      => __( 'Typography', 'pojo' ),
			'desc'       => '',
			'fields'     => $fields,
		);

		return $sections;
	}

	public function section_sidebar( $sections = array() ) {
		$fields = array();

		$fields[] = array(
			'id'    => 'typo_sidebar_text',
			'title' => __( 'Text', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '15px',
				'family'  => 'PT Sans',
				'weight' => '300',
				'color' => '#878787',
				'line_height' => '1.8em',
			),
			'selector' => '#sidebar',
			'change_type' => 'typography',
		);

		$fields[] = array(
			'id'    => 'color_sidebar_link',
			'title' => __( 'Link', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std'   => '#CCB768',
			'selector' => '#sidebar a',
			'change_type' => 'color',
		);

		$fields[] = array(
			'id'    => 'color_sidebar_link_hover',
			'title' => __( 'Link Hover', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std'   => '#878787',
			'selector' => '#sidebar a:hover',
			'change_type' => 'color',
			'skip_transport' => true,
		);

		$fields[] = array(
			'id'    => 'typo_sidebar_widget_text',
			'title' => __( 'Widget Title', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '21px',
				'family'  => 'Rokkitt',
				'weight' => 'normal',
				'color' => '#5b5b5b',
				'line_height' => '1.5em',
			),
			'selector' => '#sidebar .widget-title',
			'change_type' => 'typography',
		);

		$sections[] = array(
			'id' => 'sidebar',
			'title'      => __( 'Sidebar', 'pojo' ),
			'desc'       => '',
			'fields'     => $fields,
		);

		return $sections;
	}

	public function section_footer( $sections = array() ) {
		$fields = array();

		$fields[] = array(
			'id'    => 'color_bg_footer',
			'title' => __( 'Background Color', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std' => '#2c2c2c',
			'selector' => '#footer',
			'change_type' => 'bg_color',
		);

		$fields[] = array(
			'id'    => 'typo_footer_text',
			'title' => __( 'Text', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '16px',
				'family'  => 'Rokkitt',
				'weight' => 'normal',
				'color' => '#848484',
				'line_height' => '1.3em',
			),
			'selector' => '#footer',
			'change_type' => 'typography',
		);

		$fields[] = array(
			'id'    => 'color_footer_link',
			'title' => __( 'Link', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std'   => '#888888',
			'selector' => '#footer a',
			'change_type' => 'color',
		);

		$fields[] = array(
			'id'    => 'color_footer_link_hover',
			'title' => __( 'Link Hover', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std'   => '#CCB768',
			'selector' => '#footer a:hover',
			'change_type' => 'color',
			'skip_transport' => true,
		);

		$fields[] = array(
			'id'    => 'typo_footer_widget_text',
			'title' => __( 'Widget Title', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '19px',
				'family'  => 'Rokkitt',
				'weight' => 'normal',
				'color' => '#CCB768',
				'line_height' => '2em',
			),
			'selector' => '#sidebar-footer .widget-title',
			'change_type' => 'typography',
		);

		$fields[] = array(
			'id'    => 'sidebar_footer_columns',
			'title' => __( 'Widget Columns', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_SELECT,
			'choices' => array(
				'1' => '1',
				'2' => '2',
				'3' => '3',
				'4' => '4',
			),
			'std' => '4',
		);

		$sections[] = array(
			'id' => 'footer',
			'title'      => __( 'Footer', 'pojo' ),
			'desc'       => '',
			'fields'     => $fields,
		);

		return $sections;
	}

	public function section_copyright( $sections = array() ) {
		$fields = array();

		$fields[] = array(
			'id'    => 'color_bg_copyright',
			'title' => __( 'Background Color', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std' => '#303030',
			'selector' => '#copyright',
			'change_type' => 'bg_color',
		);

		$fields[] = array(
			'id'    => 'typo_copyright_text',
			'title' => __( 'Text', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_TYPOGRAPHY,
			'std'   => array(
				'size'  => '13px',
				'family'  => 'Rokkitt',
				'weight' => 'normal',
				'color' => '#CCB768',
				'line_height' => '70px',
			),
			'selector' => '#copyright',
			'change_type' => 'typography',
		);

		$fields[] = array(
			'id'    => 'color_copyright_link',
			'title' => __( 'Link', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std'   => '#CCB768',
			'selector' => '#copyright a',
			'change_type' => 'color',
		);

		$fields[] = array(
			'id'    => 'color_copyright_link_hover',
			'title' => __( 'Link Hover', 'pojo' ),
			'type'  => Pojo_Theme_Customize::FIELD_COLOR,
			'std'   => '#ffffff',
			'selector' => '#copyright a:hover',
			'change_type' => 'color',
			'skip_transport' => true,
		);

		$sections[] = array(
			'id' => 'copyright',
			'title'      => __( 'Copyright', 'pojo' ),
			'desc'       => '',
			'fields'     => $fields,
		);

		return $sections;
	}

	public function pojo_wp_head_custom_css_code( Pojo_Create_CSS_Code $css_code ) {
		$primary_color   = get_theme_mod( 'primary_color' );
		$secondary_color = get_theme_mod( 'secondary_color' );

		if ( $primary_color && $secondary_color ) {
			$string = <<<CSSCODE
			article.sticky:before {background-color: {$primary_color};}
			.image-link:hover {background-color: {$primary_color};}
			#breadcrumbs .separator {color: {$primary_color};}
			.nav-main .pojo-menu-cart li.cart-checkout a {color: {$primary_color} !important;border-color: {$primary_color} !important;}
			.nav-main .pojo-menu-cart li.cart-checkout a:hover {color: {$secondary_color} !important;border-color: {$secondary_color} !important;}
			.align-pagination .pagination > li > a:hover,.align-pagination .pagination > li > span:hover,.align-pagination .pagination > .active > a,.align-pagination .pagination > .active > span,.align-pagination .pagination > .active > a:hover,.align-pagination .pagination > .active > span:hover,.align-pagination .pagination > .active > a:focus,.align-pagination .pagination > .active > span:focus {border-color: {$primary_color}; color: {$primary_color};}
			.pojo-loadmore-wrap .button:hover,.pojo-loadmore-wrap .pojo-loading,.pojo-loading-wrap .button:hover,.pojo-loading-wrap .pojo-loading:hover {border-color: {$primary_color}; color: {$primary_color};}
			.gallery-item.grid-item .inbox .caption small {color: {$primary_color};}
			.woocommerce ul.products li.product .caption .price, .woocommerce-page ul.products li.product .caption .price {color: {$primary_color};}
			.category-filters li a {color: {$secondary_color};}
			.category-filters li a:hover,.category-filters li a.active {color: {$primary_color};}
			.widget_tag_cloud a, #sidebar-footer .widget_tag_cloud a {color: {$secondary_color};}
			.widget_tag_cloud a:hover, #sidebar-footer .widget_tag_cloud a:hover {background-color: {$primary_color}; color: {$secondary_color};}
			ul.social-links li a .social-icon:before {background-color: {$primary_color};}
			ul.social-links li a .social-icon:before {color: {$secondary_color}; }
			ul.social-links li a:hover .social-icon:before {background-color: {$secondary_color}; }
			ul.social-links li a:hover .social-icon:before {color: {$primary_color}; }
			.read-more,.more-link span {color: {$primary_color};}
			.read-more:hover,.more-link span:hover {color: {$secondary_color};}
			.navbar-toggle:hover .icon-bar, .navbar-toggle:focus .icon-bar {background-color: {$primary_color};}
			.catalog-item-price {color: {$primary_color};}
			.button,.button.size-small,.button.size-large,.button.size-xl,.button.size-xxl {border-color: {$primary_color};color: {$primary_color};}
			.button:hover,.button.size-small:hover,.button.size-large:hover,.button.size-xl:hover {border-color: {$secondary_color};color: {$secondary_color};}
CSSCODE;
			
			$css_code->add_data( $string );

		} // End style colors

		$option = get_theme_mod( 'height_menu', '90px' );
		$css_code->add_value( '.sf-menu a, .menu-no-found,.sf-menu li.pojo-menu-search,.search-header', 'line-height', $option );
		$css_code->add_value( '.sf-menu li:hover ul, .sf-menu li.sfHover ul', 'top', $option );

		$option = get_theme_mod( 'typo_menu_primary' );
		if ( ! empty( $option['color'] ) ) {
			$css_code->add_value( 'a.search-toggle', 'color', $option['color'] );
			$css_code->add_value( '.navbar-toggle', 'border-color', $option['color'] );
			$css_code->add_value( '.icon-bar', 'background-color', $option['color'] );
		}

		$option = get_theme_mod( 'color_menu_primary_hover' );
		$css_code->add_value( '.nav-main .sf-menu > li > a:hover span:after, sf-menu > li.active a span:after, .sf-menu > li.current-menu-item > a span:after,.sf-menu li.current-menu-ancestor > a span:after,.sf-menu .sfHover > a span:after,.sf-menu .sfHover > li.current-menu-item > a span:after', 'background', $option );

		$option = get_theme_mod( 'color_bg_sub_menu_hover' );
		$css_code->add_value( '.sf-menu .sub-menu li > a', 'border-color', $option );

		$css_code->add_value( '#page-header', 'height', get_theme_mod( 'ph_height' ) );

		$css_code->add_value( '.search-section .form-search .button', 'color', get_theme_mod( 'color_search_button' ) );
	}

	public function __construct() {
		add_filter( 'pojo_register_customize_sections', array( &$this, 'section_style' ), 100 );
		add_filter( 'pojo_register_customize_sections', array( &$this, 'section_logo' ), 110 );
		add_filter( 'pojo_register_customize_sections', array( &$this, 'section_header' ), 120 );
		add_filter( 'pojo_register_customize_sections', array( &$this, 'section_menus' ), 130 );
		add_filter( 'pojo_register_customize_sections', array( &$this, 'section_search_bar' ), 140 );
		add_filter( 'pojo_register_customize_sections', array( &$this, 'section_title_bar' ), 150 );
		add_filter( 'pojo_register_customize_sections', array( &$this, 'section_typography' ), 160 );
		add_filter( 'pojo_register_customize_sections', array( &$this, 'section_sidebar' ), 165 );
		add_filter( 'pojo_register_customize_sections', array( &$this, 'section_footer' ), 170 );
		add_filter( 'pojo_register_customize_sections', array( &$this, 'section_copyright' ), 180 );

		add_filter( 'pojo_wp_head_custom_css_code', array( &$this, 'pojo_wp_head_custom_css_code' ) );
	}

}
new Pojo_Berlin_Customize_Register_Fields();