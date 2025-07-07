<?php
/**
 * Plugin Name:       Size Chart for WooCommerce
 * Description:       Easily create and display custom size charts on your WooCommerce product pages. Help customers choose the right fit, reduce returns, and enhance their shopping experience.
 * Version:           1.0.2
 * Requires at least: 5.2
 * Requires PHP:      7.4
 * Author:            jthemesstudio
 * Author URI:        https://jthemes.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       size-chart-for-woocommerce
 *
 * @package Size Chart For Woocommerce
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

if ( ! defined( 'SCWC_FILE' ) ) :
    define( 'SCWC_FILE', __FILE__ ); // Define the plugin file path.
endif;

if ( ! defined( 'SCWC_BASENAME' ) ) :
    define( 'SCWC_BASENAME', plugin_basename( SCWC_FILE ) ); // Define the plugin basename.
endif;

if ( ! defined( 'SCWC_VERSION' ) ) :
    define( 'SCWC_VERSION', '1.0.2' ); // Define the plugin version.
endif;

if ( ! defined( 'SCWC_PATH' ) ) :
    define( 'SCWC_PATH', plugin_dir_path( __FILE__ ) ); // Define the plugin directory path.
endif;

if ( ! defined( 'SCWC_TEMPLATE_PATH' ) ) :
	define( 'SCWC_TEMPLATE_PATH', plugin_dir_path( __FILE__ ) . '/templates/' ); // Define the plugin directory path.
endif;

if ( ! defined( 'SCWC_URL' ) ) :
    define( 'SCWC_URL', plugin_dir_url( __FILE__ ) ); // Define the plugin directory URL.
endif;

if ( ! defined( 'SCWC_REVIEWS' ) ) :
    define( 'SCWC_REVIEWS', 'https://jthemes.com/' ); // Define the plugin directory URL.
endif;

if ( ! defined( 'SCWC_CHANGELOG' ) ) :
    define( 'SCWC_CHANGELOG', 'https://jthemes.com/' ); // Define the plugin directory URL.
endif;

if ( ! defined( 'SCWC_DISCUSSION' ) ) :
    define( 'SCWC_DISCUSSION', 'https://jthemes.com/' ); // Define the plugin directory URL.
endif;

if ( ! defined( 'SCWC_PRO_VERSION_URL' ) ) :
    define( 'SCWC_PRO_VERSION_URL', 'https://codecanyon.net/item/woocommerce-product-size-chart-wordpress-plugin/58515654' ); // Define the Pro Version URL.
endif;

if ( ! class_exists( 'SCWC', false ) ) :
    include_once SCWC_PATH . 'includes/class-scwc.php';
endif;

$GLOBALS['scwc'] = SCWC::instance();
