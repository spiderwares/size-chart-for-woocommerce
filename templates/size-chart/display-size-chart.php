<?php
/**
 * Display Size Chart Template
 *
 * @var int $post_id
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) :
    exit;
endif; 

// Hook: Before size chart output
do_action( 'scwc_before_size_chart_display', $post_id ); ?>

<?php if ( ! empty( $top_description ) ) : ?>
    <div class="scwc-top-desc"><?php echo wp_kses_post( wpautop( $top_description ) ); ?></div>
<?php endif; ?>

<?php if ( ! empty( $table_data ) && is_array( $table_data ) ) : ?>
    <div class="scwc-sizeguide-table">

        <?php do_action( 'scwc_before_tables', $table_titles ); ?>

        <?php foreach ( $table_data as $table_index => $single_table ) : ?>
            <?php if ( ! empty( $single_table ) && is_array( $single_table ) ) : ?>
                <table class="scwc-size-chart-table option-<?php echo esc_attr($table_index); ?>" style="margin-bottom: 30px; <?php echo $table_index === 0 ? '' : 'display:none;'; ?>">
                    <?php foreach ( $single_table as $row_index => $row ) : ?>
                        <tr>
                            <?php foreach ( $row as $cell ) :
                                $tag       = ( $row_index === 0 ) ? 'th' : 'td';
                                $row_style = ( $row_index === 0 ) ? $style['heading'] : ( $row_index % 2 === 0 ? $style['even'] : $style['odd'] ); ?>
                                <<?php echo esc_html( $tag ); ?>
                                    style="background-color: <?php echo esc_attr( $row_style['bgcolor'] ); ?>;
                                        color: <?php echo esc_attr( $row_style['color'] ); ?>;
                                        padding: 8px;
                                        text-align: center;">
                                    <?php echo esc_html( $cell ); ?>
                                </<?php echo esc_html( $tag ); ?>>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php if ( ! empty( $bottom_notes ) ) : ?>
    <div class="scwc-bottom-notes"><?php echo wp_kses_post( wpautop( $bottom_notes ) ); ?></div>
<?php endif; ?>