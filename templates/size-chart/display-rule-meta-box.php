<?php
// Exit if accessed directly
defined( 'ABSPATH' ) || exit; ?>

<label><?php esc_html_e( 'Assign', 'size-chart-for-woocommerce' ); ?></label>
<div>
    <div class="scwc_assign_wrap">
        <select name="scwc_assign" id="scwc_assign" class="scwc_assign" data-hide=".scwc_assign_option">
            <?php foreach ( $assign_options as $key => $val ) : ?>
                <option value="<?php echo esc_attr( $key ); ?>"
                        data-show=".<?php echo esc_attr( $key ); ?>"
                    <?php selected( $key === $scwc_assign ); ?>
                    <?php $disabled_keys = apply_filters( 'scwc_disabled_assign_options', [ 'combined', 'product_type', 'product_visibility', 'product_tag', 'shipping_class' ] );
                        disabled( in_array( $key, $disabled_keys, true ) ); ?>>
                    <?php echo esc_html( $val ); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <!-- Products Pane -->
        <div class="scwc_assign_option products scwc_assign_pane"
            style="<?php echo esc_attr( ( isset( $scwc_assign ) && $scwc_assign == 'products' ) ? '' : 'display: none;' ); ?>"
            data-option="products">
            <select id="scwc_assign_products" name="scwc_assign_products[]"
                class="wc-product-search"
                multiple="multiple"
                data-placeholder="<?php esc_attr_e( 'Search for a product&hellip;', 'size-chart-for-woocommerce' ); ?>"
                data-action="woocommerce_json_search_products"
                data-include_variations="false">
                <?php
                if ( 'products' === $scwc_assign ) :
                    foreach ( $scwc_condition as $val ) :
                        $parts = explode( ':', $val );
                        if ( count( $parts ) >= 2 && $parts[0] === 'products' ) :
                            $product_id = absint( $parts[1] );
                            $product    = wc_get_product( $product_id );
                            if ( $product && $product->get_type() !== 'variation' ) :
                                ?>
                                <option value="<?php echo esc_attr( $product_id ); ?>" selected="selected">
                                    <?php echo esc_html( $product->get_name() ); ?>
                                </option>
                                <?php
                            endif;
                        endif;
                    endforeach;
                endif; ?>
            </select>
        </div>

        <!-- Product Category Pane -->
        <div class="scwc_assign_option product_cat scwc_assign_pane"
            style="<?php echo esc_attr( ( isset( $scwc_assign ) && $scwc_assign == 'product_cat' ) ? '' : 'display: none;' ); ?>"
            data-option="product_cat">
            <label for="scwc_assign_product_cat"><?php esc_html_e( 'Product Categories', 'size-chart-for-woocommerce' ); ?></label>
            <select id="scwc_assign_product_cat" name="scwc_assign_product_cat[]"
                class="wc-enhanced-select"
                multiple="multiple"
                data-placeholder="<?php esc_attr_e( 'Select categories&hellip;', 'size-chart-for-woocommerce' ); ?>">
                <option value=""></option>
                <?php
                // Get all product categories
                $terms = get_terms( [
                    'taxonomy'   => 'product_cat',
                    'hide_empty' => false,
                ] );

                $selected_terms = [];

                if ( 'product_cat' === $scwc_assign ) :
                    foreach ( $scwc_condition as $val ) :
                        $parts = explode( ':', $val );
                        if ( count( $parts ) >= 2 && $parts[0] === 'product_cat' ) :
                            $selected_terms[] = absint( $parts[1] );
                        endif;
                    endforeach;
                endif;

                // Render all categories
                foreach ( $terms as $term ) :
                    $selected = in_array( $term->term_id, $selected_terms, true ) ? 'selected="selected"' : '';
                    echo '<option value="' . esc_attr( $term->term_id ) . '" ' . esc_attr( $selected ) . '>' . esc_html( $term->name ) . '</option>';
                endforeach; ?>
            </select>

        </div>

        <?php do_action( 'scwc_assign_additional_fields', $scwc_assign, $scwc_condition ); ?>

    </div>
</div>
