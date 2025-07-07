<?php
/** 
 *   Buy Pro Field Template
 */

// Prevent direct access to the file.
defined( 'ABSPATH' ) || exit; ?>
<td>
    <div class="scwc-pro-field">
    <p class="description" style="color: #c9356e">
            <?php esc_html_e( 'This feature is only available in the Premium Version.', 'size-chart-for-woocommerce' ); ?>
            <?php 
            /* Translators: %1$s is the opening anchor tag, and %2$s is the closing anchor tag. */
            echo sprintf( esc_html__( 'Click %1$shere%2$s to buy.', 'size-chart-for-woocommerce' ),
                '<a href="' . esc_url( $field['pro_link'] ) . '" target="_blank">',
                '</a>'
            ); 
            ?>
        </p>
    </div>
</td>