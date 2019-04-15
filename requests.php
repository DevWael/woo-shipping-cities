<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

//saving plugin settings
add_action( 'admin_post_shipping_cities_fees', 'dw_shipping_cities_process' );
function dw_shipping_cities_process() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_safe_redirect( admin_url( '/admin.php?page=dw-shipping-cities-page&saving=not_allowed' ) );
		exit;
	}
	//check (must be a POST request)
	if ( strtoupper( $_SERVER['REQUEST_METHOD'] ) === 'POST' ) {
		if (
			! isset( $_POST['security_check'] )
			|| ! wp_verify_nonce( $_POST['security_check'], 'shipping_cities_fees' )
		) {
			//not verified user
			wp_safe_redirect( admin_url( '/admin.php?page=dw-shipping-cities-page&saving=security_failed' ) );
			exit;
		} else {
			if ( isset( $_POST['countries_data'] ) && ! empty( $_POST['countries_data'] ) && is_array( $_POST['countries_data'] ) ) {
				//successful saving process
				update_option( DW_SHIPPING_CITIES_DATA, json_encode( $_POST['countries_data'] ) );
				wp_redirect( admin_url( '/admin.php?page=dw-shipping-cities-page&saving=true' ) );
				exit;
			} else {
				//failed to save
				wp_safe_redirect( admin_url( '/admin.php?page=dw-shipping-cities-page&saving=failed' ) );
				exit;
			}
		}
	}
}

//receive the selected country, city, district data to set the select options
add_action( 'wp_ajax_dw_shipping_cities', 'dw_ajax_request_zones' );
add_action( 'wp_ajax_nopriv_dw_shipping_cities', 'dw_ajax_request_zones' );
function dw_ajax_request_zones() {
	check_ajax_referer( 'security_check', 'nonce' );
	$cities_data = json_decode( get_option( DW_SHIPPING_CITIES_DATA ), true );
	$response    = [];
	if ( ! empty( $cities_data ) ) {
		if ( isset( $_POST['id'] ) && isset( $_POST['required'] ) && ! empty( $_POST['required'] ) ) {
			if ( $_POST['required'] == 'cities' ) {
				$country_id = $_POST['id'];
				if ( in_array( $country_id, $cities_data['name'] ) ) {
					$cities     = $cities_data[ $country_id ]['cities_names'];
					$response[] = [
						'city_name' => [ '', esc_html__( 'Select City', 'dw' ) ]
					];
					foreach ( $cities as $city ) {
						$response[] = [
							'city_name' => [
								//option value -- more security and validations can be done here
								esc_html( $city ),
								//option name
								esc_html( $city )
							]
						];
					}
					wp_send_json_success( [ 'data_set' => $response ] );
				}
			} elseif ( $_POST['required'] == 'districts' ) {
				if ( isset( $_POST['country_id'] ) && ! empty( $_POST['country_id'] ) ) {
					$country_id = $_POST['country_id'];
					$city_id    = $_POST['id'];
					if ( in_array( $country_id, $cities_data['name'] ) ) {
						if ( in_array( $city_id, $cities_data[ $country_id ]['cities_names'] ) ) {
							if ( ! empty( $cities_data[ $country_id ][ $city_id ]['district_name'] ) ) {
								$districts  = $cities_data[ $country_id ][ $city_id ]['district_name'];
								$response[] = [
									'district_name' => [ '', esc_html__( 'Select District', 'dw' ) ]
								];
								foreach ( $districts as $district_key => $district_val ) {
									$response[] = [
										'district_name' => [
											//(option value) more security and validations can be done here
											esc_html( $district_key ),
											//option name
											esc_html( $district_val )
										]
									];
								}
								wp_send_json_success( [ 'data_set' => $response ] );
							}
						}
					}
				} else {
					//error not valid data
				}
			}
		} else {

		}
	}
}

//adding new fees to the totals depending on the settings
add_action( 'woocommerce_cart_calculate_fees', 'dw_add_shipping_cities_fees' );
function dw_add_shipping_cities_fees() {
	global $woocommerce;
	if ( ! $_POST || ( is_admin() && ! is_ajax() ) ) {
		return;
	}
	//getting user selected data
	if ( isset( $_POST['post_data'] ) ) {
		parse_str( $_POST['post_data'], $post_data );
	} else {
		$post_data = $_POST;
	}
	$district_name = $fees = '';
	//getting selected country
	$country = $woocommerce->customer->get_shipping_country();
	if ( isset( $post_data['billing_district_select'] ) ) {
		//getting saved plugin data
		$cities_data = json_decode( get_option( DW_SHIPPING_CITIES_DATA ), true );
		if ( isset( $cities_data[ $country ]
			[ $post_data['billing_city_select'] ]['fees'][ $post_data['billing_district_select'] ] ) ) {

			if ( isset( $cities_data[ $country ]
				[ $post_data['billing_city_select'] ]['district_name'][ $post_data['billing_district_select'] ] ) ) {
				$district_name = $cities_data[ $country ]
				[ $post_data['billing_city_select'] ]['district_name'][ $post_data['billing_district_select'] ];
			}
			$fees = $cities_data[ $country ][ $post_data['billing_city_select'] ]['fees'][ $post_data['billing_district_select'] ];
			if ( ! empty( $fees ) && is_numeric( $fees ) ) {
				//here we set the new fees
				WC()->cart->add_fee(
					esc_html__( 'Shipping to', 'dw' ) . ' ' . esc_html( $district_name ), $fees
				);
			}
		}
	}
}