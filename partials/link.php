<?php
/**
 * General-purpose link partial.
 */

use \Geniem\Theme\Traits\Link;

/**
 * Partial args
 *
 * @var array $args
 */
$args = wp_parse_args( $args, [
    'url'              => '',
    'title'            => '',
    'target'           => '',
    'classes'          => [],
    'icon'             => '',
    'icon-end'         => '',
    'icon-start'       => '',
    'aria-label'       => '',
    'disable_external' => false,
    'icon_size'        => 's',
] );

if ( empty( $args['url'] ) ) {
    return;
}

if ( $args['target'] === '_blank' && empty( $args['icon'] ) ) {
    $args['icon-start'] = 'link-external';
}

if ( hds_is_external_link( $args['url'] ) && ! $args['disable_external'] ) {
    $args['icon-start'] = 'link-external';
}

$classes = hds_get_prefixed_classes_string( $args['classes'] );

$classes .= ! empty( $args['icon-start'] ) ? ' link--icon-start' : '';
$classes .= ! empty( $args['icon-end'] ) ? ' link--icon-end' : '';
?>
<a href="<?php echo esc_url( $args['url'] ); ?>" class="link <?php echo esc_attr( $classes ); ?>"
    target="<?php echo esc_attr( $args['target'] ); ?>">

    <?php if ( ! empty( $args['icon-start'] ) ) : ?>
        <?php
        get_template_part( 'partials/icon', '', [
            'icon' => $args['icon-start'],
            'size' => 's',
        ] );
        ?>
    <?php endif; ?>

    <?php if ( ! empty( $args['title'] ) ) : ?>
        <span> <?php echo esc_html( $args['title'] ); ?> </span>
    <?php endif; ?>

    <?php if ( $args['target'] === '_blank' && ! $args['disable_external'] ) : ?>
        <span class="hiddenFromScreen"><?php esc_html_e( 'Opens in new tab', 'nuhe' ); ?></span>
    <?php endif; ?>

    <?php if ( $args['aria-label'] !== '' ) : ?>
        <span class="hiddenFromScreen"><?php echo esc_html( $args['aria-label'] ); ?></span>
    <?php endif; ?>

    <?php if ( ! empty( $args['icon-end'] ) ) : ?>
        <?php
        get_template_part( 'partials/icon', '', [
            'icon' => $args['icon-end'],
            'size' => $args['icon_size'],
        ] );
        ?>
    <?php endif; ?>

    <?php if ( ! empty( $args['icon'] ) ) : ?>
        <?php
        get_template_part( 'partials/icon', '', [
            'icon' => $args['icon'],
            'size' => $args['icon_size'],
        ] );
        ?>
    <?php endif; ?>
</a>
