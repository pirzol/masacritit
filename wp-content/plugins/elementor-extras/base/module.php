<?php

namespace ElementorExtras\Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Module
 *
 * Base 
 *
 * @since 1.6.0
 */
abstract class Module_Base {

	/**
	 * @var \ReflectionClass
	 */
	private $reflection;

	/**
	 * @var Module_Base
	 */
	protected static $_instances = [];

	abstract public function get_name();

	public static function class_name() {
		return get_called_class();
	}

	/**
	 * @return static
	 */
	public static function instance() {
		if ( empty( static::$_instances[ static::class_name() ] ) ) {
			static::$_instances[ static::class_name() ] = new static();
		}

		return static::$_instances[ static::class_name() ];
	}

	public function __construct() {
		$this->reflection = new \ReflectionClass( $this );

		add_action( 'elementor/widgets/widgets_registered', [ $this, 'init_widgets' ] );
	}

	public function init_widgets() {
		$widget_manager = \Elementor\Plugin::instance()->widgets_manager;

		foreach ( $this->get_widgets() as $widget ) {

			$class_name = $this->reflection->getNamespaceName() . '\Widgets\\' . $widget;

			if ( $class_name::requires_elementor_pro() && ! is_elementor_pro_active() ) {
				continue;
			}

			$module_filename = $this->get_name();
			$widget_filename = str_replace( '_', '-', strtolower( $widget ) );

			$widget_filename = ELEMENTOR_EXTRAS_PATH . "includes/modules/{$module_filename}/widgets/{$widget_filename}.php";

			// require( $widget_filename );

			$widget_manager->register_widget_type( new $class_name() );
		}
	}

	public function get_widgets() {
		return [];
	}

	/**
	 * Method for setting module dependancy on Elementor Pro plugin
	 *
	 * When returning false it doesn't allow the module to be registered
	 *
	 * @access public
	 * @since 1.6.0
	 * @return bool
	 */
	public static function requires_elementor_pro() {
		return false;
	}
}
