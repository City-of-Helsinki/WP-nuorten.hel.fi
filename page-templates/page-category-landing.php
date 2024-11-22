<?php
/**
 * PageCategoryLanding template
 *
 * Template Name: Kategorian etusivu
 */

get_header();

$model = ModelController()->get_class( 'PageCategoryLanding' );

the_post();

$links = get_field( 'hero_links', get_the_ID() );
if ( ! empty( $links ) ) {
    $links = array_map( function( $link ) {
        return $link['link'];
    }, $links );
}

$breadcrumbs = $model->get_breadcrumbs();

get_template_part( 'partials/hero', '', [
    'modifier_class' => 'page-category-landing',
    'description'    => get_field( 'hero_description', get_the_ID() ),
    'links'          => empty( $links ) ? [] : $links,
    'classes'        => [ 'theme-white' ],
    'icon-end'       => 'arrow-right',
    'breadcrumbs'    => $breadcrumbs,
] );
?>
<div class="container page-grid">
    <main class="page-grid__content blocks">
        <?php get_template_part( 'partials/blocks' ); ?>
    </main>

    <aside class="page-grid__sidebar" aria-label="<?php esc_attr_e( 'Related content', 'nuhe' ); ?>">
        <?php
        $important_links       = $model->get_important_links();
        $important_links_title = ! empty( $important_links ) ? $important_links['title'] : '';
        $important_links_links = ! empty( $important_links ) ? $important_links['links'] : '';
        $important_links_icon  = ! empty( $important_links ) ? $important_links['icon'] : '';
        ?>

        <?php if ( ! empty( $important_links ) ) : ?>
            <div class="links important-links">
                <?php if ( ! empty( $important_links_title ) ) : ?>
                    <?php
                    get_template_part( 'partials/heading', '', [
                        'title' => $important_links_title,
                        'icon'  => $important_links_icon,
                        'level' => 'h3',
                        'class' => ! empty( $important_links_icon ) ? 'heading heading--has-icon links-title' : '',
                    ] );
                    ?>
                <?php endif; ?>

                <?php
                get_template_part( 'partials/links', '', [
                    'links' => $important_links_links,
                ] );
                ?>
            </div>
        <?php endif; ?>

        <?php $model->sidebar(); ?>
    </aside>
</div>

<aside class="page-footer" aria-label="<?php esc_attr_e( 'More related content', 'nuhe' ); ?>">
    <?php $model->content(); ?>
</aside>

<div class="container container--sub-pages">
    <?php
    get_template_part( 'partials/links', '', [
        'list_classes' => [ 'child-menu-items', 'button-links' ],
        'links'        => $model->get_child_menu_items(),
        'type'         => 'button',
        'classes'      => [ 'outlined', 'border-black' ],
        'icon-end'     => 'arrow-right',
    ] );
    ?>
</div>
<?php

get_footer();
