<?php
/**
 * Image partial
 *
 * Accepts args:
 *  - image: <array>
 *  - align: <string>
 */

$fields = wp_parse_args( $fields['data'], [
    'image' => [],
    'align' => '',
] );

$align = $fields['align'] ? "align{$fields['align']}" : 'alignnone';

?>

<div class="nuhe-block nuhe-image <?php echo esc_attr( $align ); ?>">
    <?php if ( ! empty( $fields['image'] ) ) : ?>
        <figure>
            <?php
            echo wp_get_attachment_image(
                $fields['image']['ID'],
                'large',
            );
            ?>

            <figcaption class="text-sm">
                <?php if ( ! empty( $fields['image']['caption'] ) ) : ?>
                    <span class="image-caption figcaption-span">
                        <?php echo wp_kses_post( $fields['image']['caption'] ); ?>
                    </span>
                <?php endif; ?>

                <?php if ( isset( $fields['image']['photographer_name'] ) ) : ?>
                    <span class="image-by figcaption-span">
                        <?php echo esc_html( $fields['image']['photographer_name'] ); ?>
                    </span>
                <?php endif; ?>
            </figcaption>
        </figure>
    <?php endif; ?>
</div>
