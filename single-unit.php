<?php
/**
 * Single unit template (Applies to PostType\HighSchool, PostType\VocationalSchool)
 * and PostType\YouthCenter
 */

use Geniem\Theme\Model\SingleUnit;

global $post;

$model = new SingleUnit( $post->post_type );

get_header();

$partial_path     = 'partials/views/unit/unit';
$links            = $model->get_anchors();
$hero_image       = \get_field( 'hero_image' );
$bg_color         = \get_field( 'hero_bg_color' );
$is_events_hidden = \get_field( 'is_events_hidden' );
$sections         = $model->get_sections();
$flexible_content = $model->get_layouts_array();
$respa_data       = $model->get_respa_spaces_data();
$uni_data         = $model->get_unit_data();
$breadcrumbs      = $model->get_breadcrumbs();
$id               = \get_the_ID();

$slides        = \get_field( 'slides', $id ) ?: [];
$slides_height = \get_field( 'height', $id ) ?: [];
$slides_count  = \count( $slides );

the_post();

get_template_part( 'partials/hero', '', [
    'modifier_class' => 'single-unit',
    'class'          => "has-bg-color has-bg-color--{$bg_color}",
    'links'          => $links,
    'classes'        => [ 'theme-white' ],
    'breadcrumbs'    => $breadcrumbs,
    'hero_image'     => $hero_image,
] );


if ( $slides_count > 0 ) : ?>
    <div class="container" id=<?php echo esc_attr( $slides['anchor']['url'] ); ?>>
        <section class="glide hero-slider hero-slider--unit-slider" id="hero-slider" aria-label="Kuvakaruselli" aria-roledescription="carousel">
            <div class="glide__track" data-glide-el="track">
                <div class="glide__slides">
                    <?php
                        foreach ( $slides as $slide ) {
                            get_template_part( 'partials/slider', '', [
                                'height'          => $slides_height,
                                'title'           => $slide['title'],
                                'description'     => $slide['description'],
                                'image'           => $slide['image']['url'],
                                'image_copyright' => $slide['image_copyright'],
                                'link'            => $slide['link'],
                            ] );
                        }
                     ?>
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
    </div>
<?php endif; ?>

<main class="unit-sections">
    <?php
    if ( ! empty( $sections['intro_group'] ) ) {
        get_template_part( $partial_path, 'intro', [
            'intro'     => $sections['intro_group'],
            'link_list' => $sections['unit_link_list'],
        ] );
    }

    if ( ! empty( $sections['instructors_group']['instructors'] ) ) {
        get_template_part( $partial_path, 'instructors', $sections['instructors_group'] );
    }

    if ( ! empty( $sections['opening_hours_group'] ) ) {
        get_template_part( $partial_path, 'opening-hours', [
            'is_events_hidden'   => $is_events_hidden,
            'opening_hours_data' => $sections['opening_hours_group'],
            'events'             => $model->get_events(),
        ] );
    }

    if ( ! empty( $sections['video_group'] ) ) {
        get_template_part( $partial_path, 'video', $sections['video_group'] );
    }
    ?>
</main>

<?php if ( ! empty( $flexible_content ) || ! empty( $respa_data ) ) : ?>
    <aside class="unit-footer" aria-label="<?php esc_attr_e( 'More related content', 'nuhe' ); ?>">

        <?php if ( empty ( $is_events_hidden ) || empty ( $sections['opening_hours_group']['is_hidden'] ) ): ?>
            <div class="koro koro--wave koro--grey"></div>
        <?php endif; ?>

        <?php $model->content(); ?>

        <?php
        if ( ! empty( $respa_data ) ) {
            get_template_part( 'partials/views/respa/respa-grid', '', $respa_data );
        }
        ?>
    </aside>
<?php endif; ?>

<?php

get_footer();
