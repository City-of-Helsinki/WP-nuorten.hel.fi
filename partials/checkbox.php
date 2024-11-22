<?php
/**
 * HDS checkbox partial
 *
 * Accepts args:
 *  - checked: <bool>
 *  - disabled: <bool>
 *  - label: <string>
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
<div class="hds-checkbox">
    <input
        type="checkbox"
        id="<?php echo esc_attr( $id ); ?>"
        name="<?php echo esc_attr( $args['name'] ); ?>"
        class="hds-checkbox__input"
        value="<?php echo esc_attr( $args['value'] ); ?>"
        <?php checked( $args['checked'], true ); ?>
        <?php disabled( $args['disabled'], true ); ?>
    >
    <label
        for="<?php echo esc_attr( $id ); ?>"
        class="hds-checkbox__label">
        <?php echo esc_html( $args['label'] ); ?>
    </label>
</div>
