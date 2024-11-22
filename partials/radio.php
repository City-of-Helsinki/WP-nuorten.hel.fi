<?php
/**
 * HDS radio button partial
 *
 * Accepts args:
 *  - checked: <bool>
 *  - disabled: <bool>
 *  - label: <string>
 *  - value: <string>
 *  - name: <string>
 *  - id: <string>
 */

$args = wp_parse_args( $args, [
    'checked'  => false,
    'disabled' => false,
    'label'    => '',
    'value'    => '',
    'name'     => '',
    'id'       => '',
] );

$id = empty( $args['id'] ) ? uniqid( $args['name'] ) : $args['name'];
?>
<div class="hds-radio-button">
    <input 
        type="radio"
        id="<?php echo esc_attr( $id ); ?>"
        name="<?php echo esc_attr( $args['name'] ); ?>"
        class="hds-radio-button__input"
        value="<?php echo esc_attr( $args['value'] ); ?>"
        <?php checked( $args['checked'], true ); ?>
        <?php disabled( $args['disabled'], true ); ?>
    >
    <label 
        for="<?php echo esc_attr( $id ); ?>"
        class="hds-radio-button__label">
        <?php echo esc_html( $args['label'] ); ?>
    </label>
</div>
