<?php
/**
 * Unit opening hours
 */

/**
 * Opening hours data is passed as args.
 *
 * @var array $args
 */
$data               = $args;
$opening_hours_data = $data['opening_hours_data'];
?>
<?php if ( empty ( $data['is_events_hidden'] ) || empty ( $opening_hours_data['is_hidden'] ) ): ?>
<div
    class="unit-section unit-section--opening-hours has-bg-color has-bg-color--grey"
    id=<?php echo esc_attr( $opening_hours_data['anchor']['url'] ); ?>
>
    <div class="container">
        <div class="grid-columns grid-columns--2">
            <?php if ( empty ( $opening_hours_data['is_hidden'] ) ): ?>
            <div class="grid-columns__column">
                <h2 class="unit-section__title">
                    <?php echo esc_html( $opening_hours_data['title'] ); ?>
                </h2>
                <?php
                if ( ! empty( $opening_hours_data['opening_hours_text'] ) ) {
                    echo wp_kses_post( $opening_hours_data['opening_hours_text'] );
                }
                ?>
            </div>
            <?php endif; ?>
            <?php if ( empty ( $data['is_events_hidden'] ) ): ?>
            <div class="grid-columns__column">
                <h2 class="unit-section__title">
                    <?php esc_html_e( 'Upcoming events and hobbies', 'nuhe' ); ?>
                </h2>
                <?php
                if ( ! empty( $data['events'] ) ) :
                    $event_chunks = array_chunk( $data['events'], 4 );
                    ?>
                    <?php foreach ( $event_chunks as $key => $chunk ) : ?>
                        <?php $hidden_class = $key > 0 ? ' is-hidden' : ''; ?>
                        <div class="event-grid__grid event-grid__grid--<?php echo esc_attr( $key ); ?><?php echo esc_attr( $hidden_class ); ?>">
                            <?php
                            foreach ( $chunk as $event ) {
                                get_template_part( 'partials/views/event/event', 'item', $event );
                            }
                            ?>
                        </div>
                    <?php endforeach; ?>
                    <?php
                    if ( count( $event_chunks ) > 1 ) {
                        get_template_part( 'partials/button', '', [
                            'label'      => __( 'Show more events and hobbies', 'nuhe' ),
                            'icon-end'   => 'arrow-down',
                            'classes'    => [ 'theme-black', 'border-black', 'show-more-events' ],
                            'attributes' => [
                                'id'         => 'js-show-more-events',
                                'aria-label' => __( 'Show keywords', 'nuhe' ),
                            ],
                        ] );
                    }
                    ?>
                <?php else: // phpcs:ignore ?>
                <p><?php esc_html_e( 'No upcoming events and hobbies', 'nuhe' ); ?></p>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php endif; ?>
