<?php
namespace ElementorExtras;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Extensions_Manager {

	const SECTIONS 				= 'sections';
	const PORTFOLIO 			= 'portfolio';
	const STICKY 				= 'sticky';
	const PARALLAX 				= 'parallax';
	const PARALLAX_BACKGROUND 	= 'parallax-background';

	private $_extensions = null;

	/**
	 * @since 0.1.0
	 */
	public function register_extensions() {

		$this->_extensions = [];

		$available_extensions = [
			self::SECTIONS,
			self::PORTFOLIO,
			self::STICKY,
			self::PARALLAX,
			self::PARALLAX_BACKGROUND,
		];

		foreach ( $available_extensions as $extension_id ) {
			$extension_filename = str_replace( '_', '-', $extension_id );

			$extension_filename = ELEMENTOR_EXTRAS_PATH . "extensions/{$extension_filename}.php";

			require( $extension_filename );

			$class_name = str_replace( '-', '_', $extension_id );
			$class_name = 'ElementorExtras\Extensions\Extension_' . ucwords( $class_name );

			$this->register_extension( $extension_id, new $class_name() );
		}

		do_action( 'elementor_extras/extensions/extensions_registered', $this );
	}

	/**
	 * @since 0.1.0
	 *
	 * @param $extension_id
	 * @param Extension_Base $extension_instance
	 */
	public function register_extension( $extension_id, Base\Extension_Base $extension_instance ) {
		$this->_extensions[ $extension_id ] = $extension_instance;
	}

	/**
	 * @param $extension_id
	 *
	 * @since 0.1.0
	 * @return bool
	 */
	public function unregister_extension( $extension_id ) {
		if ( ! isset( $this->_extensions[ $extension_id ] ) ) {
			return false;
		}

		unset( $this->_extensions[ $extension_id ] );

		return true;
	}

	/**
	 * @since 0.1.0
	 * @return Extension_Base[]
	 */
	public function get_extensions() {
		if ( null === $this->_extensions ) {
			$this->register_extensions();
		}

		return $this->_extensions;
	}

	/**
	 * @since 0.1.0
	 * @param $extension_id
	 *
	 * @return bool|\ElementorExtras\Extension_Base
	 */
	public function get_extension( $extension_id ) {
		$extensions = $this->get_extensions();

		return isset( $extensions[ $extension_id ] ) ? $extensions[ $extension_id ] : false;
	}

	private function require_files() {
		require( ELEMENTOR_EXTRAS_PATH . 'base/extension.php' );
	}

	public function __construct() {
		$this->require_files();
		$this->register_extensions();
	}
}
