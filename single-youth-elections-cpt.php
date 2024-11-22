<?php
/**
 * Single youth elections cpt template.
 */

use Geniem\Theme\Model\SingleYouthElections;

global $post;

$model       = new SingleYouthElections();
$breadcrumbs = $model->get_breadcrumbs();
$candidate   = $model->get_candidate_data( $post );

get_header();

?>
<div id="main-content" class="container page-grid<?php echo ! empty( $breadcrumbs ) ? ' page-grid--has-breadcrumbs' : ''; ?> ">
    <main class="page-grid__content">
        <?php
        if ( ! empty( $breadcrumbs ) ) {
            \get_template_part( 'partials/breadcrumbs', '', [
                'breadcrumbs' => $breadcrumbs,
            ] );
        }

        the_post();

        // H1 heading
        \get_template_part( 'partials/heading', '', [
            'level' => 'h1',
            'class' => 'page-heading',
        ] );

        \get_template_part( 'partials/views/youth-council-elections/candidate', '', $candidate );

        ?>

        <?php if ( ! empty( $candidate->description ) ) : ?>
            <div class="candidate-description">
                <?php echo wp_kses_post( $candidate->description ); ?>
            </div>
        <?php endif; ?>
    </main>
</div>
<?php

get_footer();
