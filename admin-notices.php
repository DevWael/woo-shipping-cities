<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
//display admin notices and errors
add_action( 'admin_notices', 'dw_shipping_cities_admin_notice' );
function dw_shipping_cities_admin_notice() {
	if ( ! class_exists( 'woocommerce' ) ) {
		?>
        <div class="notice notice-warning is-dismissible">
            <p><?php esc_html_e( 'Woo Shipping Cities requires Woocommerce plugin to be installed', 'dw' ); ?></p>
        </div>
		<?php
	}

	$screen = get_current_screen();
	if ( $screen->id === 'woocommerce_page_dw-shipping-cities-page' ) {
		if ( isset( $_GET['saving'] ) ) {
			if ( $_GET['saving'] === 'true' ) { ?>
                <div class="notice notice-success is-dismissible">
                    <p><?php esc_html_e( 'All Changes Saved.', 'dw' ); ?></p>
                </div>
				<?php
			}
			if ( $_GET['saving'] === 'not_allowed' ) { ?>
                <div class="notice notice-error is-dismissible">
                    <p><?php esc_html_e( 'You are not allowed to do anything here.', 'dw' ); ?></p>
                </div>
				<?php
			}
			if ( $_GET['saving'] === 'security_failed' ) { ?>
                <div class="notice notice-error is-dismissible">
                    <p><?php esc_html_e( 'Failed to check your user info, please try again!.', 'dw' ); ?></p>
                </div>
				<?php
			}
			if ( $_GET['saving'] === 'failed' ) { ?>
                <div class="notice notice-warning is-dismissible">
                    <p><?php esc_html_e( 'Failed to save data, please try again!.', 'dw' ); ?></p>
                </div>
				<?php
			}
		}
	}
}
