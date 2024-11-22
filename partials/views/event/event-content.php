<?php
/**
 * Event content
 */

/**
 * Event is passed as args.
 *
 * @var \Geniem\Theme\Integrations\LinkedEvents\Entities\Event $args
 */
$event       = $args['event'];
$breadcrumbs = $args['breadcrumbs'];

?>
<div class="container">
    <?php
    if ( ! empty( $breadcrumbs ) ) {
        \get_template_part( 'partials/breadcrumbs', '', [
            'breadcrumbs' => $breadcrumbs,
        ] );
    }
    ?>
    <div class="event__grid">
        <div class="event__description">
            <h2 class="event__description-title">
                <?php esc_html_e( 'Description', 'nuhe' ); ?>
            </h2>

            <?php echo wp_kses_post( $event->get_description() ); ?>
        </div>

        <div class="event__details">
            <div class="detail-block">
                <div class="detail-block__title">
                    <?php get_template_part( 'partials/icon', '', [ 'icon' => 'calendar-clock' ] ); ?>
                    <?php esc_html_e( 'Date and time', 'nuhe' ); ?>
                </div>

                <div class="detail-block__content">
                    <?php echo esc_html( $event->get_formatted_time_string() ); ?>
                </div>
            </div>

            <div class="detail-block">
                <div class="detail-block__title">
                    <?php get_template_part( 'partials/icon', '', [ 'icon' => 'location' ] ); ?>
                    <?php esc_html_e( 'Location', 'nuhe' ); ?>
                </div>

                <div class="detail-block__content">
                    <?php
                    $location = $event->get_location();

                    echo wp_kses_post( sprintf(
                        '<div>%s</div> <div>%s</div> <div>%s</div> <div>%s</div>',
                        $location->get_name(),
                        $location->get_street_address(),
                        $location->get_neighborhood(),
                        $location->get_address_locality()
                    ) );
                    ?>

                    <?php
                    if ( $location->get_google_maps_link() ) {
                        get_template_part(
                            'partials/link',
                            '',
                            [
                                'url'    => $location->get_google_maps_link(),
                                'title'  => __( 'Open map', 'nuhe' ),
                                'target' => '_blank',
                                'icon'   => 'angle-right',
                            ]
                        );
                    }
                    ?>
                </div>
            </div>

            <div class="detail-block">
                <div class="detail-block__title">
                    <?php get_template_part( 'partials/icon', '', [ 'icon' => 'info-circle' ] ); ?>
                    <?php esc_html_e( 'Other information', 'nuhe' ); ?>
                </div>

                <div class="detail-block__content">
                    <?php
                    $links = [
                        [
                            'url'   => 'tel:' . $location->get_telephone(),
                            'title' => $location->get_telephone(),
                            'icon'  => false,
                        ],
                        [
                            'url'    => $event->get_info_url(),
                            'title'  => __( 'Website', 'nuhe' ),
                            'target' => '_blank',
                            'icon'   => 'angle-right',
                        ],
                    ];

                    get_template_part( 'partials/views/event/detail-block-content-links', '', $links );
                    ?>
                </div>
            </div>

            <div class="detail-block">
                <div class="detail-block__title">
                    <?php get_template_part( 'partials/icon', '', [ 'icon' => 'map' ] ); ?>
                    <?php esc_html_e( 'Directions', 'nuhe' ); ?>
                </div>

                <div class="detail-block__content">
                    <?php
                    $links = [
                        [
                            'url'    => $location->get_hsl_directions_link(),
                            'title'  => __( 'Directions (HSL)', 'nuhe' ),
                            'target' => '_blank',
                            'icon'   => 'angle-right',
                        ],
                        [
                            'url'    => $location->get_google_directions_link(),
                            'title'  => __( 'Directions (Google)', 'nuhe' ),
                            'target' => '_blank',
                            'icon'   => 'angle-right',
                        ],
                    ];

                    get_template_part( 'partials/views/event/detail-block-content-links', '', $links );
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
