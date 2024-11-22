<?php
/**
 * Podcast partial
 *
 * Accepts args:
 *  - title: <string>
 *  - description: <string>
 *  - podcast: <string>
 *  - link : <array> or <string>
 */

$fields = wp_parse_args( $fields['data'], [
    'title'       => '',
    'description' => '',
    'podcast'     => '',
    'link'        => [],
] );

?>

<div class="nuhe-block nuhe-podcast nuhe-embed">
    <?php if ( ! empty( $fields['title'] ) ) : ?>
        <h3><?php echo esc_html( $fields['title'] ); ?></h3>
    <?php endif; ?>

    <?php if ( ! empty( $fields['description'] ) ) : ?>
        <p><?php echo wp_kses_post( $fields['description'] ); ?></p>
    <?php endif; ?>

    <div class="iframe is-1by1">
        <iframe
            class="has-ratio"
            title="<?php echo esc_html( $fields['title'] ); ?>"
            src="<?php echo esc_url( $fields['podcast'] ); ?>">
        </iframe>
    </div>

    <?php if ( ! empty( $fields['link'] ) ) : ?>
        <div class="call-to-action">
            <a
                href="<?php echo esc_url( $fields['link']['url'] ); ?>"
                class="call-to-action__link"
                target="<?php echo esc_attr( $fields['link']['target'] ); ?>">
                <span class="call-to-action__text">
                    <?php echo esc_html( $fields['link']['title'] ); ?>
                </span>

                <?php get_template_part( 'partials/icon', '', [ 'icon' => 'arrow-right' ] ); ?>
            </a>
        </div>
    <?php endif; ?>
</div>
