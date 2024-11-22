<?php
/**
 * People list partial
 *
 * Accepts args:
 *  - data: <array>
 */

$args = wp_parse_args( $args, [
    'data' => [],
] );

$grid_columns_class = $args['columns'] !== 1
                    ? " grid-columns grid-columns--{$args['columns']}"
                    : '';
$has_description    = ! empty( $args['description'] );


$layout_classes = [
    'nuhe-layout',
    'nuhe-layout--main-content',
    'nuhe-card-list',
    'nuhe-people-list',
    'has-bg-color',
    'has-bg-color--white',
    $has_description ? 'nuhe-card-list--has-description' : '',
];
$layout_classes = implode( ' ', $layout_classes );
?>

<div class="<?php echo esc_attr( $layout_classes ); ?>">
        <div class="container">
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


            <ul class="nuhe-card-list__items<?php echo esc_attr( $grid_columns_class ); ?>">
                <?php
                foreach ( $args['people'] as $item ) {
                    get_template_part( 'partials/people-list-item', '', $item );
                }
                ?>
            </ul>
        </div>
</div>
