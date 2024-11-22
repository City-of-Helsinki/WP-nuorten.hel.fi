<?php
/**
 * Event item
 */

/**
 * Partial data
 *
 * @var object $args
 */

$event = $args;
$image = $event->get_primary_image();

?>

<div
    class="event-grid__item event-grid-item <?php echo $image || $default_image ? 'has-image' : 'has-empty-image'; ?>">
    <?php if ( $image ) : ?>
        <div class="event-grid-item__image objectfit-image-wrapper">
            <div class="objectfit-image-container">
                <a href="<?php echo esc_url( $event->get_permalink() ); ?>">
                    <img src="<?php echo esc_url( $image->get_url() ); ?>" class="objectfit-image"
                        alt="<?php echo esc_html( $image->get_alt_text() ); ?>">
                </a>
            </div>
        </div>
    <?php else: // phpcs:ignore ?>
        <?php if ( $default_image ) : ?>
            <div class="event-grid-item__image objectfit-image-wrapper">
                <div class="objectfit-image-container">
                    <a href="<?php echo esc_url( $event->get_permalink() ); ?>">
                        <img
                            src="<?php echo esc_url( $default_image['sizes']['large'] ); ?>"
                            class="objectfit-image"
                            alt="<?php echo esc_html( $default_image['alt'] ); ?>">
                    </a>
                </div>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <div class="event-grid-item__info">
        <?php
        $keywords = $event->get_keywords( 3 );

        if ( ! empty( $keywords ) ) :
            ?>
            <ul class="event-grid-item__keywords keywords">
                <?php foreach ( $keywords as $keyword ) : ?>
                    <li>
                        <span class="event__keyword keyword">
                            <?php echo esc_html( $keyword->get_name() ); ?>
                        </span>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <div class="event-grid-item__date">
            <?php echo esc_html( $event->get_formatted_time_string() ); ?>
        </div>

        <h3 class="event-grid-item__title">
            <a href="<?php echo esc_url( $event->get_permalink() ); ?>" class="event-grid-item__link">
                <?php echo esc_html( $event->get_name() ); ?>
            </a>
        </h3>

        <div class="event-grid-item__location">
            <?php echo esc_html( $event->get_location_string() ); ?>
        </div>

        <?php
        $tickets = $event->get_offers();

        if ( ! empty( $tickets ) ) :
            ?>
            <div class="event-grid-item__tickets">
                <?php foreach ( $tickets as $ticket ) : ?>
                    <?php if ( $ticket->is_free() ) : ?>
                        <div class="ticket__price">
                            <?php echo esc_html__( 'Free', 'nuhe' ); ?>
                        </div>
                    <?php else : // phpcs:ignore ?>
                        <div class="ticket__price">
                            <?php echo wp_kses_post( $ticket->get_price() ); ?>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
