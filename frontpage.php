<?php
/**
 * Front page template
 *
 * Template Name: Etusivu
 */

get_header();

$model = ModelController()->get_class( 'FrontPage' );

the_post();

$id               = get_the_ID();
$hero_link        = get_field( 'hero_link', $id );
$photographer     = get_field( 'photographer_name', get_post_thumbnail_id( $id ) );
$bg_color         = get_field( 'hero_bg_color', $id );
$btn_border_class = empty( $bg_color ) || $bg_color === 'default' ? 'border-white' : 'border-black';

$slides       = get_field( 'slides', $id ) ?: [];
$slides_count = count( $slides );

?>

<?php if ( $slides_count > 0 ) : ?>
<section class="glide hero-slider hero-slider--page-front" id="hero-slider" aria-label="Kuvakaruselli" aria-roledescription="carousel">
    <div class="glide__track" data-glide-el="track">
        <div class="glide__slides">
            <?php foreach ( $slides as $slide ) : ?>
                <?php
                get_template_part( 'partials/slider-front-page', '', [
                    'title'       => $slide['title'],
                    'description' => $slide['description'],
                    'image'       => $slide['image']['url'],
                    'link'        => $slide['link'],
                ] );
                ?>
            <?php endforeach; ?>
        </div>
        <?php if ( $slides_count > 1 ) : ?>
            <nav aria-label="<?php echo __( 'Carousel navigation', 'nuhe' ); ?>" class="hero-slider__controls">
            
                <!-- Buttons needs to be wrapped with this and if you put only one wrapper, it messes up bullets active state -->
                <div class="hero-slider__bullets-container" data-glide-el="controls[nav]">
                    <button aria-label="<?php echo __( 'Previous', 'nuhe' ); ?>" type="button" class="hero-slider__arrow-button" data-glide-dir="<">
                        <span aria-hidden="true" class="hds-icon hds-icon--arrow-left hds-icon--size-m"></span>
                    </button>
                </div>
            
                <div class="hero-slider__bullets-container" data-glide-el="controls[nav]">                    
                    <?php foreach ( range( 0, $slides_count - 1 ) as $index ) : ?>
                        <button aria-label=" <?php echo (int) $index; ?>" class="glide__bullet hero-slider__bullet" type="button" data-glide-dir="=<?php echo (int) $index; ?>"></button>
                    <?php endforeach; ?>
                </div>
                
                <div class="hero-slider__bullets-container" data-glide-el="controls[nav]">
                    <button aria-label="<?php echo __( 'Next', 'nuhe' ); ?>" type="button" class="hero-slider__arrow-button" data-glide-dir=">">                        
                        <span aria-hidden="true" class="hds-icon hds-icon--arrow-right hds-icon--size-m"></span>
                    </button>
                </div>
            </nav>            
        <?php endif; ?>
    </div>
    <div class="koro"></div>
</section>    
<?php endif; ?>

<?php if ( $slides_count === 0 ) : ?>
    <?php
    get_template_part( 'partials/hero-front-page', '', [
        'hero_description'   => get_field( 'hero_description', $id ),
        'hero_description_2' => get_field( 'hero_description_2', $id ),
        'links'              => empty( $hero_link ) ? [] : [ $hero_link ],
        'classes'            => [ 'outlined', $btn_border_class ],
        'background_image'   => get_the_post_thumbnail_url( $id, 'fullhd' ),
        'bg_color'           => $bg_color,
    ] );
    ?>
<?php endif; ?>

<?php if ( ! empty( $photographer ) ) : ?>
    <div class="container container--image-by">
        <span class="image-by"><?php echo esc_html( $photographer ); ?></span>
    </div>
<?php endif; ?>

<div class="container">
    <main class="blocks blocks--front-page">
        <?php
        get_template_part( 'partials/content-sections', '', [
            'sections' => $model->get_content_sections(),
        ] );
        ?>

        <?php get_template_part( 'partials/blocks' ); ?>
    </main>
</div>

<aside class="page-footer" aria-label="<?php esc_attr_e( 'More related content', 'nuhe' ); ?>">
    <?php $model->content(); ?>
</aside>
<?php

get_footer();
