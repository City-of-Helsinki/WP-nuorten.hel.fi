<?php
/**
 * Event Grid
 */

/**
 * Partial args
 *
 * @var array $args
 */

use Geniem\Theme\Settings;

$args = wp_parse_args( $args, [
    'events'        => '',
    'title'         => '',
    'external_link' => false,
    'hide_koro'     => false,
    'limit'         => 6,
    'has_bg_color'  => false,
] );

if ( empty( $args['events'] ) ) {
    return;
}

$limit    = intval( $args['limit'] );
$columns  = $limit % 2 === 0 ? 2 : 3;
$columns  = ( 6 === $limit ) ? 3 : $columns;
$bg_color = $args['has_bg_color'] ? ' has-bg-color has-bg-color--grey' : '';

/**
 * Events
 *
 * @var \Geniem\Theme\Integrations\LinkedEvents\Entities\Event[] $args
 */
$events        = $args['events'];
$default_image = Settings::get_setting( 'default_page_image' );
?>
<div class="nuhe-layout nuhe-layout--main-content nuhe-events<?php echo esc_attr( $bg_color ); ?> has-<?php echo esc_attr( $columns ); ?>-columns">
    <div class="event-grid">
        <div class="container">
            <?php if ( ! empty( $args['title'] ) ) : ?>
                <h2 class="event-grid__title">
                    <?php echo esc_html( $args['title'] ); ?>
                </h2>
            <?php endif; ?>

            <div class="event-grid__grid">
                <?php
                foreach ( $events as $event ) {
                    get_template_part( 'partials/views/event/event', 'item', $event );
                }
                ?>
            </div>

            <?php if ( $args['external_link'] ) : ?>
                <div class="call-to-action">
                    <a href="<?php echo esc_url( $args['external_link']['url'] ); ?>"
                        class="call-to-action__link"
                        target="<?php echo esc_attr( $args['external_link']['target'] ); ?>">
                        <span class="call-to-action__text">
                            <?php echo esc_html( $args['external_link']['title'] ); ?>
                        </span>

                        <?php get_template_part( 'partials/icon', '', [ 'icon' => 'arrow-right' ] ); ?>
                    </a>
                </div>
            <?php endif; ?>
        </div>

        <?php if ( false === $args['hide_koro'] ) : ?>
            <div class="koro koro--wave koro--grey"></div>
        <?php endif; ?>
    </div>
</div>