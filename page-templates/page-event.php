<?php
/**
 * Single event template
 *
 * Template Name: Tapahtuma
 */

use Geniem\Theme\Model\PageEvent;
use Geniem\Theme\Settings;

get_header();

$partial_path = 'partials/views/event/event';
$page_event   = new PageEvent();
$event        = $page_event->get_event();
$event_title  = $page_event->get_event_title();
$breadcrumbs  = $page_event->get_breadcrumbs( null, $event_title );
?>
    <main id="main-content">
        <article class="event">
            <?php
            if ( $event ) {
                get_template_part( $partial_path, 'header', $event );
                get_template_part( $partial_path, 'content', [
                    'event'       => $event,
                    'breadcrumbs' => $breadcrumbs,
                ] );
            }
            else {
                get_template_part( $partial_path, 'not-found' );
            }
            ?>
        </article>
    </main>

<?php
$related       = $page_event->get_related_events( 6 );
$external_link = Settings::get_setting( 'linked_events_external_url' );

if ( $related ) {
    get_template_part(
        $partial_path,
        'grid',
        [
            'events'        => $related,
            'title'         => __( 'Related events', 'nuhe' ),
            'external_link' => $external_link,
            'hide_koro'     => true,
            'has_bg_color'  => true,
        ]
    );
}
?>

<?php
get_footer();
