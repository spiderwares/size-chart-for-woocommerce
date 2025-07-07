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
        <?php if ( ! empty( $table_data_arr ) && is_array( $table_data_arr ) ) : ?>
            <?php foreach ( $table_data_arr as $table_index => $table ) : ?>
                <div class="scwc-table-container">
                    
                    <?php
                        /**
                         * Fires when rendering a single table title input field.
                         *
                         * @param string $table_title  The current table title.
                         * @param int    $table_index  The index of the current table.
                         */
                        do_action( 'scwc_add_table_title', $table_title, $table_index );
                    ?>
                    
                    <input type="hidden" name="scwc_table_data[]" class="scwc-table-data" value='<?php echo esc_attr( json_encode( $table ) ); ?>' />
                    <table class="scwc-table-form">
                        <thead>
                            <tr>
                                <th><?php esc_html_e( 'Actions', 'size-chart-for-woocommerce' ); ?></th>
                                <?php if ( !empty( $table[0] ) ) : ?>
                                    <?php foreach ( $table[0] as $col_index => $col_val ) : ?>
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
                            <?php foreach ( $table as $row_index => $row ) : ?>
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
            <?php endforeach; ?>
        <?php endif; ?>

        <?php if ( ! class_exists( 'SCWC_PRO' ) ) : ?>
            <p class="scwc-buy-pro" style="color: #c9356e">
                <?php esc_html_e( 'To add multiple size charts and display them in different formats such as inches and centimeters, you need to purchase the Premium Version.', 'size-chart-for-woocommerce' ); ?>
                <?php 
                // Translators: %1$s is the opening anchor tag, and %2$s is the closing anchor tag.
                echo sprintf( esc_html__( 'Click %1$shere%2$s to buy.', 'size-chart-for-woocommerce' ),
                    '<a href="' . esc_url( SCWC_PRO_VERSION_URL ) . '" target="_blank">',
                    '</a>'
                ); 
                ?>
            </p>
        <?php endif; ?>

        <?php
        /**
         * Hook: scwc_add_table_button.
         *
         * Allows output of "Add New Table" button in Pro version.
         */
        do_action( 'scwc_add_table_button' ); ?>

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