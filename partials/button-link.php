<?php
/**
 * Button link based on HDS Button partial
 *
 * Accepts args:
 *  - classes: <array of classnames without prefix, eg 'primary'>
 *  - attributes: <associative array of attributes and values>
 *  - label: <string>
 */

/**
 * Partial args
 *
 * @var array $args
 */
$args = wp_parse_args( $args, [
    'classes'    => [],
    'url'        => '',
    'attributes' => [],
    'icon-start' => '',
    'icon-end'   => '',
    'label'      => '',
    'target'     => '',
] );

$is_active_class = isset( $args['is_active'] ) && $args['is_active'] ? ' is-active' : '';
?>
<a
    href="<?php echo esc_url( $args['url'] ); ?>"
    class="hds-button hds-button-link<?php echo esc_attr( $is_active_class ); ?> <?php echo esc_attr( hds_get_prefixed_classes_string( $args['classes'], 'hds-button--' ) ); ?>"
    target="<?php echo esc_attr( $args['target'] ); ?>"
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
        ] );
    }
    else {
        if ( hds_is_external_link( $args['url'] ) ) {
            get_template_part( 'partials/icon', '', [
                'icon' => 'link-external',
            ] );
        }
    }
    ?>
    <span class="hds-button__label"><?php echo esc_attr( $args['label'] ); ?></span>
    <?php
    // End icon, if it exists.
    if ( ! empty( $args['icon-end'] ) ) {
        get_template_part( 'partials/icon', '', [
            'icon' => $args['icon-end'],
        ] );
    }
    ?>
</a>
