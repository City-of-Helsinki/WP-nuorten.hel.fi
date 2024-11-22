<?php
/**
 * Featured image partial
 */

$featured_image_id = \get_post_thumbnail_id();
$image_by          = \get_field( 'photographer_name', $featured_image_id );
$link              = \get_field( 'image_link', $featured_image_id );

?>

<figure class="featured-image">
    <div class="img-container">
        <?php if ( ! empty( $link ) ) : ?>
            <a href="<?php echo \esc_url( $link['url'] ); ?>" target="<?php echo \esc_attr( $link['target'] ?: '_parent' ); ?>">
        <?php endif; ?>

        <?php echo \wp_get_attachment_image( $featured_image_id, 'large' ); ?>

        <?php if ( ! empty( $link ) ) : ?>
            </a>
        <?php endif; ?>
    </div>

    <?php if ( ! empty( $image_by ) ) : ?>
        <figcaption class="text-sm">
            <span class="image-by figcaption-span">
                <?php echo esc_html( $image_by ); ?>
            </span>
        </figcaption>
    <?php endif; ?>
</figure>
