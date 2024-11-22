<?php
/**
 * Card list item partial
 *
 */

$args = wp_parse_args( $args );

?>

<li class="grid-columns__column people-list-item">
    <div class="people-list-item__inner-container">
        <figure class="img-container">
            <?php
                echo wp_get_attachment_image( $args['person_image']['id'], 'large' );
            ?>
        </figure>

        <div class="people-list-item__content">
            <h3 class="people-list-item__name">
                <?php echo esc_html( $args['person_name'] ); ?>
            </h3>

            <?php if ( ! empty( $args['person_title'] ) ) : ?>
                <p class="people-list-item__title"><?php echo esc_html( $args['person_title'] ); ?></p>
            <?php endif; ?>

            <?php if ( ! empty( $args['person_description'] ) ) : ?>
                <p class="people-list-item__description">”<?php echo esc_html( $args['person_description'] ); ?>”</p>
            <?php endif; ?>


            <?php if ( ! empty( $args['video_link'] ) && ! empty( $args['video_link']['title'] ) ) : ?>
                <?php
                get_template_part( 'partials/link', '', [
                    'title'      => $args['video_link']['title'],
                    'url'        => $args['video_link']['url'],
                    'target'     => $args['video_link']['target'],
                    'icon-start' => 'angle-right',
                ] );
                ?>
            <?php endif; ?>
        </div>
    </div>
</li>
