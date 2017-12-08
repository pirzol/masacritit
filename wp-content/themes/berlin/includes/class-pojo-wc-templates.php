<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Pojo_WC_Templates {

	public function init() {
		remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
		remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );

		add_action( 'woocommerce_archive_description', array( &$this, 'show_breadcrumbs' ) );
		add_action( 'woocommerce_single_product_summary', array( &$this, 'show_breadcrumbs' ), 3 );
	}

	public function show_breadcrumbs() {
		if ( po_breadcrumbs_need_to_show() ) {
			pojo_breadcrumbs();
			echo '<div class="clearfix"></div>';
		}
	}
	
	public function __construct() {
		add_action( 'init', array( &$this, 'init' ), 500 );
	}
	
}
new Pojo_WC_Templates();