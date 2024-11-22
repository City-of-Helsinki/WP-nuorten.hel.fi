<?php
/**
 * Front page hero partial
 */

$args = wp_parse_args( $args, [
    'title'       => '',
    'description' => '',
    'image'       => '',
    'link'        => [],
] );

?>

<div class="glide__slide" aria-roledescription="slide" style="background-image:url(<?php echo esc_url( $args['image'] ); ?>)">
    <div<?php if ( ! empty( $args['title'] ) || ! empty( $args['description'] ) || ! empty( $args['link'] ) ): ?> class="hero-slider__overlay"<?php endif; ?>>
        <div class="hero-slider__grid container">
            <div class="hero-slider-content">
                <?php if ( ! empty( $args['title'] ) ): ?>
                <h1 class="page-heading"><?php echo esc_html( $args['title'] ); ?></h1>
                <?php endif; ?>
                <?php if ( ! empty( $args['description'] ) ): ?>
                <div class="hero-slider-content__description">
                    <p><?php echo wp_kses_post( $args['description'] ); ?></p>
                </div>
                <?php endif; ?>
                <?php if ( ! empty( $args['link'] ) ): ?>
                <ul class="links-list button-links">
                    <li>
                        <a href="<?php echo esc_url( $args['link']['url'] ); ?>" class="hds-button hds-button-link hds-button--outlined hds-button--border-white" target="<?php echo esc_attr( $args['link']['target'] ); ?>">
                            <span class="hds-button__label"><?php echo $args['link']['title'] ? esc_html( $args['link']['title'] ) : __( 'Read more', 'nuhe' ); ?></span>
                        </a>
                    </li>
                </ul>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
