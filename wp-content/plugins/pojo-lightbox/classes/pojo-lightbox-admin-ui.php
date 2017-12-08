<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

final class Pojo_Lightbox_Admin_UI {

	public function plugin_action_links( $links ) {
		$settings_link = sprintf( '<a href="%s" target="_blank">%s</a>', 'https://github.com/pojome/pojo-lightbox', __( 'GitHub', 'pojo-lightbox' ) );
		array_unshift( $links, $settings_link );

		$settings_link = sprintf( '<a href="%s">%s</a>', admin_url( 'admin.php?page=pojo-lightbox' ), __( 'Settings', 'pojo-lightbox' ) );
		array_unshift( $links, $settings_link );

		return $links;
	}
	
	public function __construct() {
		add_filter( 'plugin_action_links_' . POJO_LIGHTBOX_BASE, array( &$this, 'plugin_action_links' ) );
	}
	
}