<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'SCWC_Admin_Menu' ) ) :

    /**
     * Main SCWC_Admin_Menu Class
     *
     * @class SCWC_Admin_Menu
     * @version     
     */
    final class SCWC_Admin_Menu {

        /**
         * The single instance of the class.
         *
         * @var SCWC_Admin_Menu
         */
        protected static $instance = null;

        /**
         * Constructor for the class.
         * Initializes the event handler (hooks and actions).
         */
        public function __construct() {
            $this->event_handler();
        }

        /**
         * Initialize hooks and filters for the admin menu.
         * This includes the settings registration and filter actions.
         */
        private function event_handler() {
            // Add admin init action to register settings
            add_action( 'admin_init', [ $this, 'register_settings' ] );

            // Add admin menu action to create submenu pages
            add_action( 'admin_menu', [ $this, 'admin_menu' ] );
            add_filter( 'pre_update_option_scwc_quick_view_setting', [ $this, 'filter_data_before_update' ], 10, 3 );
        }

        /**
         * Register plugin settings.
         * This is used to register all the settings that will be stored in the options table.
         */
        public function register_settings() {
            register_setting( 'scwc_size_chart_setting', 'scwc_size_chart_setting', [ 'sanitize_callback' => [ $this, 'sanitize_input' ] ] );
        }
        
        /**
         * Generic sanitization function for all settings.
         *
         * @param mixed $input The input value to sanitize.
         * @return mixed Sanitized input value.
         */
        public function sanitize_input( $input ) {
            add_settings_error(
                'scwc_size_chart_setting',
                'scwc_size_chart_setting_updated',
                esc_html__( 'Settings saved successfully.', 'size-chart-for-woocommerce' ),
                'updated'
            );

            return $input;
        }

        /**
         * Add submenus to the "JThemes" menu.
         * These submenus allow users to navigate and configure various settings.
         */
        public function admin_menu() {
            // Add Quick View submenu
            add_submenu_page( 
                'jthemes', 
                esc_html__( 'Size Chart', 'size-chart-for-woocommerce' ), 
                esc_html__( 'Size Chart', 'size-chart-for-woocommerce' ), 
                'manage_options', 
                'scwc-size-chart', 
                [ $this, 'size_chart_menu_content' ] 
            );
        }
        
        /**
         * Display content for the Size Chart settings page.
         */
        public function size_chart_menu_content() {
            // Get the active tab (default to 'general')
            // phpcs:ignore WordPress.Security.NonceVerification.Recommended
            $active_tab = isset( $_GET['tab'] ) ? sanitize_key( $_GET['tab'] ) : 'general';

            // Include the view file for the Size Chart settings page
            require_once SCWC_PATH . 'includes/admin/settings/views/size-chart-menu.php';
        }    

        /**
         * Filter data before updating options in the database.
         *
         * @param mixed  $value     The new value to be updated.
         * @param mixed  $old_value The previous value.
         * @param string $option    The option name.
         *
         * @return mixed The filtered data.
         */
        public function filter_data_before_update( $value, $old_value, $option ) {
            // Merge old value with new value to retain all settings
            $data = array_merge( (array) $old_value, (array) $value );
            return $data;
        }

    }

    // Instantiate the SCWC_Admin_Menu class
    new SCWC_Admin_Menu();

endif;
