<?php
/**
 * Some embed partial
 *
 * Accepts args:
 *  - data: <array>
 */

$args      = wp_parse_args( $args );
$anchor_id = ! empty( $args['anchor_id'] ) ? ' id="' . $args['anchor_id'] . '"' : '';
?>

<div class="nuhe-layout nuhe-layout--main-content nuhe-some-embed has-bg-color has-bg-color--white"<?php echo wp_kses_post( $anchor_id ); ?>>
    <div class="container">
        <?php if ( ! empty( $args['title'] ) ) : ?>
            <h2 class="nuhe-some-embed__title">
                <?php echo esc_html( $args['title'] ); ?>
            </h2>
        <?php endif; ?>

        <span class="is-sr-only">
            <?php esc_html_e( 'Nuorten Helsingin sosiaalisen median tilisyöte', 'nuhe' ); ?>
        </span>

        <a href="#<?php echo esc_html( $args['skip_embed_id'] ); ?>" class="u-skip-to-content">
            <?php esc_html_e( 'Ohita upotus', 'nuhe' ); ?>
        </a>

        <?php if ( ! empty( $args['some_embed'] ) ) : ?>
            <div class="nuhe-some-embed__content">
                <?php echo wp_kses_post( $args['some_embed'] ); ?>
            </div>
        <?php endif; ?>
    </div>
    <div id="<?php echo esc_html( $args['skip_embed_id'] ); ?>"></div>
</div>
