<?php
/**
 * Image text partial
 */

$args = wp_parse_args( $args );

$has_image = ! empty( $args['image'] );

$grid_layout_class = $has_image ? ' grid-columns grid-columns--2' : '';
$column_class      = $has_image ? ' grid-columns__column' : '';

?>

<div class="nuhe-layout nuhe-layout--main-content nuhe-image-text">
    <div class="container<?php echo esc_attr( $grid_layout_class ); ?>">
        <?php if ( $args['image_position'] && $has_image ) : ?>
            <div class="image-content<?php echo esc_attr( $column_class ); ?>">
                <figure class="img-container">
                    <?php
                        echo wp_get_attachment_image( $args['image']['id'], 'large' );
                    ?>
                </figure>
            </div>
        <?php endif; ?>
        <div class="text-content<?php echo esc_attr( $column_class ); ?>">
            <?php if ( ! empty( $args['title'] ) ) : ?>
                <h2 class="nuhe-image-text__title">
                    <?php echo esc_html( $args['title'] ); ?>
                </h2>
            <?php endif; ?>

            <?php if ( ! empty( $args['description'] ) ) : ?>
                <div class="nuhe-image-text__description">
                    <?php echo wp_kses_post( $args['description'] ); ?>
                </div>
            <?php endif; ?>

            <?php
            get_template_part( 'partials/links', '', [
                'list_classes' => [ 'image-text-ctas' ],
                'links'        => $args['ctas'],
                'type'         => 'button',
                'classes'      => [ 'outlined', 'border-black' ],
            ] );
            ?>
        </div>
        <?php if ( empty( $args['image_position'] ) && $has_image ) : ?>
            <div class="image-content<?php echo esc_attr( $column_class ); ?>">
                <figure class="img-container">
                    <?php
                        echo wp_get_attachment_image( $args['image']['id'], 'large' );
                    ?>
                </figure>
            </div>
        <?php endif; ?>
    </div>
</div>
