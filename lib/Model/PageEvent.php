<?php
/**
 * PageEvent
 *
 * Model for page-templates/page-event.php
 */

namespace Geniem\Theme\Model;

use Closure;
use Geniem\Theme\Interfaces\Model;
use Geniem\Theme\Integrations\LinkedEvents\ApiClient;
use Geniem\Theme\Integrations\LinkedEvents\Entities\Event;
use Geniem\Theme\Localization;
use Geniem\Theme\Traits;
use Geniem\Theme\Settings;

/**
 * Class PageEvent
 *
 * @package Geniem\Theme\Model
 */
class PageEvent implements Model {

    use Traits\Breadcrumbs;

    /**
     * This defines the name of this model.
     */
    const NAME = 'PageEvent';

    /**
     * Template
     */
    const TEMPLATE = 'page-templates/page-event.php';

    /**
     * Title
     *
     * @var string
     */
    private $title;

    /**
     * Instance of ApiClient
     *
     * @var ApiClient
     */
    private ApiClient $api;

    /**
     * PageEvent constructor.
     */
    public function __construct() {
        $this->api = new ApiClient();
        $this->set_event_title();
    }

    /**
     * Add hooks and filters from this model
     *
     * @return void
     */
    public function hooks() : void {

        \add_filter(
            'the_seo_framework_title_from_generation',
            Closure::fromCallable( [ $this, 'alter_title' ] )
        );

        // og:title.
        \add_filter(
            'the_seo_framework_title_from_custom_field',
            Closure::fromCallable( [ $this, 'alter_title' ] )
        );

        // og:image.
        \add_filter(
            'the_seo_framework_image_generation_params',
            Closure::fromCallable( [ $this, 'alter_image' ] )
        );

        // og:description.
        \add_filter(
            'the_seo_framework_custom_field_description',
            Closure::fromCallable( [ $this, 'alter_desc' ] )
        );

        // og:url.
        \add_filter(
            'the_seo_framework_ogurl_output',
            Closure::fromCallable( [ $this, 'alter_url' ] )
        );

        \add_filter(
            'nuhe_lang_nav',
            Closure::fromCallable( [ $this, 'add_links_to_event_translations' ] )
        );
    }

    /**
     * Add event name to title.
     *
     * @param string $title Title.
     * @return string
     */
    protected function alter_title( string $title ) : string {
        if ( ! is_page_template( static::TEMPLATE ) ) {
            return $title;
        }

        $event = $this->get_event();

        if ( $event ) {
            $title = $event->get_name();
        }
        else {
            $title = __( 'Event not found', 'nuhe' );
        }

        return $title;
    }

    /**
     * Add image for og:image.
     *
     * @param array $params An array of SEO framework image parameters.
     * @return array
     */
    protected function alter_image( $params ) {

        if ( ! is_page_template( static::TEMPLATE ) ) {
            return $params;
        }

        $event = $this->get_event();

        if ( $event ) {

             // Ensure our custom generator is ran first.
            $params['cbs'] = array_merge(
                [ 'nuhe' => Closure::fromCallable( [ $this, 'seo_image_generator' ] ) ],
                $params['cbs']
            );
        }

        return $params;
    }

    /**
     * Custom generator for The SEO Framework og images.
     *
     * @yield array : {
     *     string url: The image URL,
     *     int     id: The image ID,
     * }
     */
    protected function seo_image_generator() {

        $event = $this->get_event();
        $image = $event->get_primary_image();

        if ( $image ) {
            yield [
                'url' => $image->get_url() ?? '',
                'id'  => $image->get_id() ?? '',
            ];
        }
    }

    /**
     * This sets the content of og:description.
     *
     * @param string $description The original description.
     * @return string
     */
    protected function alter_desc( $description ) {

        if ( ! is_page_template( static::TEMPLATE ) ) {
            return $description;
        }

        $event = $this->get_event();

        if ( $event ) {
            $description = $event->get_short_description();
        }

        return $description;
    }

    /**
     * This sets the content of og:url.
     *
     * @param string $url The original URL.
     * @return string
     */
    protected function alter_url( $url ) {

        if ( ! is_page_template( static::TEMPLATE ) ) {
            return $url;
        }

        $event = $this->get_event();

        if ( $event ) {
            $url = $event->get_permalink();
        }

        return $url;
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
     * Set event title
     *
     * @return void
     */
    private function set_event_title() : void {
        $event = $this->get_event();

        if ( $event ) {
            $title = $event->get_name();
        }
        else {
            $title = __( 'Event not found', 'nuhe' );
        }

        $this->title = $title;
    }

    /**
     * Get title of the event
     *
     * @return string Event title
     */
    public function get_event_title() {
        return $this->title;
    }

    /**
     * Add classes to html.
     *
     * @param array $classes Array of classes.
     *
     * @return array Array of classes.
     */
    public function add_classes_to_html( $classes ) : array {
        return $classes;
    }

    /**
     * Get event
     *
     * @return false|Event
     */
    public function get_event() {
        $event_id = get_query_var( 'event_id', false );

        if ( ! $event_id ) {
            return false;
        }

        return $this->api->get_event_by_id( $event_id );
    }

    /**
     * Get related events
     *
     * @param int|bool $limit Limit.
     *
     * @return false|Event[]
     */
    public function get_related_events( $limit = false ) {
        $current_event = $this->get_event();

        if ( ! $current_event ) {
            return false;
        }

        $params   = [
            'language' => Localization::get_current_language(),
        ];
        $keywords = $current_event->get_keywords();

        if ( ! empty( $keywords ) ) {
            $keywords          = array_map( fn( $kw ) => $kw->get_id(), $keywords );
            $params['keyword'] = implode( ',', $keywords );
        }

        $events           = $this->api->get_events( $params );
        $current_event_id = $current_event->get_id();

        if ( empty( $events ) ) {
            return false;
        }

        $events = array_filter( $events, function ( $event ) use ( $current_event_id ) {
            return $event->get_id() !== $current_event_id;
        } );

        if ( $limit && ! empty( $events ) ) {
            $events = array_slice( $events, 0, $limit );
        }

        return $events;
    }

    /**
     * Add links to event translations
     *
     * @param array $lang_data Language navigation data.
     */
    private function add_links_to_event_translations( array $lang_data ) : array {
        if ( ! is_page_template( static::TEMPLATE ) ) {
            return $lang_data;
        }

        $event          = $this->get_event();
        $events_page_id = Settings::get_setting( 'linked_events_page' );
        $all_languages  = $lang_data['all'] ?: [];

        // bail if no events page set or event empty or no all languages
        if ( empty( $events_page_id ) || empty( $event ) || empty( $all_languages ) ) {
            return $lang_data;
        }

        $event_id     = $event->get_id();
        $current_lang = $lang_data['current'] ?: [];

        $language_nav_all = array_map( function( $item ) use ( $event_id ) {
            $url         = $item['url'];
            $item['url'] = \add_query_arg(
                [
                    'event_id' => $event_id,
                ],
                $url
            );

            return $item;
        }, $all_languages );

        return [
            'current' => $current_lang,
            'all'     => $language_nav_all,
        ];
    }
}
