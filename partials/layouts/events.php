<?php
/**
 * Events partial
 *
 * Accepts args:
 *  - title: <string|bool>
 *  - events: <Event[]>
 */

use Geniem\Theme\Settings;

/**
 * Partial args from flexible content layout.
 *
 * @var array $args partial args.
 */
$args = wp_parse_args( $args, [
    'title'  => false,
    'events' => [],
    'limit'  => 6,
] );

$external_link = Settings::get_setting( 'linked_events_external_url' );

if ( $args['events'] ) {
    get_template_part(
        'partials/views/event/event',
        'grid',
        [
            'events'        => $args['events'],
            'title'         => $args['title'],
            'external_link' => $external_link ?? false,
            'limit'         => $args['limit'],
            'hide_koro'     => true,
        ]
    );
}
