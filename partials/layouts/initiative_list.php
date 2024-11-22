<?php
/**
 * Initiative list partial
 *
 * Accepts args:
 *  - data: <array>
 */

$args = wp_parse_args( $args, [
    'data' => [],
] );

$bg_color  = $args['bg_color'] ?? 'white';
$has_koro  = $bg_color === 'grey';
$anchor_id = ! empty( $args['anchor_id'] ) ? ' id="' . $args['anchor_id'] . '"' : '';

$layout_classes = [
    'nuhe-layout',
    'nuhe-layout--main-content',
    'nuhe-card-list',
    'nuhe-initiative-list',
    'has-bg-color',
    "has-bg-color--{$bg_color}",
    $has_koro ? 'has-koro' : '',
];
$layout_classes = implode( ' ', $layout_classes );

?>

<div class="<?php echo esc_attr( $layout_classes ); ?>"<?php echo wp_kses_post( $anchor_id ); ?>>
    <div class="container">

    <?php if ( ! empty( $args['title'] ) ) : ?>
        <h2 class="nuhe-card-list__title">
            <?php echo esc_html( $args['title'] ); ?>
        </h2>
    <?php endif; ?>

    <?php if ( ! empty( $args['initiatives'] ) ) : ?>
        <ul class="nuhe-card-list__items grid-columns grid-columns--<?php echo esc_attr( $args['columns'] ); ?>">
            <?php
            foreach ( $args['initiatives'] as $initiative ) {
                get_template_part( 'partials/initiative-list-item', '', $initiative );
            }
            ?>
        </ul>
    <?php endif; ?>

    <?php if ( ! empty( $args['link'] ) ) : ?>
        <div class="call-to-action">
            <a
                href="<?php echo esc_url( $args['link']['url'] ); ?>"
                class="call-to-action__link">
                <span class="call-to-action__text">
                    <?php echo esc_html( $args['link']['title'] ); ?>
                </span>

                <?php get_template_part( 'partials/icon', '', [ 'icon' => 'arrow-right' ] ); ?>
            </a>
        </div>
    <?php endif; ?>

    <?php the_posts_pagination(); ?>
    </div>

    <?php if ( $has_koro ) : ?>
        <div class="koro koro--wave koro--grey"></div>
    <?php endif; ?>
</div>
