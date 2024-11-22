<?php
/**
 * Initiatives archive
 */

use Geniem\Theme\Model\ArchiveInitiatives;

get_header();

$model       = new ArchiveInitiatives();
$breadcrumbs = $model->get_breadcrumbs();
$initiatives = $model->get_initiatives();
?>

<main class="archive-initiatives">
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
            'title' => get_queried_object()->labels->all_items,
        ] );
        ?>
    </div>

    <?php
    if ( ! empty( $initiatives ) ) {
        get_template_part( 'partials/layouts/initiative_list', '', $initiatives );
    }
    ?>
</main>
<?php

get_footer();
