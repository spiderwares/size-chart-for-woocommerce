<?php
if ( ! defined( 'ABSPATH' ) ) :
    exit; 
endif;
?>

<?php foreach ( $fields as $field_Key => $field ) : 
    $field_Val  = isset( $options[ $field_Key ] ) ? $options[ $field_Key ] : $field['default']; 
    $field_type = isset( $field[ 'field_type' ] ) ? $field[ 'field_type' ] : ''; ?>

    <tr class="<?php echo isset( $field['extra_class'] ) ? esc_attr( $field['extra_class'] ) : '';  ?>"

        <?php if( isset($field[ 'style' ] ) && !empty( $field[ 'style' ] ) ): 
            $style = explode('.', $field['style'], 2); ?>
            style="<?php echo esc_attr( ( isset( $options[ $style[0] ] ) && $options[ $style[0] ] == $style[1] ) ? '' : 'display: none;' ); ?>" 
        <?php endif; ?> >
        
        <th scope="row" class="scwc-label <?php echo esc_attr( $field_type ); ?>" <?php echo ( $field_type === 'scwctitle' ) ? 'colspan="2"' : ''; ?>>
            <?php echo esc_html( $field['title'] ); ?>
        </th>
        <?php
        switch ( $field['field_type'] ) {

            case "scwctext" : 
                wc_get_template(
                    'fields/text-field.php',
                    array(
                        'field'         => $field,
                        'field_Val'     => $field_Val,
                        'field_Key'     => $field_Key
                    ),
                    'essential-tool-for-woocommerce/',
                    SCWC_TEMPLATE_PATH
                );
                break;

            case "scwcswitch":
                wc_get_template(
                    'fields/switch-field.php',
                    array(
                        'field'     => $field,
                        'field_Val' => $field_Val,
                        'field_Key' => $field_Key,
                    ),
                    'essential-tool-for-woocommerce/',
                    SCWC_TEMPLATE_PATH
                );
                break;

            case "scwcselect":
                wc_get_template(
                    'fields/select-field.php', 
                    array(
                        'field'     => $field,
                        'field_Val' => $field_Val,
                        'field_Key' => $field_Key,
                    ),
                    'essential-tool-for-woocommerce/',
                    SCWC_TEMPLATE_PATH
                );
                break;

            case "scwcswitch":
                wc_get_template(
                    'fields/switch-field.php', 
                    array(
                        'field'     => $field,
                        'field_Val' => $field_Val,
                        'field_Key' => $field_Key,
                    ),
                    'essential-tool-for-woocommerce/',
                    SCWC_TEMPLATE_PATH
                );
                break;

            case "scwcbuypro":
                wc_get_template(
                    'fields/buy-pro-field.php',
                    array(
                        'field' => $field
                    ),
                    'essential-tool-for-woocommerce/',
                    SCWC_TEMPLATE_PATH
                );
                break;
                
            case "scwccolor":
                wc_get_template(
                    'fields/color-field.php', 
                    array(
                        'field'     => $field,
                        'field_Val' => $field_Val,
                        'field_Key' => $field_Key,
                    ),
                    'essential-tool-for-woocommerce/',
                    SCWC_TEMPLATE_PATH
                );
                break;

            case "scwcrepeater":
                wc_get_template(
                    'fields/repeater-field.php',
                    array(
                        'field'     => $field,
                        'field_Val' => $field_Val,
                        'field_Key' => $field_Key,
                    ),
                    'essential-tool-for-woocommerce/',
                    SCWC_TEMPLATE_PATH
                );
                break;

            case "scwcnumber":
                wc_get_template(
                    'fields/number-field.php',
                    array(
                        'field'     => $field,
                        'field_Val' => $field_Val,
                        'field_Key' => $field_Key,
                    ),
                    'essential-tool-for-woocommerce/',
                    SCWC_TEMPLATE_PATH
                );
                break;

            case "scwctextarea":
                wc_get_template(
                    'fields/textarea-field.php',
                    array(
                        'field'     => $field,
                        'field_Val' => $field_Val,
                        'field_Key' => $field_Key,
                    ),
                    'essential-tool-for-woocommerce/',
                    SCWC_TEMPLATE_PATH
                );
                break;

            case "scwcsize":
                wc_get_template(
                    'fields/image-size-field.php',
                    array(
                        'field'     => $field,
                        'field_Val' => $field_Val,
                        'field_Key' => $field_Key,
                    ),
                    'essential-tool-for-woocommerce/',
                    SCWC_TEMPLATE_PATH
                );
                break;
            

        }
        ?>
    </tr>

<?php endforeach; ?>