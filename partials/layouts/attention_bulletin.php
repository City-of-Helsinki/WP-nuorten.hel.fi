<?php
/**
 * Attention bulletin partial
 */

$args      = wp_parse_args( $args );
$anchor_id = ! empty( $args['anchor_id'] ) ? ' id="' . $args['anchor_id'] . '"' : '';
?>

<div class="nuhe-layout nuhe-layout--main-content nuhe-attention-bulletin"<?php echo wp_kses_post( $anchor_id ); ?>>
    <div class="container">
        <?php
        get_template_part( 'partials/icon', '', [
            'icon' => 'alert-circle',
            'size' => 'xl',
        ] );
        ?>
        <div class="container-inner">
            <?php if ( ! empty( $args['title'] ) ) : ?>
                <h2 class="nuhe-attention-bulletin__title">
                    <?php echo esc_html( $args['title'] ); ?>
                </h2>
            <?php endif; ?>

            <?php if ( ! empty( $args['description'] ) ) : ?>
                <div class="nuhe-attention-bulletin__description">
                    <?php echo wp_kses_post( $args['description'] ); ?>
                </div>
            <?php endif; ?>

            <?php
            get_template_part( 'partials/links', '', [
                'list_classes' => [ 'attention-bulletin-links' ],
                'links'        => $args['links'],
                'classes'      => [ 'outlined', 'border-black' ],
            ] );
            ?>
        </div>
    </div>
</div>
