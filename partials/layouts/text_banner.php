<?php
/**
 * TextBanner partial
 */

$args = wp_parse_args( $args );

?>

<div class="nuhe-layout nuhe-layout--main-content nuhe-text-banner has-koro">
    <div class="koro koro--pulse koro--bus koro--before"></div>
    <div class="container">
        <?php if ( ! empty( $args['title'] ) ) : ?>
            <p class="nuhe-text-banner__title">
                <?php echo esc_html( $args['title'] ); ?>
            </p>
        <?php endif; ?>

        <?php if ( ! empty( $args['citation'] ) ) : ?>
            <p class="nuhe-text-banner__citation h2">
                <?php echo esc_html( $args['citation'] ); ?>
            </p>
        <?php endif; ?>

        <?php
        if ( ! empty( $args['link'] ) ) {
            get_template_part( 'partials/link', '', [
                'title'      => $args['link']['title'],
                'label'      => $args['link']['title'],
                'url'        => $args['link']['url'],
                'icon-start' => 'arrow-right',
            ] );
        }
        ?>
    </div>
</div>
