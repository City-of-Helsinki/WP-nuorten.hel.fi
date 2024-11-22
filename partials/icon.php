<?php
/**
 * HDS Icon partial
 *
 * Accepts args:
 *  - icon: <icon-name: e.g. facebook>
 *  - size: <string: xs, s, m, l, xl>
 */

/**
 * Partial args.
 *
 * @var array $args
 */
$args = wp_parse_args( $args, [
    'icon' => '',
    'size' => 'm',
] );

$size = ! empty( $args['size'] ) ? 'hds-icon--size-' . esc_attr( $args['size'] ) : '';
?>
<span aria-hidden="true"
    class="hds-icon hds-icon--<?php echo esc_attr( $args['icon'] ); ?> <?php echo esc_attr( $size ); ?>
    "></span>
