<?php
/**
 * Size Chart Admin Settings.
 *
 * @package Size Chart for WooCommerce
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'SCWC_Size_Chart_Admin_Settings' ) ) :

    /**
     * SCWC Size Chart Admin Settings Class.
     */
    class SCWC_Size_Chart_Admin_Settings {

        /**
         * Returns general fields for the Size Chart settings.
         *
         * @return array
         */
        public static function general_field() {

            $fields = array(
                'popup_library' => array(
                    'title'      => esc_html__( 'Popup Library', 'size-chart-for-woocommerce' ),
                    'field_type' => 'scwcselect',
                    'default'    => 'magnific',
                    'name'       => 'scwc_size_chart_setting[popup_library]',
                    'options'    => array(
                        'featherlight'      => esc_html__( 'Featherlight', 'size-chart-for-woocommerce' ),
                        'magnific'          => esc_html__( 'Magnific', 'size-chart-for-woocommerce' ),
                    ),
                    'data_hide'  => '.popup_library_option',                    
                    // translators: 1: Link to Featherlight plugin documentation, 2: Link to Magnific Popup plugin documentation.
                    'desc'       => wp_kses_post( sprintf( __( 'Read more about %1$s and %2$s. We recommend using the popup library that is already used in your theme or other plugins.', 'size-chart-for-woocommerce' ),
                        '<a href="https://noelboss.github.io/featherlight/" target="_blank">Featherlight</a>',
                        '<a href="https://dimsemenov.com/plugins/magnific-popup/" target="_blank">Magnific</a>'
                    ) ),
                ),
                'position' => array(
                    'title'      => esc_html__( 'Position', 'size-chart-for-woocommerce' ),
                    'field_type' => 'scwcselect',
                    'name'       => 'scwc_size_chart_setting[position]',
                    'default'    => 'above_atc',
                    'options'    => array(
                        'disable-0'                                     => esc_html__( 'Disable', 'size-chart-for-woocommerce' ), 
                        'woocommerce_product_tabs-0'                    => esc_html__( 'In a new tab', 'size-chart-for-woocommerce' ),
                        'woocommerce_single_product_summary-6'          => esc_html__( 'After Product Title', 'size-chart-for-woocommerce' ), 
                        'woocommerce_single_product_summary-11'         => esc_html__( 'After Product Price', 'size-chart-for-woocommerce' ), 
                        'woocommerce_single_product_summary-21'         => esc_html__( 'After Short Description', 'size-chart-for-woocommerce' ), 
                        'woocommerce_single_product_summary-29'         => esc_html__( 'Before Add to Cart Button', 'size-chart-for-woocommerce' ), 
                        'woocommerce_single_product_summary-31'         => esc_html__( 'After Add to Cart Button', 'size-chart-for-woocommerce' ), 
                        'woocommerce_single_product_summary-41'         => esc_html__( 'After Product Meta Information', 'size-chart-for-woocommerce' ), 
                    ),
                    'desc' => wp_kses_post( __( 'Choose the position to show the size-chart links on the single product page. You can also use the shortcode <code>[scwc_size_chart id="123"]</code>.', 'size-chart-for-woocommerce' ) ),
                ),
                'label' => array(
                    'title'      => esc_html__( 'Label', 'size-chart-for-woocommerce' ),
                    'field_type' => 'scwctext',
                    'name'       => 'scwc_size_chart_setting[label]',
                    'default'    => 'Size Charts',
                    'desc'       => esc_html__( 'Customize the label for the size chart link.', 'size-chart-for-woocommerce' ),
                ),
                'display_title' => array(
                    'title'      => esc_html__( 'Hide Chart Title', 'size-chart-for-woocommerce' ),
                    'field_type' => 'scwcswitch',
                    'name'       => 'scwc_size_chart_setting[display_title]',
                    'default'    => 'no',
                    'desc'       => esc_html__( 'Enable to hide the title on the front end.', 'size-chart-for-woocommerce' ),
                ),
            );
            
            return $fields = apply_filters( 'scwc_size_chart_general_fields', $fields );
        }

        /**
         * Returns premium fields for the Size Chart settings.
         *
         * @return array
         */
        public static function premium_fields() {

            $fields = array(
                'use_combined_source' => array(
                    'title'      => esc_html__( 'Use Combined Source', 'size-chart-for-woocommerce' ),
                    'field_type' => 'scwcbuypro',
                    'pro_link'   => SCWC_PRO_VERSION_URL,
                    'default'    => 'no',
                ),

                'product_type_rule' => array(
                    'title'      => esc_html__( 'Product Type Rule', 'size-chart-for-woocommerce' ),
                    'field_type' => 'scwcbuypro',
                    'pro_link'   => SCWC_PRO_VERSION_URL,
                    'default'    => 'no',
                ),

                'product_visibility_rule' => array(
                    'title'      => esc_html__( 'Product Visibility Rule', 'size-chart-for-woocommerce' ),
                    'field_type' => 'scwcbuypro',
                    'pro_link'   => SCWC_PRO_VERSION_URL,
                    'default'    => 'no',
                ),

                'product_tag_rule' => array(
                    'title'      => esc_html__( 'Product Tag Rule', 'size-chart-for-woocommerce' ),
                    'field_type' => 'scwcbuypro',
                    'pro_link'   => SCWC_PRO_VERSION_URL,
                    'default'    => 'no',
                ),

                'shipping_class_rule' => array(
                    'title'      => esc_html__( 'Shipping Class Rule', 'size-chart-for-woocommerce' ),
                    'field_type' => 'scwcbuypro',
                    'pro_link'   => SCWC_PRO_VERSION_URL,
                    'default'    => 'no',
                ),

                'premium_support' => array(
                    'title'      => esc_html__( 'Updates & Premium Support', 'size-chart-for-woocommerce' ),
                    'field_type' => 'scwcbuypro',
                    'pro_link'   => SCWC_PRO_VERSION_URL,
                    'default'    => 'no',
                ),
            );

            return $fields = apply_filters( 'scwc_size_chart_premium_fields', $fields );
        
        }

    }

endif;