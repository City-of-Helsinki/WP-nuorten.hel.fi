<?php
/**
 * Page list partial
 *
 * Accepts args:
 *  - data: <array>
 */

$args = wp_parse_args( $args, [
    'data' => [],
] );

$grid_columns_class = $args['use_grid'] && $args['columns'] !== 1
                    ? " grid-columns grid-columns--{$args['columns']}"
                    : '';
$bg_color           = $args['bg_color'] ?? 'white';
$has_koro           = $args['context'] === 'main-content' && $bg_color === 'grey';
$anchor_id          = ! empty( $args['anchor_id'] ) ? ' id="' . $args['anchor_id'] . '"' : '';
$has_description    = ! empty( $args['description'] ) && $args['context'] === 'main-content';

$layout_classes = [
    'nuhe-layout',
    "nuhe-layout--{$args['context']}",
    'nuhe-card-list',
    $has_description ? 'nuhe-card-list--has-description' : '',
    'has-bg-color',
    "has-bg-color--{$bg_color}",
    $has_koro ? 'has-koro' : '',
];
$layout_classes = implode( ' ', $layout_classes );
?>

<div class="<?php echo esc_attr( $layout_classes ); ?>"<?php echo wp_kses_post( $anchor_id ); ?>>
    <?php if ( $args['use_container'] ) : ?>
        <div class="container">
    <?php endif; ?> 

        <?php if ( ! empty( $args['title'] ) ) : ?>
            <h2 class="nuhe-card-list__title">
                <?php echo esc_html( $args['title'] ); ?>
            </h2>
        <?php endif; ?>

        <?php if ( $has_description ) : ?>
            <div class="nuhe-card-list__description">
                <?php echo wp_kses_post( $args['description'] ); ?>
            </div>
        <?php endif; ?>

        <?php if ( ! empty( $args['pages'] ) ) : ?>
            <ul class="nuhe-card-list__items<?php echo esc_attr( $grid_columns_class ); ?>">
                <?php
                foreach ( $args['pages'] as $item ) {
                    $object_args = [
                        'post_object' => $item['page'],
                    ];

                    get_template_part( 'partials/card-list-item', '', $object_args );
                }
                ?>
            </ul>
        <?php endif; ?>

        <?php if ( $args['context'] === 'main-content' && ! empty( $args['link'] ) ) : ?>
            <div class="call-to-action">
                <a
                    href="<?php echo esc_url( $args['link']['url'] ); ?>"
                    class="call-to-action__link"
                    target="<?php echo esc_attr( $args['link']['target'] ); ?>">
                    <span class="call-to-action__text">
                        <?php echo esc_html( $args['link']['title'] ); ?>
                    </span>

                    <?php get_template_part( 'partials/icon', '', [ 'icon' => 'arrow-right' ] ); ?>
                </a>
            </div>
        <?php endif; ?>

    <?php if ( $args['use_container'] ) : ?>
        </div>
    <?php endif; ?>

    <?php if ( $args['context'] === 'main-content' && $bg_color === 'grey' ) : ?>
        <div class="koro koro--wave koro--grey"></div>
    <?php endif; ?>
</div>
