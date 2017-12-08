<?php

namespace ElementorExtras\Base;

use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

abstract class Extras_Widget extends Widget_Base {

	protected $_is_edit_mode = false;

	public function __construct( $data = [], $args = null ) {
		parent::__construct( $data, $args );

		$this->_is_edit_mode = \Elementor\Plugin::instance()->editor->is_edit_mode();
	}

	public function add_helper_render_attribute( $key, $name = '' ) {

		if ( ! $this->_is_edit_mode )
			return;

		$this->add_render_attribute( $key, [
			'data-ee-helper' 	=> $name,
			'class'				=> 'ee-editor-helper',
		] );
	}

	/**
	 * Method for setting widget dependancy on Elementor Pro plugin
	 *
	 * When returning false it doesn't allow the widget to be registered
	 *
	 * @access public
	 * @since 1.6.0
	 * @return bool
	 */
	public static function requires_elementor_pro() {
		return false;
	}

}
