<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) :
    exit;
endif;

// Check if the class already exists to avoid redeclaration.
if ( ! class_exists( 'SCWC_Size_Chart_Frontend' ) ) :

    /**
     * Class SCWC_Size_Chart_Frontend
     *
     * Handles the frontend display of size charts on WooCommerce product pages.
     */
    class SCWC_Size_Chart_Frontend {

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

            // Dynamically hook the size chart link based on settings.
            if ( ! empty( $this->setting['position'] ) && $this->setting['position'] !== 'disable-0' ) :
                list( $hook, $priority ) = explode( '-', $this->setting['position'] );
            
                $priority = (int) $priority;
            
                if ( $hook === 'woocommerce_product_tabs' ) :
                    add_filter( $hook, array( $this, 'add_size_chart_tab' ), $priority );
                else :
                    add_action( $hook, array( $this, 'render_size_chart_link' ), $priority );
                endif;
            endif;

            // Render popup container.
            add_action( 'wp_footer', array( $this, 'size_chart_popup' ) );

            // Enqueue required styles on the frontend.
            add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend_assets' ) );
        }

        /**
         * Get all published size chart posts.
         *
         * @return WP_Query
         */
        public function get_all_size_charts() {
            return new WP_Query( array(
                'post_type'      => 'scwc_size_chart',
                'posts_per_page' => -1,
                'post_status'    => 'publish',
            ) );
        }

        /**
         * Match applicable size charts to the given product.
         *
         * @param int $product_id Product ID.
         * @return array Array of matched size charts.
         */
        private function get_matching_size_charts( $product_id ) {
            $product_cats = wc_get_product_term_ids( $product_id, 'product_cat' );
            $charts       = $this->get_all_size_charts();
            $matched      = array();

            if ( $charts && ! empty( $charts->posts ) ) :
                foreach ( $charts->posts as $chart ) :
                    $display_data = get_post_meta( $chart->ID, 'scwc_data', true );
                    $assign_type  = isset( $display_data['assign'] ) ? $display_data['assign'] : 'none';
                    $conditions   = isset( $display_data['condition'] ) ? $display_data['condition'] : array();

                    $is_match = false;

                    // Match chart based on assignment type.
                    switch ( $assign_type ) :
                        case 'products':
                            foreach ( $conditions as $cond ) :
                                $parts = explode( ':', $cond );
                                if ( isset( $parts[1] ) && (int) $parts[1] === $product_id ) :
                                    $is_match = true;
                                    break;
                                endif;
                            endforeach;
                            break;

                        case 'product_cat':
                            foreach ( $conditions as $cond ) :
                                $parts = explode( ':', $cond );
                                if ( isset( $parts[1] ) && in_array( (int) $parts[1], $product_cats, true ) ) :
                                    $is_match = true;
                                    break;
                                endif;
                            endforeach;
                            break;

                        case 'all':
                            $is_match = true;
                            break;

                        case 'none':
                        default:
                            $is_match = false;
                            break;
                    endswitch;

                    $is_match = apply_filters( 'scwc_chart_match', $is_match, $chart, $product_id, $display_data );

                    // If matched, add the chart to the output.
                    if ( $is_match ) :
                        $table_data = get_post_meta( $chart->ID, 'scwc_table_data', true );
                        if ( ! empty( $table_data ) ) :
                            $matched[] = array(
                                'chart'           => $chart,
                                'top_description' => get_post_meta( $chart->ID, 'scwc_top_description', true ),
                                'bottom_notes'    => get_post_meta( $chart->ID, 'scwc_bottom_notes', true ),
                                'style'           => get_post_meta( $chart->ID, 'scwc_size_chart_style', true ),
                                'table_data'      => json_decode( $table_data, true ),
                            );
                        endif;
                    endif;

                endforeach;
            endif;

            return apply_filters( 'scwc_matched_size_charts', $matched, $product_id );
        }

        /**
         * Add custom tab for size chart if applicable charts exist.
         *
         * @param array $tabs Existing WooCommerce tabs.
         * @return array Modified tabs with size chart tab added.
         */
        public function add_size_chart_tab( $tabs ) {
            global $product;

            $matching_charts = $this->get_matching_size_charts( $product->get_id() );

            if ( ! empty( $matching_charts ) ) :
                $tabs['scwc_size_chart'] = array(
                    'title'    => esc_html( isset( $this->setting['label'] ) ? $this->setting['label'] : esc_html__( 'Size Chart', 'size-chart-for-woocommerce' ) ),
                    'priority' => 50,
                    'callback' => array( $this, 'render_size_chart_tab_content' ),
                );
            endif;

            return $tabs;
        }

        /**
         * Render the content of the custom size chart tab.
         */
        public function render_size_chart_tab_content() {
            global $product;

            $matching_charts = $this->get_matching_size_charts( $product->get_id() );

            foreach ( $matching_charts as $data ) :
                wc_get_template(
                    'size-chart/display-size-chart.php',
                    array(
                        'top_description' => $data['top_description'],
                        'bottom_notes'    => $data['bottom_notes'],
                        'table_data'      => $data['table_data'],
                        'style'           => $data['style'],
                    ),
                    'essential-kit-for-woocommerce/',
                    SCWC_TEMPLATE_PATH
                );
            endforeach;
        }

        /**
         * Enqueue frontend CSS styles for the size chart display.
         */
        public function enqueue_frontend_assets() {
            wp_enqueue_style(
                'scwc-size-chart-style',
                SCWC_URL . 'assets/css/size-chart/scwc-size-chart-style.css',
                array(),
                SCWC_VERSION
            );

            wp_enqueue_script(
                'scwc-size-chart-frontend',
                SCWC_URL . 'assets/js/size-chart/scwc-size-chart-frontend.js',
                array('jquery'),
                SCWC_VERSION,
                true
            );
            
            if ( isset( $this->setting['popup_library'] ) && $this->setting['popup_library'] === 'featherlight' ):
                wp_enqueue_style(
                    'featherlight-css',
                    SCWC_URL . 'assets/css/size-chart/featherlight.min.css',
                    array(),
                    SCWC_VERSION
                );
                
                wp_enqueue_script(
                    'featherlight-js',
                    SCWC_URL . 'assets/js/size-chart/featherlight.min.js',
                    array('jquery'),
                    SCWC_VERSION,
                    true
                );
            endif;

            if ( isset( $this->setting['popup_library'] ) && $this->setting['popup_library'] === 'magnific' ):
                wp_enqueue_style(
                    'magnific-css',
                    SCWC_URL . 'assets/css/size-chart/magnific.min.css',
                    array(),
                    SCWC_VERSION
                );
                
                wp_enqueue_script(
                    'magnific-js',
                    SCWC_URL . 'assets/js/size-chart/magnific.min.js',
                    array('jquery'),
                    SCWC_VERSION,
                    true
                );
            endif;
        
            // Optional: Localize script if you need to pass data to JS
            wp_localize_script( 'scwc-size-chart-frontend', 'scwc_size_chart_vars', array(
                'ajax_url'  => admin_url('admin-ajax.php'),
                'setting'   => $this->setting,
                'nonce'     => wp_create_nonce('scwc_size_chart_nonce'),
            ));
        }

        /**
         * Render the size chart link selector outside the tab.
         */
        public function render_size_chart_link() {
            global $product;

            if ( ! $product instanceof WC_Product ) :
                return;
            endif;

            $matching_charts = $this->get_matching_size_charts( $product->get_id() );

            if ( ! empty( $matching_charts ) ) :
                echo '<div class="scwc-size-charts-list">';
                echo '<span class="scwc-size-charts-list-label">' . ( isset( $this->setting['label'] ) ? esc_html( $this->setting['label'] ) . ':' : esc_html__( 'Size Charts', 'size-chart-for-woocommerce' ) ) . '</span>';

                foreach ( $matching_charts as $chart_data ) :
                    if ( isset( $chart_data['chart'] ) && $chart_data['chart'] instanceof WP_Post ) :
                        $chart_id    = (int) $chart_data['chart']->ID;
                        $chart_title = esc_html( $chart_data['chart']->post_title );

                        echo '<a class="scwc-btn scwc-size-charts-list-item" data-id="' . esc_attr( $chart_id ) . '">' . esc_html( $chart_title ) . '</a>';
                    endif;
                endforeach;

                echo '</div>';
            endif;
        }

        /**
         * Output the size chart popup container in footer.
         */
        public function size_chart_popup() { ?>
            <div id="scwc-size-chart-popup" class="scwc-size-chart-popup" style="display: none;">
                <div class="scwc-popup-overlay"></div>
                <div class="scwc-popup-content">
                    <button class="scwc-close-popup" aria-label="<?php esc_attr_e( 'Close', 'size-chart-for-woocommerce' ); ?>">&times;</button>
                    <div class="scwc-popup-inner"></div>
                </div>
            </div>
            <?php
        }


    }

    // Instantiate the class.
    new SCWC_Size_Chart_Frontend();

endif;