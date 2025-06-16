<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'SCWC' ) ) :

    /**
     * Main SCWC Class
     *
     * @class SCWC
     * @version 1.0.0
     */
    final class SCWC {

        /**
         * The single instance of the class.
         *
         * @var SCWC
         */
        protected static $instance = null;

        /**
         * Constructor for the class.
         */
        public function __construct() {
            $this->event_handler();
            $this->includes();
        }

        /**
         * Initialize hooks and filters.
         */
        private function event_handler() {
            // Register plugin activation hook
            register_activation_hook( SCWC_FILE, array( __CLASS__, 'install' ) );

            // Hook to install the plugin after plugins are loaded
            add_action( 'plugins_loaded', array( $this, 'scwc_install' ), 11 );
            add_action( 'plugins_loaded', array( $this, 'includes' ), 11 );
        }

        /**
         * Main SCWC Instance.
         *
         * Ensures only one instance of SCWC is loaded or can be loaded.
         *
         * @static
         * @return SCWC - Main instance.
         */
        public static function instance() {
            if ( is_null( self::$instance ) ) :
                self::$instance = new self();
                do_action( 'scwc_plugin_loaded' );
            endif;
            return self::$instance;
        }

        /**
         * Function to display admin notice if WooCommerce is not active.
         */
        public function woocommerce_admin_notice() {
            ?>
            <div class="error">
                <p><?php esc_html_e( 'Size Chart For WooCommerce requires WooCommerce to work.', 'size-chart-for-woocommerce' ); ?></p>
            </div>
            <?php
        }

        /**
         * Function to initialize the plugin after WooCommerce is loaded.
         */
        public function scwc_install() {
            if ( ! function_exists( 'WC' ) ) :
                add_action( 'admin_notices', array( $this, 'woocommerce_admin_notice' ) );
            else :
                do_action( 'scwc_init' );
            endif;
        }

        /**
         * Include required files.
         */
        public function includes() {
            require_once SCWC_PATH . 'includes/public/class-scwc-size-chart-ajax-handler.php';
            if( is_admin() ) :
                $this->includes_admin();
            else :
                $this->includes_public();
            endif;
        }

        /**
         * Include Admin required files.
         */
        public function includes_admin() {
            require_once SCWC_PATH . 'includes/class-scwc-install.php';
            require_once SCWC_PATH . 'includes/admin/dashboard/class-scwc-dashboard.php';
            require_once SCWC_PATH . 'includes/admin/settings/class-scwc-admin-menu.php';
            require_once SCWC_PATH . 'includes/admin/settings/class-scwc-size-chart-post-type.php';
        }

        /**
         * Include Public required files.
         */
        public function includes_public(){
            require_once SCWC_PATH . 'includes/public/class-scwc-size-chart-frontend.php';
            require_once SCWC_PATH . 'includes/public/class-scwc-size-chart-shortcode.php';
        }

        /**
         * Install the plugin tables.
         */
        public static function install() {
            $defaultOptions = require_once SCWC_PATH . '/includes/static/scwc-default-options.php';
            foreach ( $defaultOptions as $optionKey => $option ) :
                $existingOption = get_option( $optionKey );
                if ( ! $existingOption ) :
                    update_option( $optionKey, $option );
                endif;
            endforeach;
        }

    }
endif;