<?php
/**
 * Volunteering hero partial
 */

$args = wp_parse_args( $args, [
    'image'       => '',
    'title'       => '',
    'description' => '',
] );

if ( empty( $args['image'] ) ) {
    return;
}

?>

<div id="main-content" class="volunteering-hero">
    <div class="container grid-columns grid-columns--2">
        <div class="volunteering-hero__content volunteering-hero__content--image grid-columns__column">
            <figure class="img-container">
                <?php
                    echo wp_get_attachment_image( $args['image'], 'large' );
                ?>
            </figure>
        </div>
        <div class="volunteering-hero__content volunteering-hero__content--text grid-columns__column">
            <?php
            // H1 heading
            \get_template_part( 'partials/heading', '', [
                'title' => ! empty( $args['title'] ) ? $args['title'] : null,
                'level' => 'h1',
                'class' => 'page-heading',
            ] );
            ?>

            <?php if ( ! empty( $args['description'] ) ) : ?>
                <div class="volunteering-hero-desc">
                    <?php echo wp_kses_post( $args['description'] ); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
