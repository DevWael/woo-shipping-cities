<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

//add city and district fields to checkout form
add_filter( 'woocommerce_checkout_fields', 'dw_city_district_fields' );
function dw_city_district_fields( $fields ) {
	//city field
	$fields['billing']['billing_city_select'] = array(
		'type'              => 'select',
		'priority'          => 41,
		'custom_attributes' => [
			'disabled' => true
		],
		'class'             => array( 'dw_billing_city_select' ),
		'label'             => esc_html__( 'City', 'woocommerce' ),
		'options'           => [
			'' => esc_html__( 'Select City', 'dw' ),
		]
	);

	//district field
	$fields['billing']['billing_district_select'] = array(
		'type'              => 'select',
		'priority'          => 42,
		'custom_attributes' => [
			'disabled' => true
		],
		'class'             => array( 'dw_billing_district_select' ),
		'label'             => esc_html__( 'District', 'woocommerce' ),
		'options'           => [
			'' => esc_html__( 'Select District', 'dw' ),
		]
	);

	return $fields;
}

//saving selected fields to order so we can display them in the order page
add_action( 'woocommerce_checkout_update_order_meta', 'dw_update_order_meta', 10, 1 );
function dw_update_order_meta( $order_id ) {
	//city field
	if ( isset( $_POST['billing_city_select'] ) && ! empty( $_POST['billing_city_select'] ) ) {
		update_post_meta( $order_id, 'dw_billing_city_select', sanitize_text_field( $_POST['billing_city_select'] ) );
	}
	//district field
	if ( isset( $_POST['billing_district_select'] ) && ! empty( $_POST['billing_district_select'] ) ) {
		update_post_meta( $order_id, 'billing_district_select', sanitize_text_field( $_POST['billing_district_select'] ) );
	}
}

//display the saved city and district into order admin dashboard
add_action( 'woocommerce_admin_order_data_after_billing_address', 'dw_display_admin_order_meta', 10, 1 );
function dw_display_admin_order_meta( $order ) {
	//city field
	if ( $city = get_post_meta( $order->get_id(), 'dw_billing_city_select', true ) ) {
		echo '<p><strong>' . esc_html__( 'City', 'dw' ) . ':</strong> ' . esc_html( $city ) . '</p>';
	}
	//district field
	if ( $district = get_post_meta( $order->get_id(), 'billing_district_select', true ) ) {
		echo '<p><strong>' . esc_html__( 'District', 'dw' ) . ':</strong> ' . esc_html( $district ) . '</p>';
	}
}