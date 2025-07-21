<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) :
    exit;
endif;

if ( ! class_exists( 'SCWC_Size_Chart_Post_Type' ) ) :

    /**
     * Main SCWC_Size_Chart_Post_Type Class
     *
     * @class SCWC_Size_Chart_Post_Type
     * @version 1.0.0
     */
    final class SCWC_Size_Chart_Post_Type {

        /**
         * Constructor for the class.
         */
        public function __construct() {
            add_action( 'init', [ $this, 'register_size_chart_cpt' ] );
            add_action( 'add_meta_boxes', [ $this, 'add_meta_boxes' ] );
			add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
            add_action( 'save_post', [ $this, 'save_size_chart_meta' ] );            
        }

        /**
         * Register the Size Chart custom post type.
         */
        public function register_size_chart_cpt() {

            $labels = [
                'name'                  => esc_html__( 'Size Charts', 'size-chart-for-woocommerce' ),
                'singular_name'         => esc_html__( 'Size Chart', 'size-chart-for-woocommerce' ),
                'menu_name'             => esc_html__( 'Size Charts', 'size-chart-for-woocommerce' ),
                'name_admin_bar'        => esc_html__( 'Size Chart', 'size-chart-for-woocommerce' ),
                'add_new'               => esc_html__( 'Add New', 'size-chart-for-woocommerce' ),
                'add_new_item'          => esc_html__( 'Add New Size Chart', 'size-chart-for-woocommerce' ),
                'edit_item'             => esc_html__( 'Edit Size Chart', 'size-chart-for-woocommerce' ),
                'new_item'              => esc_html__( 'New Size Chart', 'size-chart-for-woocommerce' ),
                'view_item'             => esc_html__( 'View Size Chart', 'size-chart-for-woocommerce' ),
                'all_items'             => esc_html__( 'All Size Charts', 'size-chart-for-woocommerce' ),
                'search_items'          => esc_html__( 'Search Size Charts', 'size-chart-for-woocommerce' ),
                'not_found'             => esc_html__( 'No size charts found.', 'size-chart-for-woocommerce' ),
                'not_found_in_trash'    => esc_html__( 'No size charts found in Trash.', 'size-chart-for-woocommerce' ),
            ];

            $args = [
                'label'               => esc_html__( 'Size Chart', 'size-chart-for-woocommerce' ),
                'labels'              => $labels,
                'supports'            => [ 'title' ],
                'hierarchical'        => false,
                'public'              => false,
                'show_ui'             => true,
                'menu_position'       => 28,
                'menu_icon'           => 'dashicons-chart-line',
                'exclude_from_search' => true,
                'publicly_queryable'  => false,
                'show_in_rest'        => false,
            ];

            register_post_type( 'scwc_size_chart', $args );
        }

        /**
         * Register meta boxes for the Size Chart custom post type.
         *
         * @return void
         */
        public function add_meta_boxes() {
            add_meta_box(
                'scwc_content',                                                     
                esc_html__( 'Size Chart Content', 'size-chart-for-woocommerce' ),
                [ $this, 'content_callback' ],                                      
                'scwc_size_chart',
                'advanced',
                'low'
            );

            add_meta_box(
                'scwc_shortcode',
                esc_html__( 'Shortcode', 'size-chart-for-woocommerce' ),
                [ $this, 'shortcode_callback' ],
                'scwc_size_chart',
                'side',
                'default'
            );

            add_meta_box(
                'scwc_display_rules',
                esc_html__( 'Display Rules', 'size-chart-for-woocommerce' ),
                [ $this, 'display_rules_callback' ], 
                'scwc_size_chart',
                'advanced',
                'low'
            );

            add_meta_box(
                'scwc_table_style',
                esc_html__( 'Table Style', 'size-chart-for-woocommerce' ),
                [ $this, 'table_style_callback' ], 
                'scwc_size_chart', 
                'advanced', 
                'low' 
            );
            
            
        }

        /**
         * Callback function for the Configuration meta box.
         *
         * @param WP_Post $post The current post object.
         *
         * @return void
         */
        public function content_callback( $post ) {
            $post_id            = $post->ID;
            $top_description    = get_post_meta( $post_id, 'scwc_top_description', true );
            $bottom_notes       = get_post_meta( $post_id, 'scwc_bottom_notes', true );
            $table_data         = get_post_meta( $post_id, 'scwc_table_data', true ) ?: '[[[""]]]';
            $table_title        = get_post_meta( $post_id, 'scwc_table_titles', true );
            $table_data_arr     = json_decode( $table_data );

            wc_get_template(
                'size-chart/content-meta-box.php',
                array(
                    'post_id'           => $post_id,
                    'top_description'   => $top_description,
                    'bottom_notes'      => $bottom_notes,
                    'table_data'        => $table_data,
                    'table_data_arr'    => $table_data_arr,
                    'table_title'       => $table_title
                ),
                'size-chart-for-woocommerce/',
                SCWC_TEMPLATE_PATH
            );
            
        }
        
        /**
         * Callback function for the Shortcode meta box.
         *
         * @param WP_Post $post The current post object.
         *
         * @return void
         */
        public function shortcode_callback( $post ) {
            wc_get_template(
                'size-chart/shortcode-meta-box.php',
                array(
                    'post_id' => $post->ID,
                ),
                'size-chart-for-woocommerce/',
                SCWC_TEMPLATE_PATH
            );
        }

        /**
         * Callback function for the Display Rules meta box.
         *
         * @param WP_Post $post The current post object.
         */
        public function display_rules_callback( $post ) {
            $post_id = $post->ID;

            // Assign options list
            $assign_options = array(
                'none'               => esc_html__( 'None', 'size-chart-for-woocommerce' ),
                'all'                => esc_html__( 'All Products', 'size-chart-for-woocommerce' ),
                'products'           => esc_html__( 'Products', 'size-chart-for-woocommerce' ),
                'product_cat'        => esc_html__( 'Product Categories', 'size-chart-for-woocommerce' ),
                'combined'           => esc_html__( 'Combined (Premium)', 'size-chart-for-woocommerce' ),
                'product_type'       => esc_html__( 'Product Type (Premium)', 'size-chart-for-woocommerce' ),
                'product_visibility' => esc_html__( 'Product Visibility (Premium)', 'size-chart-for-woocommerce' ),
                'product_tag'        => esc_html__( 'Product Tags (Premium)', 'size-chart-for-woocommerce' ),
                'shipping_class'     => esc_html__( 'Product Shipping Class (Premium)', 'size-chart-for-woocommerce' ),
            );

            $assign_options = apply_filters( 'scwc_assign_options', $assign_options );
            $scwc_data      = get_post_meta( $post_id, 'scwc_data', true );
            $scwc_assign    = $scwc_data['assign'] ?? 'none';
            $scwc_condition = $scwc_data['condition'] ?? [];

            // Render template
            wc_get_template(
                'size-chart/display-rule-meta-box.php',
                array(
                    'post_id'        => $post_id,
                    'assign_options' => $assign_options,
                    'scwc_assign'    => $scwc_assign,
                    'scwc_condition' => $scwc_condition,
                ),
                'size-chart-for-woocommerce/',
                SCWC_TEMPLATE_PATH
            );
        }

        /**
         * Callback function for the Table Style meta box.
         *
         * @param WP_Post $post The current post object.
         *
         * @return void
         */
        public function table_style_callback( $post ) {
            $post_id = $post->ID;
        
            // Get the saved style data
            $style_data         = get_post_meta( $post_id, 'scwc_size_chart_style', true );
                    
            // Set default values if the style data is empty
            $heading_bgcolor    = isset( $style_data['heading']['bgcolor'] ) ? esc_attr( $style_data['heading']['bgcolor'] ) : '#e0f2fe';
            $heading_color      = isset( $style_data['heading']['color'] ) ? esc_attr( $style_data['heading']['color'] ) : '#111827';
            $odd_row_bgcolor    = isset( $style_data['odd']['bgcolor'] ) ? esc_attr( $style_data['odd']['bgcolor'] ) : '#ffffff';
            $odd_row_color      = isset( $style_data['odd']['color'] ) ? esc_attr( $style_data['odd']['color'] ) : '#374151';
            $even_row_bgcolor   = isset( $style_data['even']['bgcolor'] ) ? esc_attr( $style_data['even']['bgcolor'] ) : '#f7f9fc';
            $even_row_color     = isset( $style_data['even']['color'] ) ? esc_attr( $style_data['even']['color'] ) : '#111827';
        
            // Correct the variable names when passing to wc_get_template
            wc_get_template(
                'size-chart/table-style-meta-box.php',
                array(
                    'post_id'             => $post_id,
                    'heading_bgcolor'     => $heading_bgcolor,
                    'heading_color'       => $heading_color,
                    'odd_row_bgcolor'     => $odd_row_bgcolor,
                    'odd_row_color'       => $odd_row_color,
                    'even_row_bgcolor'    => $even_row_bgcolor,
                    'even_row_color'      => $even_row_color,
                ),
                'size-chart-for-woocommerce/',
                SCWC_TEMPLATE_PATH
            );
        }


        /**
		 * Enqueue admin-specific styles for the dashboard.
		 */
		public function enqueue_scripts( $hook ) {
			wp_enqueue_script(
				'scwc-size-chart-handler',
				SCWC_URL . '/assets/js/size-chart/scwc-size-chart-handler.js',
				array( 'jquery', 'wc-enhanced-select', 'wp-color-picker' ), // Dependencies
				SCWC_VERSION,
				true // Load in footer
			);

			wp_enqueue_style(
				'select2-css',
				SCWC_URL . 'assets/css/select2.min.css',
				[],
				SCWC_VERSION
			);

			// Enqueue Select2 JS.
			wp_enqueue_script(
				'select2-js',
				SCWC_URL . 'assets/js/select2.min.js',
				[ 'jquery' ],
				SCWC_VERSION,
				true
			);
		}

        /**
         * Save meta box content for Size Chart Content.
         *
         * @param int $post_id Post ID.
         */
        public function save_size_chart_meta( $post_id ) {

            if ( ! isset( $_POST['scwc_meta_box_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['scwc_meta_box_nonce'] ) ), 'scwc_save_meta_box' ) ) :
                return;
            endif;

            // Bail early on autosave, revision, or wrong post type.
            if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
            if ( get_post_type( $post_id ) !== 'scwc_size_chart' ) return;
            if ( ! current_user_can( 'edit_post', $post_id ) ) return;

            // Sanitize and save top description.
            if ( isset( $_POST['scwc_top_description'] ) ) :
                $top_description = wp_kses_post( wp_unslash( $_POST['scwc_top_description'] ) );
                update_post_meta( $post_id, 'scwc_top_description', $top_description );
            endif;

            // Sanitize and save bottom notes.
            if ( isset( $_POST['scwc_bottom_notes'] ) ) :
                $bottom_notes = wp_kses_post( wp_unslash($_POST['scwc_bottom_notes'] ) );
                update_post_meta( $post_id, 'scwc_bottom_notes', $bottom_notes );
            endif;

            // Save table data if valid JSON.
            if ( isset( $_POST['scwc_table_data'] ) && is_array( $_POST['scwc_table_data'] ) ) :
                $tables_data = [];
                // Ignored: $_POST['scwc_table_data'] not unslashed before sanitization. Use wp_unslash() or similar.
                foreach ( $_POST['scwc_table_data'] as $raw_json ) :
                    $unslashed  = wp_unslash( $raw_json ); 
                    $sanitized  = sanitize_text_field( $unslashed );
                    $decoded    = json_decode( $sanitized, true );

                    if ( is_array( $decoded ) ) :
                        $tables_data[] = $decoded;
                    endif;
                endforeach;
                update_post_meta( $post_id, 'scwc_table_data', wp_json_encode( $tables_data ) );
            endif;

            // Ignored: $_POST['scwc_table_data'] not unslashed before sanitization. Use wp_unslash() or similar.
            if ( isset( $_POST['scwc_table_titles'] ) && is_array( $_POST['scwc_table_titles'] ) ) :
                update_post_meta( $post_id, 'scwc_table_titles', $_POST['scwc_table_titles'] );
            endif;

            // --- Save Display Rules ---
            $assign    = isset( $_POST['scwc_assign'] ) ? sanitize_text_field( wp_unslash( $_POST['scwc_assign'] ) ) : 'none';
            $condition = array();

            switch ( $assign ) :
                case 'products':
                    if ( isset( $_POST['scwc_assign_products'] ) && is_array( $_POST['scwc_assign_products'] ) ) :
                        $product_ids = array_map( 'absint', wp_unslash( $_POST['scwc_assign_products'] ) );
                        foreach ( $product_ids as $product_id ) :
                            $product_id = absint( $product_id );
                            $product    = wc_get_product( $product_id );
                            if ( $product ) :
                                $condition[] = 'products:' . $product_id . ':' . $product->get_name();
                            endif;
                        endforeach;
                    endif;
                    break;
            
                case 'product_cat':
                    if ( isset( $_POST['scwc_assign_product_cat'] ) && is_array( $_POST['scwc_assign_product_cat'] ) ) :
                        $cat_ids = array_map( 'absint', wp_unslash( $_POST['scwc_assign_product_cat'] ) );
                        foreach ( $cat_ids as $cat_id ) :
                            $term = get_term( absint( $cat_id ), 'product_cat' );
                            if ( $term && ! is_wp_error( $term ) ) :
                                $condition[] = 'product_cat:' . $term->term_id . ':' . $term->name;
                            endif;
                        endforeach;
                    endif;
                    break;
            
                case 'product_type':
                    if ( isset( $_POST['scwc_assign_product_type'] ) && is_array( $_POST['scwc_assign_product_type'] ) ) :
                        $product_types = array_map( 'sanitize_text_field', wp_unslash( $_POST['scwc_assign_product_type'] ) );
                        foreach ( $product_types as $type ) :
                            $condition[] = 'product_type:' . sanitize_text_field( $type );
                        endforeach;
                    endif;
                    break;
            
                case 'product_visibility':
                    if ( isset( $_POST['scwc_assign_product_visibility'] ) && is_array( $_POST['scwc_assign_product_visibility'] ) ) :
                        $visibilities = array_map( 'sanitize_text_field', wp_unslash( $_POST['scwc_assign_product_visibility'] ) );
                        foreach ( $visibilities as $visibility ) :
                            $condition[] = 'product_visibility:' . sanitize_text_field( $visibility );
                        endforeach;
                    endif;
                    break;
            
                case 'product_tag':
                    if ( isset( $_POST['scwc_assign_product_tag'] ) && is_array( $_POST['scwc_assign_product_tag'] ) ) :
                        $tag_ids = array_map( 'absint', wp_unslash( $_POST['scwc_assign_product_tag'] ) );
                        foreach ( $tag_ids as $tag_id ) :
                            $term = get_term( absint( $tag_id ), 'product_tag' );
                            if ( $term && ! is_wp_error( $term ) ) :
                                $condition[] = 'product_tag:' . $term->term_id . ':' . $term->name;
                            endif;
                        endforeach;
                    endif;
                    break;
            
                case 'shipping_class':
                    if ( isset( $_POST['scwc_assign_shipping_class'] ) && is_array( $_POST['scwc_assign_shipping_class'] ) ) :
                        $class_ids = array_map( 'absint', wp_unslash( $_POST['scwc_assign_shipping_class'] ) );
                        foreach ( $class_ids as $class_id ) :
                            $term = get_term( absint( $class_id ), 'product_shipping_class' );
                            if ( $term && ! is_wp_error( $term ) ) :
                                $condition[] = 'shipping_class:' . $term->term_id . ':' . $term->name;
                            endif;
                        endforeach;
                    endif;
                    break;
            
                default:
                    $condition = array();
                    break;
            endswitch;

            $data = array(
                'assign'    => $assign,
                'condition' => $condition,
            );

            update_post_meta( $post_id, 'scwc_data', $data );
            
            // Ignored: $_POST['scwc_table_data'] not unslashed before sanitization. Use wp_unslash() or similar.
            $style_input_raw = isset( $_POST['scwc_size_chart_style'] ) ? wp_unslash( $_POST['scwc_size_chart_style'] ) : [];
            $style_input     = is_array( $style_input_raw ) ? $style_input_raw : [];
            $style_data  = [
                'heading' => [
                    'bgcolor' => isset( $style_input['heading']['bgcolor'] ) ? sanitize_hex_color( $style_input['heading']['bgcolor'] ) : '#e0f2fe',
                    'color'   => isset( $style_input['heading']['color'] ) ? sanitize_hex_color( $style_input['heading']['color'] ) : '#111827',
                ],
                'odd' => [
                    'bgcolor' => isset( $style_input['odd']['bgcolor'] ) ? sanitize_hex_color( $style_input['odd']['bgcolor'] ) : '#ffffff',
                    'color'   => isset( $style_input['odd']['color'] ) ? sanitize_hex_color( $style_input['odd']['color'] ) : '#374151',
                ],
                'even' => [
                    'bgcolor' => isset( $style_input['even']['bgcolor'] ) ? sanitize_hex_color( $style_input['even']['bgcolor'] ) : '#f7f9fc',
                    'color'   => isset( $style_input['even']['color'] ) ? sanitize_hex_color( $style_input['even']['color'] ) : '#111827',
                ],
            ];

            // Save the serialized data in a single meta field
            update_post_meta( $post_id, 'scwc_size_chart_style', $style_data );

        }

        
    }

    // Instantiate the class.
    new SCWC_Size_Chart_Post_Type();

endif;