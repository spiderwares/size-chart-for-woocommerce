<?php 

if ( ! defined( 'ABSPATH' ) ) exit;

return apply_filters( 'scwc_get_default_options',
    array(
        'scwc_size_chart_setting'   => array(
                'popup_library'     => 'magnific',
                'effect'            => 'mfp-zoom-in',
                'position'          => 'woocommerce_product_tabs-0',
                'label'             => 'Size Chart',
                'display_title'     => 'no',
        ),
    )
);
