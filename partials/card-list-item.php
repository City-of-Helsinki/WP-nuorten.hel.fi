<?php
/**
 * Card list item partial
 *
 * Accepts args:
 *  - post_object: <object>
 */

$args = wp_parse_args( $args, [
    'post_object' => (object) [],
] );

$white_bg_class     = empty( $args['post_object']->background_class )
    ? " has-bg-color has-bg-color--white {$args['post_object']->color_theme} has-bg-image"
    : " {$args['post_object']->color_theme}";
$grid_columns_class = $args['post_object']->is_grid_column ? ' grid-columns__column' : '';
?>

<li class="card-list-item<?php echo esc_attr( $grid_columns_class ); ?><?php echo esc_attr( $args['post_object']->background_class ); ?>">
    <div class="card-list-item__inner-container<?php echo esc_attr( $args['post_object']->background_class ); ?>">
        <?php if ( empty( $args['post_object']->background_class ) ) : ?>
            <figure class="img-container">
                <?php
                echo wp_get_attachment_image( $args['post_object']->image['id'], 'large' );
                ?>
            </figure>
        <?php endif; ?>

        <div class="card-list-item__content has-dash<?php echo esc_attr( $white_bg_class ); ?>">
            <h3 class="card-list-item-title">
                <a
                    href="<?php echo esc_url( get_permalink( $args['post_object'] ) ); ?>"
                    class="card-list-item-title__link"
                >
                    <?php echo esc_html( $args['post_object']->post_title ); ?>
                </a>
            </h3>

            <?php
            if ( ! empty( $args['post_object']->post_excerpt ) && $args['post_object']->show_excerpt ) {
                echo wp_kses_post( apply_filters( 'the_content', $args['post_object']->post_excerpt ) );
            }
            ?>
        </div>
    </div>
</li>
