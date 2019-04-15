(function ($) {
    'use strict';

    let billing_country = $('#billing_country'),
        billing_city = $('#billing_city_select'),
        billing_district = $('#billing_district_select');

    billing_country.on('change', function () {
        billing_city.empty().prop('disabled', true);
        billing_district.empty().prop('disabled', true);
        $.ajax({
            type: "post",
            dataType: "json",
            url: dw_shipping.ajaxurl,
            data: {
                action: "dw_shipping_cities",
                required: "cities",
                id: $(this).val(),
                nonce: dw_shipping.ajax_nonce
            },
            success: function (response) {
                if (response.success) {
                    let new_data = response.data.data_set;
                    $.each(new_data, function (key, val) {
                        billing_city.append('<option value="' + val.city_name[0] + '">' + val.city_name[1] + '</option>').prop('disabled', false);
                    })
                }
            }
        });
    });
    if (billing_country.val() !== '') {
        billing_country.trigger('change');
    }

    billing_city.on('change', function () {
        billing_district.empty().prop('disabled', true);
        $.ajax({
            type: "post",
            dataType: "json",
            url: dw_shipping.ajaxurl,
            data: {
                action: "dw_shipping_cities",
                required: "districts",
                country_id: billing_country.val(),
                id: $(this).val(),
                nonce: dw_shipping.ajax_nonce
            },
            success: function (response) {
                if (response.success) {
                    let new_data = response.data.data_set;
                    $.each(new_data, function (key, val) {
                        billing_district.append('<option value="' + val.district_name[0] + '">' + val.district_name[1] + '</option>').prop('disabled', false);
                    });
                }
            }
        });
    });

    billing_district.on('change', function () {
        $('body').trigger('update_checkout');
    });

})(jQuery);