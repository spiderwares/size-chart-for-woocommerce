<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) :
    exit;
endif;

// Check if the class already exists to avoid redeclaration.
if ( ! class_exists( 'SCWC_Size_Chart_Ajax_Handler' ) ) :

    /**
     * Class SCWC_Size_Chart_Ajax_Handler
     *
     * Handles the frontend display of size charts on WooCommerce product pages.
     */
    class SCWC_Size_Chart_Ajax_Handler {

        /**
         * Size Chart settings options.
         *
         * @var array
         */
        private $setting;

        /**
         * Constructor to initialize hooks.
         */
        public function __construct() {
            $this->setting = get_option( 'scwc_size_chart_setting', [] );
            $this->event_handler();
        }

        /**
         * Register frontend hooks and filters.
         */
        public function event_handler() {
            // Enqueue required styles on the frontend.
            add_action( 'wp_ajax_scwc_get_size_chart_content', [ $this, 'get_size_chart_content' ] );
            add_action( 'wp_ajax_nopriv_scwc_get_size_chart_content', [ $this, 'get_size_chart_content' ] );
            add_action( 'wp_ajax_fetch_terms_for_condition', [ $this, 'fetch_terms_for_condition' ] );
        }

        /**
         * Render the content of the custom size chart tab.
         */
        public function get_size_chart_content() {

            if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'scwc_size_chart_nonce' ) ) :
                return;
            endif;

            $post_id = isset( $_POST['chart_id'] ) ? absint( $_POST['chart_id'] ) : 0;
        
            if ( !$post_id || get_post_type( $post_id ) !== 'scwc_size_chart' ) :
                wp_send_json_error(['message' => 'Invalid size chart ID.']);
            endif;
        
            $top_description = get_post_meta( $post_id, 'scwc_top_description', true );
            $bottom_notes    = get_post_meta( $post_id, 'scwc_bottom_notes', true );
            $style           = get_post_meta( $post_id, 'scwc_size_chart_style', true );
            $table_data_json = get_post_meta( $post_id, 'scwc_table_data', true );
            $table_titles    = get_post_meta( $post_id, 'scwc_table_titles', true );
            $table_data      = json_decode( $table_data_json, true );
        
            ob_start();
        
            wc_get_template(
                'size-chart/display-size-chart.php',
                array(
                    'post_id'         => $post_id,
                    'top_description' => $top_description,
                    'bottom_notes'    => $bottom_notes,
                    'table_data'      => $table_data,
                    'style'           => $style,
                    'table_titles'    => $table_titles,
                ),
                'size-chart-for-woocommerce/',
                SCWC_TEMPLATE_PATH
            );
        
            $output = ob_get_clean();
        
            wp_send_json_success( [ 'html' => $output ] );
        }


        public function fetch_terms_for_condition() {
            
            if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'scwc_size_chart_nonce' ) ) :
                return;
            endif;

            if (!isset($_POST['condition_type'])) :
                wp_send_json_error(['message' => 'Invalid condition type.']);
            endif;

            $condition_type = sanitize_text_field( wp_unslash( $_POST['condition_type'] ) );
            $terms = [];

            switch ($condition_type) {
                case 'product_cat':
                    // Get product categories
                    $terms = get_terms([
                        'taxonomy'   => 'product_cat',
                        'hide_empty' => false,
                        'orderby'    => 'name',
                    ]);
                    break;
                case 'product_tag':
                    // Get product tags
                    $terms = get_terms([
                        'taxonomy'   => 'product_tag',
                        'hide_empty' => false,
                        'orderby'    => 'name',
                    ]);
                    break;
                case 'product_brand':
                    // If you are using a custom taxonomy for brands, replace with that taxonomy
                    $terms = get_terms([
                        'taxonomy'   => 'product_brand',  // Replace 'product_brand' with your custom taxonomy
                        'hide_empty' => false,
                        'orderby'    => 'name',
                    ]);
                    break;
                // Add more conditions (for product_shipping_class, etc.)
                // Case for custom product attributes, etc.
            }

            // Prepare terms data
            $terms_data = array_map(function ($term) {
                return [
                    'id'   => $term->term_id,
                    'name' => $term->name,
                ];
            }, $terms);

            wp_send_json_success($terms_data);
        }
        

    }

    new SCWC_Size_Chart_Ajax_Handler();

endif;