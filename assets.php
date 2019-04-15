<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

//plugin assets url
define( 'DW_SHIPPING_CITIES_ADMIN_ASSETS', plugin_dir_url( __FILE__ ) . 'assets/admin/' );
define( 'DW_SHIPPING_CITIES_ASSETS', plugin_dir_url( __FILE__ ) . 'assets/front-end/' );

/**
 * Add Plugin admin Styles & Scripts
 */
add_action( 'admin_enqueue_scripts', 'dw_shipping_cities_admin_scripts' );
function dw_shipping_cities_admin_scripts() {
	$screen = get_current_screen();
	if ( $screen->id === 'woocommerce_page_dw-shipping-cities-page' ) {
		wp_enqueue_style( 'dw-shipping-cities-css', DW_SHIPPING_CITIES_ADMIN_ASSETS . 'css/style.css', false, DW_SHIPPING_CITIES_VERSION );
		wp_enqueue_script( 'jquery-ui-sortable' );
		wp_enqueue_script( 'dw-shipping-cities', DW_SHIPPING_CITIES_ADMIN_ASSETS . 'js/main.js', array( 'jquery' ), DW_SHIPPING_CITIES_VERSION, true );
	}
}

/**
 * Add Plugin  Styles & Scripts
 */
add_action( 'wp_enqueue_scripts', 'dw_shipping_cities_scripts' );
function dw_shipping_cities_scripts() {
	wp_enqueue_style( 'dw-shipping-css', DW_SHIPPING_CITIES_ASSETS . 'css/style.css', false, DW_SHIPPING_CITIES_VERSION );
	wp_enqueue_script( 'dw-shipping-js', DW_SHIPPING_CITIES_ASSETS . 'js/main.js', array( 'jquery' ), DW_SHIPPING_CITIES_VERSION, true );
	wp_localize_script(
		'dw-shipping-js',
		'dw_shipping',
		[
			'ajaxurl'    => admin_url( 'admin-ajax.php' ),
			'ajax_nonce' => wp_create_nonce( 'security_check' ),
		]
	);
}