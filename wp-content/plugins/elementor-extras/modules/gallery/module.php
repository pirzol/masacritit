<?php
namespace ElementorExtras\Modules\Gallery;

use ElementorExtras\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Module extends Module_Base {

	public function get_name() {
		return 'gallery';
	}

	public function get_widgets() {
		return [
			'Gallery',
			'Gallery_Slider',
		];
	}
}
