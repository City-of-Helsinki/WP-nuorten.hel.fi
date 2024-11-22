<?php
/**
 * Gallery partial
 *
 * Accepts args:
 *  - fields: <array> Block data
 *
 * @var array $fields
 */

$data_fields = wp_parse_args( $fields['data'], [
    'images' => [],
] );

if ( empty( $data_fields['images'] ) ) {
    return;
}

$anchor = $fields['block']['anchor'] ?? '';

if ( ! empty( $anchor ) ) {
    $anchor = sprintf( 'id="%s"', esc_attr( $anchor ) );
}
?>

<div class="nuhe-block nuhe-gallery" <?php esc_html_e( $anchor ); ?>>
    <div class="nuhe-gallery__images">
        <?php foreach ( $data_fields['images'] as $image ) : ?>
            <div class="nuhe-gallery__image-column <?php esc_attr_e( $image['column_class'] ); ?>">
                <?php
                $image_url = wp_get_attachment_image_url(
                    $image['image']['ID'],
                    'large',
                );
                ?>

                <a href="<?php echo esc_url( $image_url ); ?>"
                    class="nuhe-gallery__image js-gallery-trigger"
                    data-group="<?php esc_attr_e( $image['group_id'] ); ?>"
                    data-modaal-desc="<?php esc_html_e( $image['image']['caption'] ); ?>">
                    <?php
                    echo wp_get_attachment_image(
                        $image['image']['ID'],
                        'medium',
                    );
                    ?>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</div>
