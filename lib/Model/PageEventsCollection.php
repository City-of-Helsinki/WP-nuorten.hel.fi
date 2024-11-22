<?php
/**
 * PageEventsCollection
 */

namespace Geniem\Theme\Model;

use \Geniem\Theme\Interfaces\Model;
use \Geniem\Theme\Traits\SocialMediaShare;
use \HKIH\LinkedEvents\API\ApiClient;
use \HKIH\LinkedEvents\API\Entities\Event;

/**
 * Class PageEventsCollection
 *
 * @package Geniem\Theme\Model
 */
class PageEventsCollection implements Model {

    use SocialMediaShare;

    /**
     * This defines the name of this model.
     */
    const NAME = 'PageEventsCollection';

    /**
     * This defines the template of this model.
     */
    const TEMPLATE = 'page-templates/page-events-collection.php';

    /**
     * This defines the timezone.
     */
    const TIMEZONE = 'Europe/Helsinki';

    /**
     * This holds the representation of current time zone.
     *
     * @var \DateTimeZone|string $date_time_zone
     */
    private $date_time_zone = '';

    /**
     * This holds the current timestamp.
     *
     * @var string $current_timestamp
     */
    private $current_timestamp = '';

    /**
     * PageEventsCollection constructor.
     */
    public function __construct() {
        $this->set_current_time_zone();
        $this->set_current_timestamp();
    }

    /**
     * Get class name constant
     *
     * @return string Class name constant
     */
    public function get_name() : string {
        return self::NAME;
    }

    /**
     * Hooks.
     *
     * @return void
     */
    public function hooks() : void {
        \add_action( 'admin_head', \Closure::fromCallable( [ $this, 'clean_page_edit_view' ] ) );
        \add_filter( 'use_block_editor_for_post_type', \Closure::fromCallable( [ $this, 'disable_gutenberg' ] ), 10, 1 );
        \add_filter( 'html_classes', \Closure::fromCallable( [ $this, 'add_class_to_html' ] ) );
    }

    /**
     * Set current time zone.
     *
     * @return void
     */
    private function set_current_time_zone() : void {
        $this->date_time_zone = new \DateTimeZone( static::TIMEZONE );
    }

    /**
     * Set current timestamp.
     *
     * @return void
     */
    private function set_current_timestamp() : void {
        $this->current_timestamp = ( new \DateTime( 'today', $this->date_time_zone ) )->getTimestamp();
    }

    /**
     * Disable Gutenberg.
     *
     * @param bool $can_edit 
     *
     * @return bool
     */
    private function disable_gutenberg( bool $can_edit ) : bool {
        if ( ! ( is_admin() && ! empty( $_GET['post'] ) ) ) {
            return $can_edit;
        }

        return \get_page_template_slug( \get_the_ID() ) === self::TEMPLATE ? false : true;
    }

    /**
     * Clean page edit view.
     *
     * @return void
     */
    private function clean_page_edit_view() : void {
        $screen = get_current_screen();
        if ( 'page' !== $screen->id || ! isset( $_GET['post'] ) ) {
            return;
        }

        if ( \get_page_template_slug( \get_the_ID() ) === self::TEMPLATE ) {
            \remove_post_type_support( 'page', 'editor' );
            \remove_meta_box( 'postimagediv', 'page', 'side' );
        }
    }

    /**
     * Get hero data.
     *
     * @return array
     */
    public function get_hero_data() : array {
        $backgroud_color = \get_field( 'background_color' ) ?: null;

        $hero_data = [
            'title'                  => \get_the_title(),
            'description'            => \get_field( 'description' ) ?: null,
            'background_color_class' => ! empty( $backgroud_color ) ? " has-bg-color has-bg-color--{$backgroud_color}" : '',
        ];

        return array_filter( $hero_data, function( $item ) {
            return ! empty( $item );
        } );
    }

    /**
     * Get event modules (repeater fields).
     *
     * @return array
     */
    public function get_event_modules() : array {
        $modules = \get_field( 'modules' ) ?: null;

        if ( empty( $modules ) || ! function_exists( 'hkih_linked_events' ) ) {
            return [];
        }

        $event_modules = [];

        foreach ( $modules as $module ) {
            $module_data = $this->get_module_data( $module );

            if ( ! empty( $module_data ) ) {
                $event_modules[] = $module_data;
            }
        }

        return $event_modules;
    }

    /**
     * Get module data.
     *
     * @param array $module
     *
     * @return array|null
     */
    private function get_module_data( array $module ) {
        $module_end_date_passed = $this->module_end_date_passed( $module['end_date'] ?: null );

        // bail early if module end date passed
        if ( $module_end_date_passed ) {
            return null;
        }

        $data = [];

        $data['title'] = $module['title'] ?: null;

        $api_client = new ApiClient();

        $selected_events = $module['selected_events'] ?: [];
        if ( $module['acf_fc_layout'] === 'event_selected' ) {
            if ( empty( $selected_events ) ) {
                return null;
            }

            foreach ( $selected_events as $key => $value ) {
                $data['events'][] = $api_client->get_event_by_id( $key );
            }

            if( $module['hide_past_events'] ) {
                $data['events'] = $this->filter_passed_events( $data['events'] );
            }

            $data['display_settings'] = $this->get_module_display_settings(
                count( $data['events'] ),
                $module['init_amount_of_events']
            );

            return $data;
        }

        // if event_search layout has selected events if selected
        $handled_selected_events = [];
        foreach ( $selected_events as $key => $value ) {
            $handled_selected_events[] = $api_client->get_event_by_id( $key );
        }

        $results_link = $module['result_link'];

        if ( empty( $results_link ) ) {
            return null;
        }

        $parts = parse_url( $results_link );
        parse_str( $parts['query'], $parts );

        if ( is_array( $parts ) ) {
            $parts['include'] = 'organizer,location,keywords';
        }

        $events = $api_client->get_events( $parts );

        if ( empty( $events ) && empty( $selected_events ) ) {
            return null;
        }

        if ( ! empty( $events ) && ! empty( $selected_events ) ) {
            // filter out selected events
            $events = array_filter( $events, function( Event $event ) use ( $selected_events ) {
                return ! array_key_exists( $event->get_id(), $selected_events );
            } );
        }

        $data['events'] = array_merge( $handled_selected_events, $events );

        if( $module['hide_past_events'] ) {
            $data['events'] = $this->filter_passed_events( $data['events'] );
        }
        
        $data['display_settings'] = $this->get_module_display_settings(
            count( $data['events'] ),
            $module['init_amount_of_events']
        );

        return $data;
    }

    /**
     * Get module display settings.
     *
     * @param int   $count
     * @param mixed $init_amount_of_events
     *
     * @return array|null
     */
    private function get_module_display_settings( int $count, $init_amount_of_events ) {
        $init_amount_of_events = intval( $init_amount_of_events );

        if ( empty( $init_amount_of_events ) ) {
            return null;
        }

        return [
            'init_amount_of_events'      => $init_amount_of_events,
            'amount_of_show_more_events' => $count - $init_amount_of_events,
        ];
    }

    /**
     * Add class to html.
     *
     * @param array $classes Array of classes.
     * @return array Array of classes.
     */
    private function add_class_to_html( $classes ) : array {

        if ( ! is_page_template( self::TEMPLATE ) ) {
            return $classes;
        }

        $classes[] = self::NAME;
        return $classes;
    }

    /**
     * Module end date passed?
     *
     * @param string|null $end_date   End date.
     *
     * @return bool
     */
    private function module_end_date_passed( ?string $end_date ) : bool {
        if ( empty( $end_date ) ) {
            return false;
        }

        $end_timestamp = ( new \DateTime( $end_date, $this->date_time_zone ) )->getTimestamp();

        return $end_timestamp < $this->current_timestamp;
    }

    /**
     * Filter passed events.
     *
     * @param array $events    Array of events.
     *
     * @return array
     */
    private function filter_passed_events( array $events ) : array {
        if ( empty( $events ) ) {
            return [];
        }

        $today = date( 'Y-m-d' );

        $events = array_filter( $events, function( Event $event ) use ( $today ) {
            if( ! empty( $event->get_end_time() ) ) {
                return $today <= $event->get_end_time()->format( 'Y-m-d' );
            }

            if ( ! empty( $event->get_start_time() ) ) {
                return $today <= $event->get_start_time()->format( 'Y-m-d' );
            }

            return false;
        });

        return $events;
    }
}
