<?php
/**
 * Silence is...
 */

// ...golden.
get_header();

/**
 * Model
 *
 * @var \Geniem\Theme\Model\Error404 $model
 */
$model = ModelController()->get_class( 'Error404' );

get_template_part( 'partials/hero-front-page', '', [
    'hero_description' => $model->get_title(),
    'background_image' => $model->get_background(),
] );
?>
    <div class="container page-grid">
        <main class="page-grid__content blocks">
            <div class="error-404__description">
                <?php echo wp_kses_post( $model->get_content() ); ?>
            </div>
        </main>
    </div>
<?php

get_footer();
