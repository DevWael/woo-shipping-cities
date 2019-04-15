(function ($) {
    'use strict';

    $(".dw-city-box").sortable();
    $(".dw-district-box").sortable();
    //adding new country box
    $(document).on('click', '.clone-first-depth', function () {
        $('.dw-first-depth > .dw-data').clone().appendTo($(this).parent());
        $(".dw-city-box").sortable();
        $(".dw-district-box").sortable();
    });

    //setting default field names
    $('.dw-country-box .dw-data-1').each(function () {
        let default_city_val = $(this).find('.dw-country-select').val();
        $(this).find('.dw-city-name').attr('name', 'countries_data[' + default_city_val + '][cities_names][]');
    });
    //adding new city box
    $(document).on('click', '.clone-second-depth', function () {
        let secondClone = $('.dw-second-depth > .dw-data').clone();
        let countryValue = $(this).parents('.dw-data-1').find('.dw-country-select').val();
        secondClone.appendTo($(this).parent());
        secondClone.find('.dw-city-name').attr('name', 'countries_data[' + countryValue + '][cities_names][]');
        $(".dw-city-box").sortable();
        $(".dw-district-box").sortable();
    });
    //set fields (city name) names to be passed as an array on changing country select
    //set fields (districts and fees) names to be passed as an array on changing country select
    $(document).on('change', '.dw-country-select', function () {
        let countryValue = $(this).val();
        $(this).parent().find('.dw-city-name')
            .attr('name', 'countries_data[' + countryValue + '][cities_names][]');
        $(this).parent().find('.dw-data-2').each(function () {
            let city_val = $(this).find('.dw-city-name').val();
            $(this).find('.dw-district-name')
                .attr('name', 'countries_data[' + countryValue + '][' + city_val + '][district_name][]');
            $(this).find('.dw-additional-fees')
                .attr('name', 'countries_data[' + countryValue + '][' + city_val + '][fees][]');
        });
    });

    //adding new district and fees box
    //set fields (districts and fees) names to be passed as an array on adding new dimension
    $(document).on('click', '.clone-third-depth', function () {
        let thirdClone = $('.dw-third-depth > .dw-data').clone();
        thirdClone.appendTo($(this).parent());
        let countryValue = $(this).parents('.dw-data-1').find('.dw-country-select').val(),
            cityValue = $(this).parents('.dw-data-2').find('.dw-city-name').val();
        thirdClone.parent().find('.dw-district-name')
            .attr('name', 'countries_data[' + countryValue + '][' + cityValue + '][district_name][]');
        thirdClone.parent().find('.dw-additional-fees')
            .attr('name', 'countries_data[' + countryValue + '][' + cityValue + '][fees][]');
        $(".dw-city-box").sortable();
        $(".dw-district-box").sortable();
    });

    //set fields (districts and fees) names to be passed as an array on changing city name
    $(document).on('change', '.dw-city-name', function () {
        let countryValue = $(this).parents('.dw-data-1').find('.dw-country-select').val(),
            thisValue = $(this).val().replace(' ', '_').toLowerCase();
        $(this).parent().find('.dw-district-name')
            .attr('name', 'countries_data[' + countryValue + '][' + thisValue + '][district_name][]');
        $(this).parent().find('.dw-additional-fees')
            .attr('name', 'countries_data[' + countryValue + '][' + thisValue + '][fees][]');
    });

    //removal functionality
    $(document).on('click', '.remove-box-button', function () {
        let removal = $(this).parent();
        $(this).parent().fadeOut(250, function () {
            removal.remove();
        });
    });
})(jQuery);