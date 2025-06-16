<?php
// Exit if accessed directly
defined( 'ABSPATH' ) || exit; ?>

<div class="scwc-size-chart-config">

    <?php wp_nonce_field( 'scwc_save_meta_box', 'scwc_meta_box_nonce' ); ?>
    
    <!-- Top Description Editor -->
    <div class="scwc-section">
        <h4><?php esc_html_e( 'Top Description', 'size-chart-for-woocommerce' ); ?></h4>
        <div class="scwc-editor-wrapper">
            <?php
            wp_editor( $top_description, 'scwc_top_description', [
                'textarea_name' => 'scwc_top_description',
                'textarea_rows' => 8,
                'editor_class'  => 'scwc-wp-editor',
            ] );
            ?>
        </div>
    </div>

    <!-- Chart Table Builder -->
    <div class="scwc-section">
        <h4><?php esc_html_e( 'Chart Table', 'size-chart-for-woocommerce' ); ?></h4>
        <input type="hidden" name="scwc_table_data" id="scwc_table_data" value='<?php echo esc_attr( json_encode( $table_data_arr ) ); ?>' />
        <div class="scwc-table-container">
            <table class="scwc-table-form">
                <thead>
                    <tr>
                        <th><?php esc_html_e( 'Actions', 'size-chart-for-woocommerce' ); ?></th>
                        <?php if ( !empty( $table_data_arr[0] ) ) : ?>
                            <?php foreach ( $table_data_arr[0] as $col_index => $col_val ) : ?>
                                <th>
                                    <div class="scwc-col-actions">
                                        <button type="button" class="button scwc-add-col"><?php esc_html_e( 'Add', 'size-chart-for-woocommerce' ); ?></button>
                                        <button type="button" class="button scwc-remove-col"><?php esc_html_e( 'Remove', 'size-chart-for-woocommerce' ); ?></button>
                                    </div>
                                </th>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ( $table_data_arr as $row_index => $row ) : ?>
                        <tr>
                            <td>
                                <div class="scwc-row-actions">
                                    <button type="button" class="button scwc-add-row"><?php esc_html_e( 'Add', 'size-chart-for-woocommerce' ); ?></button>
                                    <button type="button" class="button scwc-remove-row"><?php esc_html_e( 'Remove', 'size-chart-for-woocommerce' ); ?></button>
                                </div>
                            </td>
                            <?php foreach ( $row as $cell ) : ?>
                                <td>
                                    <input type="text" name="scwc_table_cell[]" class="scwc-cell-input" value="<?php echo esc_attr( $cell ); ?>" />
                                </td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Bottom Notes Editor -->
    <div class="scwc-section">
        <h4><?php esc_html_e( 'Bottom Description', 'size-chart-for-woocommerce' ); ?></h4>
        <div class="scwc-editor-wrapper">
            <?php
            wp_editor( $bottom_notes, 'scwc_bottom_notes', [
                'textarea_name' => 'scwc_bottom_notes',
                'textarea_rows' => 8,
                'editor_class'  => 'scwc-wp-editor',
            ] );
            ?>
        </div>
    </div>

</div>