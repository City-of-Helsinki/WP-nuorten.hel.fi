<?php
/**
 * Card list item partial
 */

$white_bg_class = empty( $args->background_class )
    ? " has-bg-color has-bg-color--white {$args->color_theme} has-bg-image"
    : " {$args->color_theme}";

$published_string_format = '<time class="datetime published" datetime="%1$s">%2$s</time>';
$published_time_string   = sprintf(
    $published_string_format,
    esc_attr( $args->publish_date ),
    esc_html( $args->publish_date )
);
?>

<li class="card-list-item card-list-item--initiative grid-columns__column<?php echo esc_attr( $args->background_class ); ?>">
    <div class="card-list-item__inner-container<?php echo esc_attr( $args->background_class ); ?>">
        <?php if ( empty( $args->background_class ) ) : ?>
            <figure class="img-container">
                <?php
                echo wp_get_attachment_image( $args->image['id'], 'large' );
                ?>
            </figure>
        <?php endif; ?>

        <div class="card-list-item__content has-dash<?php echo esc_attr( $white_bg_class ); ?>">

            <?php if ( ! empty( $args->status ) ) : ?>
                <span class="keyword initiative-status initiative-status--<?php echo esc_attr( $args->status['class'] ); ?>">
                    <?php echo esc_html( $args->status['text'] ); ?>
                </span>
            <?php endif; ?>

            <h3 class="card-list-item-title">
                <a
                    href="<?php echo esc_url( get_permalink( $args ) ); ?>"
                    class="card-list-item-title__link"
                >
                    <?php echo esc_html( $args->post_title ); ?>
                    <?php if ( ! empty( $args->status ) ) : ?>
                        <span class="is-sr-only">
                            <?php echo esc_html( _x( 'Initiative', 'theme CPT Singular Name', 'nuhe' ) ) . ' - ' . esc_html( $args->status['text'] ); ?>
                        </span>
                    <?php endif; ?>
                </a>
            </h3>

            <?php
            echo wp_kses(
                $published_time_string,
                [
                    'time' => [
                        'class'    => [],
                        'datetime' => [],
                    ],
                ]
            );
            ?>

            <?php
            if ( ! empty( $args->post_excerpt ) && $args->show_excerpt ) {
                echo wp_kses_post( apply_filters( 'the_content', $args->post_excerpt ) );
            }
            ?>
        </div>
    </div>
</li>
