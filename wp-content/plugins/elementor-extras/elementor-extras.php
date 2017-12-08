<?php
/**
 * Plugin Name: 	Elementor Extras
 * Plugin URI: 		https://shop.namogo.com/product/elementor-extras/
 * Description: 	Elementor Extras is a premium Wordpress plugin for Elementor, extending its capability with seriously useful new widgets and extensions
 * Version: 		1.6.1
 * Author: 			Namogo
 * Author URI: 		https://shop.namogo.com/
 * Text Domain: 	elementor-extras
 * Domain Path: 	/languages
 * License: 		GNU General Public License v2 or later
 * License URI: 	http://www.gnu.org/licenses/gpl-2.0.html
 * 
 * This plugin is free software: you can redistribute it and/or modify it under the terms
 * of the GNU General Public License as published by the Free Software Foundation, either
 * version 2 of the License or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License along with this program. If not, see http://www.gnu.org/licenses/
 * You can contact us at office@namogo.com
 * 
 * Elementor Extras incorporates code from:

 * — jquery-circle-progress v1.2.2, Copyright Rostyslav Bryzgunov Licenses: MIT Source: link http://kottenator.github.io/jquery-circle-progress/
 * — jQuery appear plugin v0.3.6, Copyright 2012 Andrey Sidorov Licenses: MIT Source: link https://github.com/morr/jquery.appear/
 * — LongShadow jQuery Plugin v1.1.0, Copyright 2013 - 2016 Dang Van Thanh Licenses: MIT Source: link git://github.com/dangvanthanh/jquery.longShadow.git
 * — Sticky-kit v1.1.3, Copyright 2015 Leaf Corcoran Licenses: MIT Source: link http://leafo.net
 * — jQuery Mobile v1.4.3, Copyright 2010, 2014 jQuery Foundation, Inc. Licenses: jquery.org/license
 * — jquery-visible, Copyright 2012, Digital Fusion, License: http://teamdf.com/jquery-plugins/license/ Source: http://teamdf.com/jquery-plugins/license/
 * — Parallax Background v1.2, by Eren Suleymanoglu Licenses: MIT Source: link https://github.com/erensuleymanoglu/parallax-background
 * — TableSorter v2.0.5b, Copyright 2007 Christian Bach Licenses: Dual licensed under the MIT and GPL licenses Source: link http://tablesorter.com
 * — Isotope PACKAGED v3.0.4, Copyright 2017 Metafizzy License: GPLv3 Source: link http://isotope.metafizzy.co
 * — Infinite Scroll PACKAGED v3.0.2, Copyright 2017 Metafizzy License: GPLv3 Source: link https://infinite-scroll.com
 * — javascript-detect-element-resize 0.5.3 Copyright (c) 2013 Sebastián Décima License: MIT Source: link https://github.com/sdecima/javascript-detect-element-resize
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

define( 'ELEMENTOR_EXTRAS__FILE__', 						__FILE__ );
define( 'ELEMENTOR_EXTRAS_PLUGIN_BASE', 					plugin_basename( ELEMENTOR_EXTRAS__FILE__ ) );
define( 'ELEMENTOR_EXTRAS_URL', 							plugins_url( '/', ELEMENTOR_EXTRAS__FILE__ ) );
define( 'ELEMENTOR_EXTRAS_PATH', 							plugin_dir_path( ELEMENTOR_EXTRAS__FILE__ ) );
define( 'ELEMENTOR_EXTRAS_ASSETS_URL', 						ELEMENTOR_EXTRAS_URL . 'assets/' );
define( 'ELEMENTOR_EXTRAS_VERSION', 						'1.6.1' );
define( 'ELEMENTOR_EXTRAS_ELEMENTOR_VERSION_REQUIRED', 		'1.8.0' );
define( 'ELEMENTOR_EXTRAS_ELEMENTOR_PRO_VERSION_REQUIRED', 	'1.6.0' );
define( 'ELEMENTOR_EXTRAS_PHP_VERSION_REQUIRED', 			'5.0' );
define( 'ELEMENTOR_EXTRAS_TEXTDOMAIN', 						'elementor-extras' );

// Licensing
define( 'NAMOGO_STORE_URL', 		'https://shop.namogo.com' );
define( 'NAMOGO_SL_ITEM_ID',		'elementor_extras' );
define( 'NAMOGO_SL_ITEM_SLUG', 		'elementor-extras' );
define( 'NAMOGO_SL_ITEM_NAME', 		'Elementor Extras' );

/**
 * Load Elementor Extras
 *
 * Load the plugin after Elementor (and other plugins) are loaded.
 *
 * @since 0.1.0
 */
function elementor_extras_load() {

	// Load localization file
	load_plugin_textdomain( 'elementor-extras', false, dirname( ELEMENTOR_EXTRAS_PLUGIN_BASE ) . '/languages/' );

	// Includes

	elementor_extras_include( 'includes/licensing.php' );
	elementor_extras_include( 'includes/tracker.php' );
	elementor_extras_include( 'includes/plugin.php' );

	if( is_admin() ) {
		elementor_extras_include( 'admin/class-admin-settings-info.php' );
		elementor_extras_include( 'admin/class-admin-notices-dismissal.php' );
	}

	// Notice if the Elementor is not active
	if ( ! did_action( 'elementor/loaded' ) ) {
		add_action( 'admin_notices', 'elementor_extras_fail_load' );
		return;
	}

	// Check Elementor version required
	if ( ! version_compare( ELEMENTOR_VERSION, ELEMENTOR_EXTRAS_ELEMENTOR_VERSION_REQUIRED, '>=' ) ) {

		add_action( 'admin_notices', 	'elementor_extras_fail_load_out_of_date' );
		add_action( 'admin_init', 		'elementor_extras_deactivate' );
		return;
	}

	// Check Elementor Pro version required
	if ( is_elementor_pro_active() ) {
		if ( ! version_compare( ELEMENTOR_PRO_VERSION, ELEMENTOR_EXTRAS_ELEMENTOR_PRO_VERSION_REQUIRED, '>=' ) ) {

			add_action( 'admin_notices', 	'elementor_pro_extras_fail_load_out_of_date' );
			return;
		}
	}

	// Check for required PHP version
	if ( version_compare( PHP_VERSION, ELEMENTOR_EXTRAS_PHP_VERSION_REQUIRED, '<' ) ) {

		add_action( 'admin_notices', 	'elementor_extras_php_fail' );
		add_action( 'admin_init', 		'elementor_extras_deactivate' );
		return;
	}

	add_action( 'admin_init', 'elementor_extras_updater' );
	// add_action( 'admin_init', 'elementor_extras_info_redirect' );
}
add_action( 'plugins_loaded', 'elementor_extras_load' );
register_activation_hook( ELEMENTOR_EXTRAS__FILE__, 'elementor_extras_activate' );

/**
 * Wrapper for including files
 *
 * @since 1.1.3
 */
function elementor_extras_include( $file ) {

	$path = elementor_extras_get_path( $file );

	if ( file_exists( $path ) ) {
		include_once( $path );
	}
}

/**
 * Returns the path to a file relative to our plugin
 *
 * @since 1.1.3
 */
function elementor_extras_get_path( $path ) {
	
	return ELEMENTOR_EXTRAS_PATH . $path;
	
}

/**
 * Wrapper for including files
 *
 * @since 1.1.3
 */
function elementor_extras_get_view( $path = '', $args = array() ) {
	
	if( substr( $path, -4 ) !== '.php' ) {	
		$path = elementor_extras_get_path( "admin/views/{$path}.php" );
	}

	if( file_exists( $path ) ) {
		extract( $args );
		include( $path );
	}
	
}

/**
 * Handles admin notice for non-active
 * Elementor plugin situations
 *
 * @since 0.1.0
 */
function elementor_extras_fail_load() {
	$class = 'notice notice-error';
	$message = sprintf( __( 'You need %1$s"Elementor"%2$s for the %1$s"Elementor Extras"%2$s plugin to work.', 'elementor-extras' ), '<strong>', '</strong>' );

	$plugin = 'elementor/elementor.php';

	if ( _is_elementor_installed() ) {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		$action_url = wp_nonce_url( 'plugins.php?action=activate&amp;plugin=' . $plugin . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $plugin );
		$button_label = __( 'Activate Elementor', 'elementor-extras' );

	} else {
		if ( ! current_user_can( 'install_plugins' ) ) {
			return;
		}

		$action_url = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=elementor' ), 'install-plugin_elementor' );
		$button_label = __( 'Install Elementor', 'elementor-extras' );
	}

	$button = '<p><a href="' . $action_url . '" class="button-primary">' . $button_label . '</a></p><p></p>';

	printf( '<div class="%1$s"><p>%2$s</p>%3$s</div>', esc_attr( $class ), $message, $button );
}

/**
 * Handles admin notice for outdated Elementor version
 *
 * @since 0.1.0
 */
function elementor_extras_fail_load_out_of_date() {
	$class = 'notice notice-error is-dismissible';
	$message = __( 'Elementor Extras requires at least Elementor version ' . ELEMENTOR_EXTRAS_ELEMENTOR_VERSION_REQUIRED . '. Please update Elementor and re-activate Elementor Extras.', 'elementor-extras' );

	printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
}

/**
 * Handles admin notice for outdated Elementor Pro version
 *
 * @since 1.1.2
 */
function elementor_pro_extras_fail_load_out_of_date() {
	$class = 'notice notice-error is-dismissible';
	$message = __( 'Elementor Extras requires you update Elementor Pro to at least version ' . ELEMENTOR_EXTRAS_ELEMENTOR_PRO_VERSION_REQUIRED . ' to avoid any issues.', 'elementor-extras' );

	printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
}

/**
 * Handles admin notice for PHP version requirements
 *
 * @since 0.1.0
 */
function elementor_extras_php_fail() {
	global $php_version_required;

	$class = 'notice notice-error';
	$message = __( 'Elementor Extras needs at least PHP version ' . ELEMENTOR_EXTRAS_PHP_VERSION_REQUIRED .' to work properly. We deactivated the plugin for now.', 'elementor-extras' );

	printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );

	if ( isset( $_GET['activate'] ) ) 
		unset( $_GET['activate'] );
}

/**
 * Runs code upon activation
 *
 * @since 1.1.3
 */
function elementor_extras_activate() {
	add_option( 'elementor_extras_do_activation_redirect', true );
}

/**
 * Deactivates the plugin
 *
 * @since 0.1.0
 */
function elementor_extras_deactivate() {
	deactivate_plugins( plugin_basename( __FILE__ ) );
}

/**
 * Redirects to info page
 *
 * @since 1.1.3
 */
function elementor_extras_info_redirect(  ) {

	if ( get_option( 'elementor_extras_do_activation_redirect', false ) ) {
    	delete_option( 'elementor_extras_do_activation_redirect' );

		if ( ! isset( $_GET['activate-multi'] ) && version_compare( ELEMENTOR_EXTRAS_VERSION, get_option( '_elementor_extras_was_activated_version' ), '>' ) ) {
			
			update_option( '_elementor_extras_was_activated_version', ELEMENTOR_EXTRAS_VERSION );

			exit ( wp_redirect("admin.php?page=ee-settings-info") );
		}
	}
}

/**
 * Handles updates
 *
 * @since 0.1.0
 */
function elementor_extras_updater() {

	// Require the updater class
	require( __DIR__ . '/includes/updater.php' );

	// Disable SSL verification
	add_filter( 'edd_sl_api_request_verify_ssl', '__return_false' );

	// Setup the updater
	$license = get_option( NAMOGO_SL_ITEM_ID . '_license_key' );
	$updater = new Namogo_Updater( NAMOGO_STORE_URL, __FILE__, array(
			'version' 		=> ELEMENTOR_EXTRAS_VERSION,
			'license' 		=> $license,
			'item_name' 	=> NAMOGO_SL_ITEM_NAME,
			'author' 		=> 'Namogo',
			'beta'			=> false
		)
	);
}

/**
 * Check if Elementor Pro is active
 *
 * @since 1.1.2
 *
 */
if ( ! function_exists( 'is_elementor_pro_active' ) ) {
	function is_elementor_pro_active() {
		return in_array( 
			'elementor-pro/elementor-pro.php', 
			apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) 
		);
	}
}

/**
 * Check if Elementor Pro is active
 *
 * @since 1.6.0
 *
 */
if ( ! function_exists( 'is_woocommerce_active' ) ) {
	function is_woocommerce_active() {
		return in_array( 
			'woocommerce/woocommerce.php', 
			apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) 
		);
	}
}

/**
 * Check if Elementor Pro is installed
 *
 * @since 1.1.2
 *
 * @access public
 */
if ( ! function_exists( '_is_elementor_installed' ) ) {
	function _is_elementor_installed() {
		$path 		= 'elementor/elementor.php';
		$plugins 	= get_plugins();

		return isset( $plugins[ $path ] );
	}
}