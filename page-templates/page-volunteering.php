<?php
/**
 * PageVolunteering template
 *
 * Template Name: Vapaaehtoistoiminta
 */

use Geniem\Theme\Model\PageVolunteering;

get_header();

$model = new PageVolunteering();

the_post();

$id = get_the_ID();

$breadcrumbs = $model->get_breadcrumbs();

if ( ! empty( $breadcrumbs ) ) : ?>
    <div class="container">
        <?php
        \get_template_part( 'partials/breadcrumbs', '', [
            'breadcrumbs' => $breadcrumbs,
        ] );
        ?>
    </div>
<?php endif; ?>

<?php
get_template_part( 'partials/volunteering-hero', '', [
    'title'       => get_field( 'hero_title', $id ),
    'image'       => get_post_thumbnail_id( $id ),
    'description' => get_field( 'hero_description', $id ),
] );
?>

<main class="volunteering-content">
    <?php $model->content(); ?>
</main>
<?php

get_footer();
