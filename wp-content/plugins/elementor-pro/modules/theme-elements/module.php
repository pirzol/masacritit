<?php
namespace ElementorPro\Modules\ThemeElements;

use ElementorPro\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Module extends Module_Base {

	public function get_name() {
		return 'theme-elements';
	}

	public function get_widgets() {
		$widgets = [
			'Search_Form',
			//'Post_Navigation',
		];

		//if ( $this->is_yoast_seo_active() ) {
		//	$widgets[] = 'Breadcrumbs';
		//}

		return $widgets;
	}

	public function is_yoast_seo_active(){
		return function_exists( 'yoast_breadcrumb' );
	}
}
