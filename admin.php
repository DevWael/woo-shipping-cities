<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
global $woocommerce;
$countries_obj = new WC_Countries();
$countries     = $countries_obj->__get( 'countries' );
$cities_data   = json_decode( get_option( DW_SHIPPING_CITIES_DATA ), true );
if ( ! current_user_can( 'manage_options' ) ) {
	return false;
}
?>
<div class="wrap">
    <h1><?php esc_html_e( 'Woo Shipping Cities Fees', 'dw' ) ?></h1>
	<?php settings_errors(); ?>
    <br>
    <form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post">
        <div class="dw-container">
            <div class="dw-country-box">
                <button type="button" class="clone-box-button clone-first-depth">+</button>
				<?php if ( ! empty( $cities_data ) ) {
					foreach ( $cities_data as $cities_data_key => $cities_data_val ) {
						if ( $cities_data_key == 'name' ) {
							continue;
						}
						?>
                        <div class="dw-data dw-data-1">
                            <h2>
								<?php esc_html_e( 'Select Country', 'dw' ) ?>
                            </h2>
                            <select name="countries_data[name][]" class="dw-country-select">
								<?php
								foreach ( $countries as $key => $val ) {
									?>
                                    <option value="<?php echo esc_attr( $key ) ?>"
										<?php selected( $cities_data_key, $key ) ?>>
										<?php echo esc_html( $val ); ?>
                                    </option>
								<?php } ?>
                            </select>
                            <div class="dw-city-box">
                                <button type="button" class="clone-box-button clone-second-depth">+</button>
								<?php if ( ! empty( $cities_data[ $cities_data_key ] ) ) {
									foreach ( $cities_data[ $cities_data_key ] as $cities_key => $cities_val ) {
										if ( $cities_key == 'cities_names' ) {
											continue;
										}
										?>
                                        <div class="dw-data dw-data-2">
                                            <input type="text"
                                                   name="countries_data[<?php echo esc_attr( $cities_data_key ); ?>][cities_names][]"
                                                   class="dw-city-name"
                                                   value="<?php echo esc_attr( $cities_key ); ?>"
                                                   placeholder="<?php esc_attr_e( 'City Name', 'dw' ) ?>">
                                            <div class="dw-district-box">
                                                <button type="button" class="clone-box-button clone-third-depth">+
                                                </button>
												<?php
												if ( ! empty( $cities_data[ $cities_data_key ][ $cities_key ] ) ) {
													foreach (
														$cities_data[ $cities_data_key ][ $cities_key ]['district_name'] as
														$district_kay => $district_val
													) {
														?>
                                                        <div class="dw-data dw-data-3">
                                                            <input type="text"
                                                                   name="countries_data[<?php echo $cities_data_key; ?>][<?php echo $cities_key; ?>][district_name][]"
                                                                   class="dw-district-name"
                                                                   value="<?php echo esc_attr( $district_val ); ?>"
                                                                   placeholder="<?php esc_attr_e( 'District Name', 'dw' ) ?>">
                                                            <input type="number"
                                                                   name="countries_data[<?php echo esc_attr( $cities_data_key ); ?>][<?php echo esc_attr( $cities_key ); ?>][fees][]"
                                                                   class="dw-additional-fees"
                                                                   placeholder="<?php esc_attr_e( 'Additional Fees', 'dw' ) ?>"
                                                                   value="<?php echo esc_attr( $cities_data[ $cities_data_key ][ $cities_key ]['fees'][ $district_kay ] ); ?>"
                                                                   min="0">
                                                            <button type="button" class="remove-box-button">x</button>
                                                        </div>
														<?php
													}
												}
												?>
                                            </div>
                                            <button type="button" class="remove-box-button">x</button>
                                        </div>
										<?php
									}
								}
								?>
                            </div>
                            <button type="button" class="remove-box-button">x</button>
                        </div>
					<?php }
				} else { ?>
                    <div class="dw-data dw-data-1">
						<?php
						echo '<h2>' . __( 'Select Country', 'dw' ) . '</h2>';
						?>
                        <select name="countries_data[name][]" class="dw-country-select">
							<?php
							foreach ( $countries as $key => $val ) {
								?>
                                <option value="<?php echo esc_attr( $key ) ?>">
									<?php echo esc_html( $val ); ?>
                                </option>
							<?php } ?>
                        </select>
                        <div class="dw-city-box">
                            <button type="button" class="clone-box-button clone-second-depth">+</button>
                            <div class="dw-data dw-data-2">
                                <input type="text" name="" class="dw-city-name"
                                       placeholder="<?php esc_attr_e( 'City Name', 'dw' ) ?>">
                                <div class="dw-district-box">
                                    <button type="button" class="clone-box-button clone-third-depth">+</button>
                                    <div class="dw-data dw-data-3">
                                        <input type="text" name="" class="dw-district-name"
                                               placeholder="<?php esc_attr_e( 'District Name', 'dw' ) ?>">
                                        <input type="number" name="" class="dw-additional-fees" min="0"
                                               placeholder="<?php esc_attr_e( 'Additional Fees', 'dw' ) ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
				<?php } ?>
            </div>
			<?php wp_nonce_field( 'shipping_cities_fees', 'security_check' ) ?>
            <input type="hidden" name="action" value="shipping_cities_fees">
            <div class="clearfix"></div>
            <button type="submit" class="button button-primary"><?php esc_html_e( 'Submit', 'dw' ) ?></button>
        </div>
    </form>
</div>

<!-- Dummy Fields to clone by jquery -->
<div class="dummy-fields hidden">
    <div class="dw-first-depth">
        <div class="dw-data dw-data-1">
			<?php
			echo '<h2>' . __( 'Select Country', 'dw' ) . '</h2>';
			?>
            <select name="countries_data[name][]" class="dw-country-select">
				<?php
				foreach ( $countries as $key => $val ) {
					?>
                    <option value="<?php echo esc_attr( $key ) ?>">
						<?php echo esc_html( $val ); ?>
                    </option>
				<?php } ?>
            </select>
            <div class="dw-city-box">
                <button type="button" class="clone-box-button clone-second-depth">+</button>
                <div class="dw-data dw-data-2">
                    <input type="text" name="" class="dw-city-name"
                           placeholder="<?php esc_attr_e( 'City Name', 'dw' ) ?>">
                    <div class="dw-district-box">
                        <button type="button" class="clone-box-button clone-third-depth">+</button>
                        <div class="dw-data dw-data-3">
                            <input type="text" name="" class="dw-district-name"
                                   placeholder="<?php esc_attr_e( 'District Name', 'dw' ) ?>">
                            <input type="number" name="" class="dw-additional-fees"
                                   placeholder="<?php esc_attr_e( 'Additional Fees', 'dw' ) ?>"
                                   min="0">
                            <button type="button" class="remove-box-button">x</button>
                        </div>
                    </div>
                    <button type="button" class="remove-box-button">x</button>
                </div>
            </div>
            <button type="button" class="remove-box-button">x</button>
        </div>
    </div>
    <div class="dw-second-depth">
        <div class="dw-data dw-data-2">
            <input type="text" name="" class="dw-city-name"
                   placeholder="<?php esc_attr_e( 'City Name', 'dw' ) ?>">
            <div class="dw-district-box">
                <button type="button" class="clone-box-button clone-third-depth">+</button>
                <div class="dw-data dw-data-3">
                    <input type="text" name="" class="dw-district-name"
                           placeholder="<?php esc_attr_e( 'District Name', 'dw' ) ?>">
                    <input type="number" name="" class="dw-additional-fees"
                           placeholder="<?php esc_attr_e( 'Additional Fees', 'dw' ) ?>" min="0">
                    <button type="button" class="remove-box-button">x</button>
                </div>
            </div>
            <button type="button" class="remove-box-button">x</button>
        </div>
    </div>
    <div class="dw-third-depth">
        <div class="dw-data dw-data-3">
            <input type="text" name="" class="dw-district-name"
                   placeholder="<?php esc_attr_e( 'District Name', 'dw' ) ?>">
            <input type="number" name="" class="dw-additional-fees"
                   placeholder="<?php esc_attr_e( 'Additional Fees', 'dw' ) ?>" min="0">
            <button type="button" class="remove-box-button">x</button>
        </div>
    </div>
</div>