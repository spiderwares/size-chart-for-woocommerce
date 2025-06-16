<?php
/**
 * JThemes Dashboard Class
 *
 * Handles the admin dashboard setup and related functionalities.
 *
 * @package JThemes
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'SCWC_Dashboard' ) ) {

	/**
	 * Class SCWC_Dashboard
	 *
	 * Initializes the admin dashboard for JThemes.
	 */
	class SCWC_Dashboard {

		/**
		 * Constructor for SCWC_Dashboard class.
		 * Initializes the event handler.
		 */
		public function __construct() {
			$this->events_handler();
		}

		/**
		 * Initialize hooks for admin functionality.
		 */
		private function events_handler() {
			add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
			add_action( 'admin_menu', [ $this, 'admin_menu' ] );
		}

		/**
		 * Enqueue admin-specific styles for the dashboard.
		 */
		public function enqueue_scripts() {
			// Enqueue the SCWC dashboard CSS.
			wp_enqueue_style(
				'scwc-dashboard',
				SCWC_URL . '/assets/css/admin-styles.css',
				[],
				SCWC_VERSION 
			);

			wp_enqueue_style( 'wp-color-picker' );

			wp_enqueue_script(
				'scwc-admin-js',
				SCWC_URL . '/assets/js/scwc-admin.js',
				array( 'jquery', 'wp-color-picker' ), // Dependencies
				SCWC_VERSION,
				true // Load in footer
			);

			// Pass AJAX URL and nonce to JS
			wp_localize_script( 'scwc-admin-js', 'scwc_admin', [
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'nonce'    => wp_create_nonce( 'scwc_admin_nonce' ),
			] );
		}

		/**
		 * Add JThemes menu and submenu to the WordPress admin menu.
		 */
		public function admin_menu() {
			// Add the main menu page.
			add_menu_page(
				'JThemes',
				'JThemes',
				'manage_options',
				'scwc_jthemes',
				[ $this, 'dashboard_callback' ], 
				'data:image/svg+xml;base64,' . base64_encode( file_get_contents( SCWC_PATH . '/assets/img/scwc.svg' ) ),
				26
			);

			// Add a submenu page under the main JThemes menu.
			add_submenu_page( 
                'scwc_jthemes',
                esc_html__( 'JThemes General', 'size-chart-for-woocommerce' ), 
                esc_html__( 'About', 'size-chart-for-woocommerce' ), 
                'manage_options', 
                'scwc_jthemes', 
            );
		}

		/**
		 * Callback function for rendering the dashboard content.
		 */
		public function dashboard_callback() {
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$active_tab = isset( $_GET['tab'] ) ? sanitize_key( $_GET['tab'] ) : 'general';
			// Include the about page view file.
			require_once SCWC_PATH . 'includes/admin/dashboard/views/about.php';
		}

	}

	// Instantiate the SCWC_Dashboard class.
	new SCWC_Dashboard();
}
