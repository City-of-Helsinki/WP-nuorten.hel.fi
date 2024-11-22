<?php
/**
 * Notification block
 */

$args = wp_parse_args( $fields['data'] );
?>

<div class="nuhe-block nuhe-attention-bulletin nuhe-attention-bulletin--block">
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
