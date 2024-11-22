<?php
/**
 * WP Events events
 */

/**
 * Partial args from flexible content layout.
 *
 * @var array $args partial args.
 */

use Geniem\Events\PostTypes\Event;

$args = wp_parse_args( $args, [
    'title'  => false,
    'events' => [],
    'limit'  => 6,
] );

if ( empty( $args['events'] ) ) {
    return;
}

$limit   = intval( $args['limit'] );
$columns = $limit % 2 === 0 ? 2 : 3;
$columns = ( 6 === $limit ) ? 3 : $columns;
?>
<div class="nuhe-layout nuhe-layout--main-content nuhe-events has-<?php echo esc_attr( $columns ); ?>-columns">
    <div class="event-grid">
        <div class="container">
            <?php if ( ! empty( $args['title'] ) ) : ?>
                <h2 class="event-grid__title">
                    <?php echo esc_html( $args['title'] ); ?>
                </h2>
            <?php endif; ?>

            <div class="event-grid__grid">
                <?php
                foreach ( $args['events'] as $event ) :
                    $image_class = has_post_thumbnail( $event->ID ) ? 'has-image' : 'has-empty-image';
                    ?>
                    <div class="event-grid__item event-grid-item <?php echo esc_attr( $image_class ); ?>">
                        <?php if ( has_post_thumbnail( $event->ID ) ) : ?>
                            <div class="event-grid-item__image objectfit-image-wrapper">
                                <div class="objectfit-image-container">
                                    <a href="<?php echo esc_url( get_the_permalink( $event->ID ) ); ?>">
                                        <?php
                                        echo get_the_post_thumbnail(
                                            $event->ID,
                                            'large',
                                            [ 'class' => 'objectfit-image' ]
                                        );
                                        ?>
                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="event-grid-item__info">
                            <?php
                            $keywords = Event::get_keywords( $event->ID );

                            if ( ! empty( $keywords ) ) :
                                ?>
                                <ul class="event-grid-item__keywords keywords">
                                    <?php foreach ( $keywords as $keyword ) : ?>
                                        <li>
                                            <span class="event__keyword keyword">
                                                <?php echo esc_html( $keyword->name ); ?>
                                            </span>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>

                            <div class="event-grid-item__date">
                                <?php echo esc_html( Event::get_formatted_time_string( $event->ID ) ); ?>
                            </div>

                            <h3 class="event-grid-item__title">
                                <a href="<?php echo esc_url( get_the_permalink( $event->ID ) ); ?>"
                                    class="event-grid-item__link">
                                    <?php echo esc_html( get_the_title( $event->ID ) ); ?>
                                </a>
                            </h3>

                            <div class="event-grid-item__location">
                                <?php the_field( 'location', $event->ID ); ?>
                            </div>

                            <?php if ( get_field( 'ticket_info', $event->ID ) ) : ?>
                                <div class="event__tickets">
                                    <div class="ticket__price">
                                        <?php the_field( 'ticket_info', $event->ID ); ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
