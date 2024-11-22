<?php
/**
 * Link list partial
 *
 * Accepts args:
 *  - data: <array>
 */

$args = wp_parse_args( $args, [
    'data' => [],
] );

if ( empty( $args['links'] ) ) {
    return;
}

?>

<div class="links nuhe-layout nuhe-link-list">
    <?php if ( ! empty( $args['title'] ) ) : ?>
        <?php
        get_template_part( 'partials/heading', '', [
            'title' => $args['title'],
            'level' => 'h3',
            'class' => 'links-title',
        ] );
        ?>
    <?php endif; ?>

    <?php if ( ! empty( $args['description'] ) ) : ?>
        <div class="links-description">
            <?php echo wp_kses_post( $args['description'] ); ?>
        </div>
    <?php endif; ?>

    <?php if ( ! empty( $args['links'] ) ) : ?>
        <?php
        get_template_part( 'partials/links', '', [
            'links'      => $args['links'],
            'type'       => 'link',
            'classes'    => [ 'text-bold', 'text-md' ],
            'icon-end'   => '',
            'icon-start' => 'arrow-right',
        ] );
        ?>
    <?php endif; ?>
</div>

