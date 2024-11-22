<?php
/**
 * Tag template
 */

use Geniem\Theme\Model\Tag;

get_header();

$model       = new Tag();
$breadcrumbs = $model->get_breadcrumbs();
$articles    = $model->get_posts();
?>
<main class="archive-main">
    <div class="container">
        <?php
        if ( ! empty( $breadcrumbs ) ) {
            \get_template_part( 'partials/breadcrumbs', '', [
                'breadcrumbs' => $breadcrumbs,
            ] );
        }

        // H1 heading
        \get_template_part( 'partials/heading', '', [
            'level' => 'h1',
            'class' => 'page-heading',
            'title' => __( 'Keyword', 'nuhe' ) . ': ' . get_queried_object()->name,
        ] );
        ?>
    </div>

    <?php
    if ( ! empty( $articles ) ) {
        get_template_part( 'partials/layouts/article_list', '', $articles );
    }
    ?>
</main>
<?php

get_footer();
