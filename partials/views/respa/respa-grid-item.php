<?php
/**
 * Respa grid item
 */

?>


<li class="card-list-item card-list-item--respa grid-columns__column">
    <div class="card-list-item__inner-container">
        <?php if ( ! empty( $args['image'] ) ) : ?>
            <figure class="img-container">
                <img src="<?php echo esc_url( $args['image'] ); ?>" class="objectfit-image"
                     alt="">
            </figure>
        <?php endif; ?>

        <div class="card-list-item__content">
            <h3 class="card-list-item-title">
                <a
                    href="<?php echo esc_url( $args['url'] ); ?>"
                    class="card-list-item-title__link"
                >
                    <?php echo esc_html( $args['name'] ); ?>
                </a>
            </h3>

            <?php
            if ( ! empty( $args['desc'] ) ) {
                echo wp_kses_post( apply_filters( 'the_content', $args['desc'] ) );
            }
            ?>

            <span class="respa-space-price">
                <?php get_template_part( 'partials/icon', '', [ 'icon' => 'ticket' ] ); ?>
                <?php echo esc_html( $args['price'] ); ?>
            </span>
        </div>
    </div>
</li>
