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
            add_action( 'wp_ajax_jthemes_get_plugins_kit', [ $this, 'ajax_get_plugins_kit' ] );
		}

		/**
		 * Enqueue admin-specific styles for the dashboard.
		 */
		public function enqueue_scripts() {
			
			wp_enqueue_script( 'plugin-install' );
			wp_enqueue_script( 'thickbox' );
			wp_enqueue_style( 'wp-color-picker' );
    		wp_enqueue_style( 'thickbox' );

			wp_enqueue_style(
				'scwc-dashboard',
				SCWC_URL . 'includes/admin/dashboard/css/jthemes-dashboard.css',
				[],
				SCWC_VERSION // Version number for cache-busting.
			);

			wp_enqueue_script(
				'scwc-dashboard', 
				SCWC_URL . 'includes/admin/dashboard/js/jthemes-dashboard.js', 
				array(), 
				SCWC_VERSION, 
				[ 'in_footer' => true ]
			);

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
				'jthemes',
				[ $this, 'dashboard_callback' ], 
				'data:image/svg+xml;base64,' . base64_encode( file_get_contents( SCWC_PATH . '/assets/img/scwc.svg' ) ),
				26
			);

			// Add a submenu page under the main JThemes menu.
			add_submenu_page( 
                'jthemes',
                esc_html__( 'JThemes General', 'size-chart-for-woocommerce' ), 
                esc_html__( 'About', 'size-chart-for-woocommerce' ), 
                'manage_options', 
                'jthemes', 
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
		
		/**
		 * AJAX callback to fetch and display Spiderwares plugin kit.
		 *
		 * @return void
		 */
		public function ajax_get_plugins_kit() {
			if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'scwc_admin_nonce' ) ) :
                return;
            endif;

			$transient_key = 'jthemesstudio_plugins_kit';

			// Attempt to retrieve plugin data from cache.
			if ( false === ( $plugins = get_transient( $transient_key ) ) ) :
				$args = (object) [
					'author'   => 'jthemesstudio',
					'per_page' => 120,
					'page'     => 1,
					'fields'   => [
						'slug',
						'name',
						'version',
						'downloaded',
						'active_installs',
						'last_updated',
						'rating',
						'num_ratings',
						'short_description',
						'icons',
                    	'banners'
					],
				];

				$response = wp_remote_post(
					'http://api.wordpress.org/plugins/info/1.0/',
					[
						'body' => [
							'action'  => 'query_plugins',
							'timeout' => 30,
							'request' => serialize( $args ),
						],
					]
				);

				// Bail out if API request fails.
				if ( is_wp_error( $response ) ) :
					wp_send_json_error(
						sprintf(
							/* translators: %s: Plugin website URL */
							__( 'Error loading kit. Visit: %s', 'size-chart-for-woocommerce' ),
							'<a href="https://spiderwares.com" target="_blank">spiderwares.com</a>'
						)
					);
				endif;

				$data    = maybe_unserialize( wp_remote_retrieve_body( $response ) );
				$plugins = [];

				// Prepare plugin list.
				if ( ! empty( $data->plugins ) ) :
					foreach ( $data->plugins as $p ) :
						$plugins[] = [
							'slug'        => $p->slug,
							'name'        => $p->name,
							'version'     => $p->version,
							'downloaded'  => $p->downloaded,
							'active'      => $p->active_installs,
							'updated'     => strtotime( $p->last_updated ),
							'rating'      => $p->rating,
							'ratings'     => $p->num_ratings,
							'description' => $p->short_description,
							'icon' 		  => $p->icons['1x'],
						];
					endforeach;

					// Cache results for 1 day.
					set_transient( $transient_key, $plugins, DAY_IN_SECONDS );
				endif;
			endif;

			// Bail if no data.
			if ( empty( $plugins ) ) :
				wp_send_json_error( __( 'No plugin data found.', 'size-chart-for-woocommerce' ) );
			endif;

			// Sort plugins by active installs.
			usort( $plugins, fn( $a, $b ) => $b['active'] <=> $a['active'] );

			// Output each plugin card.
			foreach ( $plugins as $plugin ) :
				$this->jthemes_render_plugin_card( $plugin );
			endforeach;

			wp_die();
		}

		/**
		 * Renders a single plugin card for the plugins Kit UI.
		 *
		 * @param array $plugin Plugin data.
		 * @return void
		 */
		public function jthemes_render_plugin_card( $plugin ) {
			$slug   = isset( $plugin['slug'] ) ? $plugin['slug'] : '';
			$name   = isset( $plugin['name'] ) ? $plugin['name'] : '';
			$file   = $slug . '.php';
			$image  = isset( $plugin['icon'] ) ? $plugin['icon'] : '';
			$desc   = isset( $plugin['description'] ) ? $plugin['description'] : '';
			$link   = network_admin_url( "plugin-install.php?tab=plugin-information&plugin={$slug}&TB_iframe=true&width=600&height=550" );
			$active = $this->jthemes_is_plugin_active( $slug, $file );
			$exists = $this->jthemes_is_plugin_installed( $slug, $file );

			echo '<div class="plugin-card ' . esc_attr( $slug ) . '" id="' . esc_attr( $slug ) . '">';
			echo '<div class="plugin-card-top">';
			echo '<a href="' . esc_url( $link ) . '" class="thickbox" title="' . esc_attr( $name ) . '"><img src="' . esc_url( $image ) . '" class="plugin-icon" alt="' . esc_attr( $name ) . '" /></a>';
			echo '<div class="name column-name"><h3><a href="' . esc_url( $link ) . '" class="thickbox" title="' . esc_attr( $name ) . '">' . esc_html( $name ) . '</a></h3></div>';

			echo '<div class="action-links"><ul class="plugin-action-buttons"><li>';

			if ( $exists ) :
				$url  = $active ? $this->jthemes_plugin_link( 'deactivate', $slug, $file ) : $this->jthemes_plugin_link( 'activate', $slug, $file );
				$text = $active ? __( 'Deactivate', 'size-chart-for-woocommerce' ) : __( 'Activate', 'size-chart-for-woocommerce' );
				echo '<a href="' . esc_url( $url ) . '" class="button">' . esc_html( $text ) . '</a>';
			else :
				$url = wp_nonce_url( self_admin_url( "update.php?action=install-plugin&plugin={$slug}" ), "install-plugin_{$slug}" );
				echo '<a href="' . esc_url( $url ) . '" class="button install-now">' . esc_html__( 'Install Now', 'size-chart-for-woocommerce' ) . '</a>';
			endif;

			echo '</li><li><a href="' . esc_url( $link ) . '" class="thickbox open-plugin-details-modal" title="' . esc_attr( $name ) . '">' . esc_html__( 'More Details', 'size-chart-for-woocommerce' ) . '</a></li></ul></div>';
			echo '<div class="desc column-description"><p>' . esc_html( $desc ) . '</p></div></div><div class="plugin-card-bottom">';

			if ( isset( $plugin['rating'], $plugin['ratings'] ) ) :
				echo '<div class="vers column-rating">';
				wp_star_rating(
					[
						'rating' => $plugin['rating'],
						'type'   => 'percent',
						'number' => $plugin['ratings'],
					]
				);
				echo '<span class="num-ratings">(' . esc_html( number_format_i18n( $plugin['ratings'] ) ) . ')</span></div>';
			endif;

			if ( isset( $plugin['version'] ) ) :
				echo '<div class="column-updated"><strong>' . esc_html__( 'Version:', 'size-chart-for-woocommerce' ) . '</strong> ' . esc_html( $plugin['version'] ) . '</div>';
			endif;

			if ( isset( $plugin['active'] ) ) :
				echo '<div class="column-downloaded">' . esc_html( number_format_i18n( $plugin['active'] ) ) . esc_html__( '+ Active Installations', 'size-chart-for-woocommerce' ) . '</div>';
			endif;

			if ( isset( $plugin['updated'] ) ) :
				echo '<div class="column-compatibility"><strong>' . esc_html__( 'Last Updated:', 'size-chart-for-woocommerce' ) . '</strong> ' . esc_html( human_time_diff( $plugin['updated'] ) ) . ' ' . esc_html__( 'ago', 'size-chart-for-woocommerce' ) . '</div>';
			endif;

			echo '</div></div>';
		}


		/**
		 * Check if a plugin is installed.
		 *
		 * @param string $slug Plugin slug.
		 * @param string $file Plugin main file.
		 * @return bool
		 */
		public function jthemes_is_plugin_installed( $slug, $file ) {
			return file_exists( WP_PLUGIN_DIR . "/{$slug}/{$file}" );
		}

		/**
		 * Check if a plugin is active.
		 *
		 * @param string $slug Plugin slug.
		 * @param string $file Plugin main file.
		 * @return bool
		 */
		public function jthemes_is_plugin_active( $slug, $file ) {
			return is_plugin_active( "{$slug}/{$file}" );
		}

		/**
		 * Generate plugin install/activate/deactivate action URL.
		 *
		 * @param string $action Action type (install|activate|deactivate).
		 * @param string $slug Plugin slug.
		 * @param string $file Plugin main file.
		 * @return string
		 */
		public function jthemes_plugin_link( $action, $slug, $file ) {
			$plugin = "{$slug}/{$file}";

			if ( $action === 'activate' || $action === 'deactivate' ) :
				return wp_nonce_url(
					admin_url( "plugins.php?action={$action}&plugin={$plugin}" ),
					"{$action}-plugin_{$plugin}"
				);
			endif;

			// Fallback (optional): use current page if needed
			return admin_url( "admin.php?page=jthemes" );
		}

	}

	// Instantiate the SCWC_Dashboard class.
	new SCWC_Dashboard();
}
