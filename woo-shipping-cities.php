<?php
/**
 * Plugin Name: Woo Shipping Cities (Beta)
 * Plugin URI: https://github.com/DevWael/woo-shipping-cities
 * Description: A WooCommerce toolkit that helps you set any district shipping fees.
 * Version: 0.1
 * Author: Ahmad Wael
 * Author URI: https://bbioon.com
 * Text Domain: dw
 * Domain Path: /languages/
 *
 * @package dw
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

//Plugin Activation indicator
if ( ! defined( 'DW_SHIPPING_CITIES' ) ) {
	define( 'DW_SHIPPING_CITIES', true );
}
//plugin version
if ( ! defined( 'DW_SHIPPING_CITIES_VERSION' ) ) {
	define( 'DW_SHIPPING_CITIES_VERSION', 0.1 );
}

if ( ! defined( 'DW_SHIPPING_CITIES_DATA' ) ) {
	define( 'DW_SHIPPING_CITIES_DATA', 'dw_shipping_storage' );//storage key
}

include plugin_dir_path( __FILE__ ) . 'assets.php';//plugin helper functionalities
include plugin_dir_path( __FILE__ ) . 'hooks.php';//plugin css and js files
include plugin_dir_path( __FILE__ ) . 'requests.php';//all requests functionalities
include plugin_dir_path( __FILE__ ) . 'admin-notices.php';//plugin admin notices

//Registering plugin admin setting page template
add_action( 'admin_menu', 'dw_shipping_cities_page', 99 );
function dw_shipping_cities_page() {
	add_submenu_page( 'woocommerce', esc_html__( 'Shipping Cities Fees', 'dw' ), esc_html__( 'Shipping Cities Fees', 'dw' ), 'manage_options', 'dw-shipping-cities-page', 'dw_shipping_cities_callback' );
}

//Plugin admin setting page template
function dw_shipping_cities_callback() {
	include plugin_dir_path( __FILE__ ) . 'admin.php';
}

//load textdomain for WP localization system
add_action( 'init', 'dw_load_textdomain' );
function dw_load_textdomain() {
	load_plugin_textdomain( 'dw', false, basename( dirname( __FILE__ ) ) . '/languages' );
}

//clean the database after uninstalling this plugin
register_uninstall_hook( __FILE__, 'dw_clean_uninstall' );
function dw_clean_uninstall() {
	delete_option( 'dw_shipping_storage' );
}