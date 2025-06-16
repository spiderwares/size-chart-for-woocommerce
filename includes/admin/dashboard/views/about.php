<?php
// Ensure that the script is being accessed within WordPress
if ( ! defined( 'ABSPATH' ) ) :
    exit; // Exit if accessed directly
endif;
?>

<div class="scwc_admin_page scwc_welcome_page wrap scwc_admin_settings_page">

    <div class="card">
    <!-- Display the plugin title and version -->
        <h1 class="title">
            <?php 
            // Output the plugin title, version, and premium label (if applicable).
            echo esc_html__( 'Size Chart For WooCommerce', 'size-chart-for-woocommerce' ) . ' ' . esc_html( SCWC_VERSION ) ; 
            ?>
        </h1>

        <!-- Plugin description and external links -->
        <div class="scwc_settings_page_desc about-text">
            <p>
                <?php 
                // Translators: %s is replaced with a five-star rating HTML.
                printf( 
                    esc_html__( 'Thank you for choosing our plugin! If you’re happy with its performance, we’d be grateful if you could give us a five-star %s rating. Your support helps us improve and deliver even better features.', 'size-chart-for-woocommerce' ), 
                    '<span style="color:#ff0000">&#9733;&#9733;&#9733;&#9733;&#9733;</span>' 
                );
                ?>
                <br/>
                <!-- Add links to reviews, changelog, and discussion pages -->
                <a href="<?php echo esc_url( SCWC_REVIEWS ); ?>" target="_blank"><?php esc_html_e( 'Reviews', 'size-chart-for-woocommerce' ); ?></a> |
                <a href="<?php echo esc_url( SCWC_CHANGELOG ); ?>" target="_blank"><?php esc_html_e( 'Changelog', 'size-chart-for-woocommerce' ); ?></a> |
                <a href="<?php echo esc_url( SCWC_DISCUSSION ); ?>" target="_blank"><?php esc_html_e( 'Discussion', 'size-chart-for-woocommerce' ); ?></a>
            </p>
        </div>
    </div>

    <!-- Content area for the active settings tab -->
    <div class="scwc_admin_settings_page_content">
        <?php
        // Load the content for the currently active tab dynamically.
        require_once SCWC_PATH . 'includes/admin/dashboard/views/about.php';
        ?>
    </div>

</div>
