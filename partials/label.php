<?php
/**
 * HDS Label partial
 *
 * Accepts args:
 *  - classes: <Array of strings>
 *  - label: <string>
 */

$args = wp_parse_args( $args, [
    'classes' => [],
    'label'   => '',
] );
?>
<span class="hds-status-label <?php echo esc_attr( hds_get_prefixed_classes_string( $args['classes'], 'hds-status-label--' ) ); ?>">
    <?php echo esc_html( $args['label'] ); ?>
</span>
