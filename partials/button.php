<?php
/**
 * HDS Button partial
 *
 * Accepts args:
 *  - classes: <array of classnames without prefix, eg 'primary'>
 *  - attributes: <associative array of attributes and values>
 *  - icon-start: <icon-name>
 *  - icon-end: <icon-name>
 *  - label: <string>
 */

/**
 * Partial args
 *
 * @var array $args
 */
$args = wp_parse_args( $args, [
    'classes'               => [],
    'attributes'            => [],
    'icon-start'            => '',
    'icon-end'              => '',
    'label'                 => '',
    'type'                  => 'button',
    'icon_size'             => 'm',
    'clipboard_copied_text' => '',
] );

?>
<button
    type="<?php echo esc_attr( $args['type'] ); ?>"
    class="hds-button <?php echo esc_attr( hds_get_prefixed_classes_string( $args['classes'], 'hds-button--' ) ); ?>"
    <?php
    // Ignoring coding standards here because attributes are already escaped for use in attributes.
    // @codingStandardsIgnoreStart
    echo hds_get_escaped_attributes_string( $args['attributes'] );
    // @codingStandardsIgnoreEnd
    ?>
>
    <?php
    // Start icon, if it exists.
    if ( ! empty( $args['icon-start'] ) ) {
        get_template_part( 'partials/icon', '', [
            'icon' => $args['icon-start'],
            'size' => $args['icon_size'],
        ] );
    }
    ?>
    <?php if ( ! empty( $args['label'] ) ) : ?>
        <span class="hds-button__label"><?php echo esc_attr( $args['label'] ); ?></span>
    <?php endif; ?>

    <?php
    // End icon, if it exists.
    if ( ! empty( $args['icon-end'] ) ) {
        get_template_part( 'partials/icon', '', [
            'icon' => $args['icon-end'],
            'size' => $args['icon_size'],
        ] );
    }

    if ( $args['clipboard_copied_text'] !== '' ) {
        ?>
    <span
        tabindex="0"
        id="js-clipboard-tooltip"
        class="clipboard-tooltip is-hidden"
        aria-label="<?php echo esc_attr( $args['clipboard_copied_text'] ); ?>"
    >
        <?php echo esc_html( $args['clipboard_copied_text'] ); ?>
    </span>
    <?php } ?>
</button>
