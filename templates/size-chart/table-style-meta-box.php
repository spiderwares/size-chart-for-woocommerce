<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit; ?>
<table class="form-table scwc-size-chart-styles">
    <tr>
        <th scope="row"><?php esc_html_e( 'Header Colors', 'size-chart-for-woocommerce' ); ?></th>
        <td>
            <div class="scwc-color-picker-row">
                <div class="scwc-size-color-wrapper">
                    <label><?php esc_html_e( 'Background Color', 'size-chart-for-woocommerce' ); ?></label>
                    <input type="text" class="scwc-color-picker" name="scwc_size_chart_style[heading][bgcolor]" value="<?php echo esc_attr( $heading_bgcolor ?? '#e0f2fe' ); ?>" />
                </div>
                <div class="scwc-size-color-wrapper">
                    <label><?php esc_html_e( 'Text Color', 'size-chart-for-woocommerce' ); ?></label>
                    <input type="text" class="scwc-color-picker" name="scwc_size_chart_style[heading][color]" value="<?php echo esc_attr( $heading_color ?? '#111827' ); ?>" />
                </div>
            </div>
        </td>
    </tr>

    <tr>
        <th scope="row"><?php esc_html_e( 'Odd Row Colors', 'size-chart-for-woocommerce' ); ?></th>
        <td>
            <div class="scwc-color-picker-row">
                <div class="scwc-size-color-wrapper">
                    <label><?php esc_html_e( 'Background Color', 'size-chart-for-woocommerce' ); ?></label>
                    <input type="text" class="scwc-color-picker" name="scwc_size_chart_style[odd][bgcolor]" value="<?php echo esc_attr( $odd_row_bgcolor ?? '#ffffff' ); ?>" />
                </div>
                <div class="scwc-size-color-wrapper">
                    <label><?php esc_html_e( 'Text Color', 'size-chart-for-woocommerce' ); ?></label>
                    <input type="text" class="scwc-color-picker" name="scwc_size_chart_style[odd][color]" value="<?php echo esc_attr( $odd_row_color ?? '#374151' ); ?>" />
                </div>
            </div>
        </td>
    </tr>

    <tr>
        <th scope="row"><?php esc_html_e( 'Even Row Colors', 'size-chart-for-woocommerce' ); ?></th>
        <td>
            <div class="scwc-color-picker-row">
                <div class="scwc-size-color-wrapper">
                    <label><?php esc_html_e( 'Background Color', 'size-chart-for-woocommerce' ); ?></label>
                    <input type="text" class="scwc-color-picker" name="scwc_size_chart_style[even][bgcolor]" value="<?php echo esc_attr( $even_row_bgcolor ?? '#f7f9fc' ); ?>" />
                </div>
                <div class="scwc-size-color-wrapper">
                    <label><?php esc_html_e( 'Text Color', 'size-chart-for-woocommerce' ); ?></label>
                    <input type="text" class="scwc-color-picker" name="scwc_size_chart_style[even][color]" value="<?php echo esc_attr( $even_row_color ?? '#111827' ); ?>" />
                </div>
            </div>
        </td>
    </tr>
</table>
