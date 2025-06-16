<?php
/**
 *  Repeater Field Template
 */

 // Prevent direct access to the file.
defined( 'ABSPATH' ) || exit; ?>

<td class="forminp">
    <div id="scwc_req_input" class="scwc-datainputs">
        <?php if ( ! empty( $field_Val ) && is_array( $field_Val ) ) : ?>
            <?php foreach ( $field_Val as $index => $data ) : ?>
                <div class="scwc-input-group" style="margin-bottom: 10px;">
                    <input name="<?php echo isset( $field['name'] ) ? esc_attr( $field['name'] ) : ''; ?>[<?php echo esc_attr($index); ?>][label]" value="<?php echo esc_attr( $data['label'] ?? '' ); ?>" placeholder="<?php esc_attr_e( 'Meta Label', 'size-chart-for-woocommerce' ); ?>" type="text" style="margin-right: 10px;">
                    <input name="<?php echo isset( $field['name'] ) ? esc_attr( $field['name'] ) : ''; ?>[<?php echo esc_attr($index); ?>][key]" value="<?php echo esc_attr( $data['key'] ?? '' ); ?>" placeholder="<?php esc_attr_e( 'Meta Key', 'size-chart-for-woocommerce' ); ?>" type="text">
                    <?php if( $index > 0 ) : ?>
                        <button type="button" class="scwc-remove-field button"> <?php esc_html_e( 'Remove', 'size-chart-for-woocommerce' ); ?> </button>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <div class="scwc-input-group" style="margin-bottom: 10px;">
                <input name="<?php echo isset( $field['name'] ) ? esc_attr( $field['name'] ) : ''; ?>[0][label]" placeholder="<?php esc_attr_e( 'Meta Label', 'size-chart-for-woocommerce' ); ?>" type="text" style="margin-right: 10px;">
                <input name="<?php echo isset( $field['name'] ) ? esc_attr( $field['name'] ) : ''; ?>[0][key]" placeholder="<?php esc_attr_e( 'Meta Key', 'size-chart-for-woocommerce' ); ?>" type="text">
            </div>
        <?php endif; ?>
    </div>
    <p class="description "><small><?php esc_html_e( 'The "Add Meta on Compare Table" field enables the addition of custom meta labels and keys to be displayed in the product comparison table.', 'size-chart-for-woocommerce' ); ?></small></p>
    <button type="button" id="scwc_add_field" class="button" style="margin-top: 10px;"><?php esc_html_e( 'Add More', 'size-chart-for-woocommerce' ); ?></button>
</td>
