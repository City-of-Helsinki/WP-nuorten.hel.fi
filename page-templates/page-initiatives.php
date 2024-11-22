<?php
/**
 * PageInitiatives template
 *
 * Template Name: Aloitteiden koontisivu
 */

use Geniem\Theme\Model\PageInitiatives;

get_header();

/**
 * Model
 *
 * @var \Geniem\Theme\Model\PageInitiatives $model
 */

$model = new PageInitiatives();

the_post();

$id               = get_the_ID();
$hero_link        = get_field( 'hero_link', $id );
$photographer     = get_field( 'photographer_name', get_post_thumbnail_id( $id ) );
$bg_color         = get_field( 'hero_bg_color', $id );
$bg_color_class   = empty( $bg_color ) || $bg_color === 'default' ? 'has-bg-color--black' : " has-bg-color--{$bg_color} text-black";
$btn_border_class = empty( $bg_color ) || $bg_color === 'default' ? 'border-white' : 'border-black';
$breadcrumbs      = $model->get_breadcrumbs();

get_template_part( 'partials/hero', '', [
    'modifier_class'   => 'page-theme page-initiatives',
    'class'            => $bg_color_class,
    'title'            => get_field( 'title', $id ),
    'description'      => get_field( 'hero_description', $id ),
    'links'            => empty( $hero_link ) ? [] : [ $hero_link ],
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
$post_content = get_the_content( $id );
?>

<?php if ( ! empty( get_the_content( $id ) ) || ! empty( $model->sidebar( false ) ) ) : ?>
    <div class="container page-grid">
        <main class="page-grid__content blocks">
            <?php get_template_part( 'partials/blocks' ); ?>
        </main>

        <aside class="page-grid__sidebar" aria-label="<?php esc_attr_e( 'Related content', 'nuhe' ); ?>">
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
