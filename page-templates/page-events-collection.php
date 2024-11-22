<?php
/**
 * Template name: Tapahtumien koontisivu
 */

use Geniem\Theme\Model\PageEventsCollection;
use Geniem\Theme\Settings;

$post_content       = \get_the_content( \get_the_ID() );
$model              = new PageEventsCollection();
$hero_data          = $model->get_hero_data();
$event_modules      = $model->get_event_modules();
$social_media_share = $model->get_sharing_data( __( 'Share collection', 'nuhe' ) );
$link               = \get_field( 'image_link', \get_post_thumbnail_id() );

\get_header();

if ( ! empty( $hero_data ) ) : ?>
<div id="main-content" class="events-collection-hero container">
    <div class="events-collection-hero__wrapper">
        <div class="events-collection-hero__item events-collection-hero__item--text<?php echo \esc_attr( $hero_data['background_color_class'] ); ?>">
            <?php if ( ! empty( $hero_data['title'] ) ) : ?>
                <h1><?php echo \esc_html( $hero_data['title'] ); ?></h1>
            <?php endif; ?>

            <?php if ( ! empty( $hero_data['description'] ) ) : ?>
                <?php echo \wp_kses_post( $hero_data['description'] ); ?>
            <?php endif; ?>

            <?php
            \get_template_part( 'partials/social-media-share', '', [
                'share_title' => $social_media_share['share_title'],
                'links'       => $social_media_share['links'],
            ] );
            ?>
        </div>

        <?php if ( \has_post_thumbnail() ) : ?>
            <div class="events-collection-hero__item events-collection-hero__item--image objectfit-image-wrapper">
                <div class="objectfit-image-container">
                    <?php if ( ! empty( $link ) ) : ?>
                        <a href="<?php echo \esc_url( $link['url'] ); ?>" target="<?php echo \esc_attr( $link['target'] ?: '_parent' ); ?>">
                    <?php endif; ?>

                    <?php \the_post_thumbnail( 'fullhd', [ 'class' => 'objectfit-image' ] ); ?>

                    <?php if ( ! empty( $link ) ) : ?>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>

<?php if ( ! empty( $post_content ) ) : ?>
<div class="container page-grid">
    <main class="page-grid__content blocks">
        <?php \get_template_part( 'partials/blocks' ); ?>
    </div>
</div>
<?php endif; ?>

<?php if ( ! empty( $event_modules ) ) : ?>
<div <?php echo empty( $hero_data ) ? 'id="main-content" ' : ''; ?>class="events-modules">
    <?php
    foreach ( $event_modules as $module ) :
        $default_image = Settings::get_setting( 'default_page_image' );
        ?>
    <div class="nuhe-layout nuhe-layout--main-content nuhe-events has-bg-color has-bg-color--grey">
        <div class="event-grid">
            <div class="container">
                <?php if ( ! empty( $module['title'] ) ) : ?>
                    <h2 class="event-grid__title">
                        <?php echo \esc_html( $module['title'] ); ?>
                    </h2>
                <?php endif; ?>

                <div class="event-grid-grid">
                    <?php
                    $count = 1;

                    $init_amount_of_events      = $module['display_settings']['init_amount_of_events'] ?: false;
                    $amount_of_show_more_events = $module['display_settings']['amount_of_show_more_events'] ?: false;
                    foreach ( $module['events'] as $event ) :
                        $image        = $event->get_primary_image();
                        $hidden_class = $init_amount_of_events && $count > $init_amount_of_events ? ' is-hidden' : '';
                        ?>
                        <div
                            class="event-grid-item has-bg-color<?php echo \esc_attr( $hidden_class ); ?> <?php echo $image || $default_image ? 'has-image' : 'has-empty-image'; ?>">
                            <?php if ( $image ) : ?>
                                <div class="event-grid-item__image objectfit-image-wrapper">
                                    <div class="objectfit-image-container">
                                        <a href="<?php echo \esc_url( $event->get_permalink() ); ?>">
                                            <img src="<?php echo \esc_url( $image->get_url() ); ?>" class="objectfit-image"
                                                alt="<?php echo \esc_html( $image->get_alt_text() ); ?>">
                                        </a>
                                    </div>
                                </div>
                            <?php else: // phpcs:ignore ?>
                                <?php if ( $default_image ) : ?>
                                    <div class="event-grid-item__image objectfit-image-wrapper">
                                        <div class="objectfit-image-container">
                                            <a href="<?php echo \esc_url( $event->get_permalink() ); ?>">
                                                <img
                                                    src="<?php echo \esc_url( $default_image['sizes']['large'] ); ?>"
                                                    class="objectfit-image"
                                                    alt="<?php echo \esc_html( $default_image['alt'] ); ?>">
                                            </a>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>

                            <div class="event-grid-item__info">

                                <?php if ( ! empty( $event->get_formatted_time_string() ) ) : ?>
                                    <div class="event-grid-item__date">
                                        <?php echo \esc_html( $event->get_formatted_time_string() ); ?>
                                    </div>
                                <?php endif; ?>

                                <?php if ( ! empty( $event->get_name() ) ) : ?>
                                    <h3 class="event-grid-item__title">
                                        <a href="<?php echo esc_url( $event->get_permalink() ); ?>" class="event-grid-item__link">
                                            <?php echo \esc_html( $event->get_name() ); ?>
                                        </a>
                                    </h3>
                                <?php endif; ?>

                                <?php if ( ! empty( $event->get_location_string() ) ) : ?>
                                    <div class="event-grid-item__location">
                                        <?php echo \esc_html( $event->get_location_string() ); ?>
                                    </div>
                                    <?php
                                    endif;
                                    $tickets           = $event->get_offers();
                                    $single_ticket_url = $event->get_single_ticket_url();
                                ?>
                                <div class="event-grid-item__tickets">
                                    <?php foreach ( $tickets as $ticket ) : ?>
                                        <?php if ( $ticket->is_free() ) : ?>
                                            <div class="ticket-price">
                                                <?php echo \esc_html__( 'Free', 'nuhe' ); ?>
                                            </div>
                                        <?php else : // phpcs:ignore ?>
                                            <div class="ticket-price">
                                                <?php
                                                $ticket_price = $ticket->get_price();
                                                $ticket_price = strpos( $ticket_price, '€' ) === false ? $ticket_price . '€' : $ticket_price;
                                                echo \wp_kses_post( $ticket_price );
                                                ?>
                                            </div>
                                        <?php endif; ?>
                                    <?php endforeach; ?>

                                    <div class="links">
                                        <?php foreach ( $tickets as $ticket ) : ?>
                                            <?php if ( ! empty( $ticket->get_info_url() ) && ! $single_ticket_url ) : ?>
                                                <div class="ticket-actions">
                                                    <?php
                                                    \get_template_part(
                                                        'partials/views/event/ticket-link',
                                                        '',
                                                        [
                                                            'is_free' => $ticket->is_free(),
                                                            'url'     => $ticket->get_info_url(),
                                                            'classes' => [ 'color-success' ],
                                                        ]
                                                    );
                                                    ?>
                                                </div>
                                            <?php endif; ?>
                                        <?php endforeach; ?>

                                        <?php if ( $single_ticket_url ) : ?>
                                            <div class="ticket-actions">
                                                <?php
                                                \get_template_part(
                                                    'partials/views/event/ticket-link',
                                                    '',
                                                    [
                                                        'is_free' => $ticket->is_free(),
                                                        'url'     => $single_ticket_url,
                                                        'classes' => [ 'color-success' ],
                                                    ]
                                                );
                                                ?>
                                            </div>
                                        <?php endif; ?>
                                        <div class="read-more">
                                            <?php
                                            \get_template_part(
                                                'partials/button-link',
                                                '',
                                                [
                                                    'label' => __( 'Read more', 'nuhe' ),
                                                    'url' => $event->get_permalink(),
                                                    'classes' => [ 'theme-black-bg' ],
                                                ]
                                            );
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                        $count++;
                        endforeach;
                    ?>
                </div>
                <?php
                if ( $amount_of_show_more_events ) {
                    \get_template_part(
                        'partials/button',
                        '',
                        [
                            'label'   => __( 'Show more', 'nuhe' ) . " ($amount_of_show_more_events)",
                            'classes' => [ 'theme-engel-medium-light', 'show-more-events' ],
                        ]
                    );
                }
                ?>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
    <?php
endif;

\get_footer();
