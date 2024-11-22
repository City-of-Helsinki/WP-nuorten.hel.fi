<?php
/**
 * Event header
 */

/**
 * Event is passed as args.
 *
 * @var \Geniem\Theme\Integrations\LinkedEvents\Entities\Event $args
 */

use Geniem\Theme\Settings;

$event         = $args;
$image         = $event->get_primary_image();
$default_image = false;

if ( ! $image ) {
    $default_image = Settings::get_setting( 'default_page_image' );
}
?>
<header class="event__header <?php echo $image || $default_image ? 'has-image' : 'has-empty-image'; ?>">
    <div class="container">
        <div class="event__header-inner">
            <?php if ( $image ) : ?>
                <div class="event__image objectfit-image-wrapper">
                    <div class="objectfit-image-container">
                        <img src="<?php echo esc_url( $image->get_url() ); ?>" class="objectfit-image"
                            alt="<?php echo esc_html( $image->get_alt_text() ); ?>">
                    </div>
                </div>
            <?php else : ?>
                <div class="event__image objectfit-image-wrapper">
                    <div class="objectfit-image-container">
                        <img
                            src="<?php echo esc_url( wp_get_attachment_image_url( $default_image['ID'], 'large' ) ); ?>"
                            class="objectfit-image"
                            alt="<?php echo esc_html( $default_image['alt'] ); ?>">
                    </div>
                </div>
            <?php endif; ?>

            <div class="event__info">
                <?php
                $keywords = $event->get_keywords();

                if ( ! empty( $keywords ) ) :
                    ?>
                    <ul class="event__keywords keywords">
                        <?php foreach ( $keywords as $keyword ) : ?>
                            <li>
                                <span class="event__keyword keyword">
                                    <?php echo esc_html( $keyword->get_name() ); ?>
                                </span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>

                <div class="event__date">
                    <?php echo esc_html( $event->get_formatted_time_string() ); ?>
                </div>

                <h1 class="event__title">
                    <?php echo esc_html( $event->get_name() ); ?>
                </h1>

                <div class="event__lead">
                    <?php echo esc_html( $event->get_short_description() ); ?>
                </div>

                <div class="event__location">
                    <?php
                    get_template_part( 'partials/icon', '', [ 'icon' => 'location' ] );
                    echo esc_html( $event->get_location_string() );
                    ?>
                </div>

                <?php
                $tickets = $event->get_offers();
                if ( ! empty( $tickets ) ) :
                    $single_ticket_url = $event->get_single_ticket_url();
                    ?>
                    <div class="event__tickets">
                        <?php foreach ( $tickets as $ticket ) : ?>
                            <div class="ticket__price">
                                <?php
                                get_template_part( 'partials/icon', '', [ 'icon' => 'ticket' ] );

                                if ( $ticket->is_free() ) {
                                    echo esc_html__( 'Free', 'nuhe' );
                                }
                                else {
                                    if ( ! empty( $ticket->get_price() ) ) {
                                        echo wp_kses_post(
                                            sprintf(
                                                '%s %s',
                                                $ticket->get_price(),
                                                $ticket->get_description()
                                            )
                                        );
                                    }
                                    else {
                                        echo wp_kses_post( $ticket->get_description() );
                                    }
                                }
                                ?>
                            </div>

                            <?php if ( ! empty( $ticket->get_info_url() ) && ! $single_ticket_url ) : ?>
                                <div class="ticket__actions">
                                    <?php
                                    get_template_part(
                                        'partials/views/event/ticket-link',
                                        '',
                                        [
                                            'is_free' => $ticket->is_free(),
                                            'url'     => $ticket->get_info_url(),
                                            'classes' => [ 'theme-engel-dark' ],
                                        ]
                                    );
                                    ?>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>

                        <?php if ( $single_ticket_url ) : ?>
                            <div class="ticket__actions">
                                <?php
                                get_template_part(
                                    'partials/views/event/ticket-link',
                                    '',
                                    [
                                        'is_free' => $ticket->is_free(),
                                        'url'     => $single_ticket_url,
                                        'classes' => [ 'theme-engel-dark' ]
                                    ]
                                );
                                ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </div>
</header>
