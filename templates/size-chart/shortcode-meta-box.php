<?php
/**
 * Shortcode Meta Box Template
 *
 * @var int $post_id
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) :
    exit;
endif; ?>

<div class="scwc-shortcode-wrap">
    <p><?php esc_html_e( 'You can use the shortcode below to display this size chart anywhere on your site.', 'size-chart-for-woocommerce' ); ?></p>
    <label>
        <input type="text"
               onfocus="this.select();"
               readonly="readonly"
               class="code scwc-size-chart-input"
               value="<?php echo esc_attr( sprintf( '[scwc_size_chart id="%d"]', $post_id ) ); ?>" />
    </label>
</div>