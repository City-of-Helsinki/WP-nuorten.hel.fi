<?php
/**
 * PageTheme template
 *
 * Template Name: Teemasivu
 */

get_header();

$model = ModelController()->get_class( 'PageTheme' );

$model->set_sections_data();

the_post();

$id               = get_the_ID();
$hero_link        = get_field('hero_link', $id );
$links            = ! empty( $hero_link ) ? array_merge( [ $hero_link ], $model->get_anchors() ) : $model->get_anchors();
$photographer     = get_field( 'photographer_name', get_post_thumbnail_id( $id ) );
$bg_color         = get_field( 'hero_bg_color', $id );
$bg_color_class   = empty( $bg_color ) || $bg_color === 'default' ? 'has-bg-color--black' : " has-bg-color--{$bg_color} text-black";
$btn_border_class = empty( $bg_color ) || $bg_color === 'default' ? 'border-white' : 'border-black';
$breadcrumbs      = $model->get_breadcrumbs();

get_template_part( 'partials/hero', '', [
    'modifier_class'   => 'page-theme',
    'class'            => $bg_color_class,
    'title'            => get_field( 'title', $id ),
    'description'      => get_field( 'hero_description', $id ),
    'links'            => $links,
    'classes'          => [ 'outlined', $btn_border_class ],
    'background_image' => get_the_post_thumbnail_url( $id, 'fullhd' ),
    'breadcrumbs'      => $breadcrumbs,
    'bg_color'         => $bg_color,
] );
?>

<?php if ( ! empty( $photographer ) ) : ?>
    <div class="container container--image-by">
        <span class="image-by"><?php echo esc_html( $photographer ); ?></span>
    </div>
<?php endif; ?>


<?php
$post_content          = get_the_content( $id );
$important_links       = $model->get_important_links();
$important_links_title = ! empty( $important_links ) ? $important_links['title'] : '';
$important_links_links = ! empty( $important_links ) ? $important_links['links'] : '';
$important_links_icon  = ! empty( $important_links ) ? $important_links['icon'] : '';
?>

<?php if ( ! empty( get_the_content( $id ) ) || ! empty( $important_links ) || ! empty( $model->sidebar( false ) ) ) : ?>
    <div class="container page-grid">
        <main class="page-grid__content blocks">
            <?php get_template_part( 'partials/blocks' ); ?>
        </main>

        <aside class="page-grid__sidebar" aria-label="<?php esc_attr_e( 'Related content', 'nuhe' ); ?>">
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
            <?php endif; ?>

            <?php
            $model->sidebar();
            ?>
        </aside>
    </div>
<?php endif; ?>

<aside class="page-footer" aria-label="<?php esc_attr_e( 'More related content', 'nuhe' ); ?>">
    <?php $model->content(); ?>
</aside>
<?php

get_footer();
