<?php
/**
 * PageSitemap template
 *
 * Template Name: Sivukartta
 */

use Geniem\Theme\Model\PageSitemap;

$model   = new PageSitemap();
$content = $model->content();

$breadcrumbs = $model->get_breadcrumbs();

get_header();

?>

<main id="main-content" class="container page-sitemap-container">
    <?php
    if ( ! empty( $breadcrumbs ) ) {
        \get_template_part( 'partials/breadcrumbs', '', [
            'breadcrumbs' => $breadcrumbs,
        ] );
    }

    get_template_part( 'partials/heading', '', [
        'title' => $content['title'],
        'level' => 'h1',
        'class' => 'page-heading',
    ] );
    ?>

    <?php if ( ! empty( $content['sitemap'] ) ) { ?>
        <ul class="pages-list">
            <?php echo wp_kses_post( $content['sitemap'] ); ?>
        </ul>
    <?php } ?>
</main>
<?php

get_footer();
