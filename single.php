<?php
/**
 * Single template
 */

get_header();


/**
 * Model
 *
 * @var \Geniem\Theme\Model\Single $model
 */

$model                = ModelController()->get_class( 'Single' );
$breadcrumbs          = $model->get_breadcrumbs();
$related_youthcenters = $model->get_related_youthcenters();
$post_tags            = $model->get_post_tags();
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

<?php if ( ! empty( $related_youthcenters ) ) : ?>
    <div class="container">
        <h3><?php esc_html_e( 'Youth centres related to the article', 'nuhe' ); ?></h3>
        <?php
        get_template_part( 'partials/links', '', [
            'list_classes' => [ 'child-menu-items', 'button-links', 'article-button-links' ],
            'links'        => $related_youthcenters,
            'type'         => 'button',
            'classes'      => [ 'outlined', 'border-black' ],
        ] );
        ?>
    </div>
    <?php
endif;

if ( ! empty( $post_tags ) ) : ?>
    <div class="container container--sub-pages post-tags">
        <h3><?php esc_html_e( 'Keywords', 'nuhe' ); ?></h3>
        <?php
        get_template_part( 'partials/links', '', [
            'list_classes' => [ 'child-menu-items', 'button-links', 'article-button-links' ],
            'links'        => $post_tags,
            'type'         => 'button',
            'classes'      => [ 'outlined', 'border-black' ],
        ] );
        ?>
    </div>
    <?php
endif;

get_footer();
