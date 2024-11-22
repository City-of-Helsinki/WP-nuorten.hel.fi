<?php
/**
 * General page template
 */

get_header();

$model       = ModelController()->get_class( 'Page' );
$model->set_sections_data();
$breadcrumbs = $model->get_breadcrumbs();
$links       = $model->get_anchors();

?>
<div id="main-content" class="container page-grid<?php echo ! empty( $breadcrumbs ) ? ' page-grid--has-breadcrumbs' : ''; ?> ">
    <main class="page-grid__content blocks">
        <?php
        if ( ! empty( $breadcrumbs ) ) {
            \get_template_part( 'partials/breadcrumbs', '', [
                'breadcrumbs' => $breadcrumbs,
            ] );
        }

        the_post();

        // Featured image
        if ( \has_post_thumbnail() ) {
            \get_template_part( 'partials/featured-image' );
        }

        // H1 heading
        \get_template_part( 'partials/heading', '', [
            'level' => 'h1',
            'class' => 'page-heading',
        ] );

        \get_template_part( 'partials/links', '', [
            'links'        => $links,
            'list_classes' => [ 'button-links', 'anchor-links' ],
            'type'         => 'button',
        ] );

        \get_template_part( 'partials/blocks' );

		?>
    </main>
    <aside class="page-grid__sidebar" aria-label="<?php esc_attr_e( 'Related content', 'nuhe' ); ?>">
        <?php $model->sidebar(); ?>
    </aside>
</div>
<aside class="page-footer" aria-label="<?php esc_attr_e( 'More related content', 'nuhe' ); ?>">
<?php $model->content(); ?>
</aside>
<?php

get_footer();
