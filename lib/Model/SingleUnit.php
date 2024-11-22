<?php
/**
 * Define the SingleUnit class.
 */

namespace Geniem\Theme\Model;

use \Geniem\Theme\Interfaces\Model;
use Geniem\Theme\Traits;
use Geniem\Theme\PostType;
use \Geniem\Theme\Integrations\Palvelukartta;
use \Geniem\Theme\Integrations\Respa;
use \Geniem\Theme\Integrations\LinkedEvents\ApiClient;
use Geniem\Theme\Integrations\LinkedEvents\Entities\Event;

/**
 * SingleUnit class
 */
class SingleUnit implements Model {

    use Traits\FlexibleContent;
    use Traits\Breadcrumbs;

    /**
     * This defines the name of this model.
     */
    const NAME = 'SingleUnit';

    /**
     * This holds the unit.
     *
     * @var bool|Palvelukartta\Entities\Unit $unit
     */
    private $unit = false;

    /**
     * This holds the unit data.
     *
     * @var array $unit
     */
    private $unit_data = [];

    /**
     * This holds the unit type.
     *
     * @var string $unit_type
     */
    private $unit_type;

    /**
     * This holds the sections data of the unit.
     *
     * @var array $sections
     */
    private $sections = [];

    /**
     * SingleUnit Constructor
     *
     * @param string|null $post_type Post type.
     */
    public function __construct( ?string $post_type = PostType\YouthCenter::SLUG ) {
        $this->set_unit_type( $post_type );
        $this->set_sections_data();
        $this->generate_video_embed();
        $this->filter_some_links();
        $this->filter_instructors();
        $this->set_unit();
        $this->set_unit_data();
        $this->filter_unit_links();
        $this->modify_intro_group();
        \add_filter( 'nuhe_modify_localized_data', \Closure::fromCallable( [ $this, 'set_unit_localized_data' ] ) );
    }

    /**
     * Add hooks and filters from this model
     *
     * @return void
     */
    public function hooks() : void {
        \add_filter( 'html_classes', [ $this, 'add_classes_to_html' ] );
        \add_filter(
            'template_include',
            \Closure::fromCallable( [ $this, 'unit_template' ] )
        );
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
     * Add classes to html.
     *
     * @param array $classes Array of classes.
     *
     * @return array Array of classes.
     */
    public function add_classes_to_html( $classes ) : array {
        if ( $this->is_singular_unit() ) {
            $classes[] = $this->get_name();
        }

        return $classes;
    }

    /**
     * Check if singular unit.
     *
     * @return bool
     */
    private function is_singular_unit() : bool {
        $post_types = [
            PostType\YouthCenter::SLUG,
            PostType\HighSchool::SLUG,
            PostType\VocationalSchool::SLUG,
        ];

        return is_singular( $post_types );
    }

    /**
     * Override template.
     *
     * @param string $template Template path.
     *
     * @return string
     */
    private function unit_template( string $template ) {
        if ( $this->is_singular_unit() ) {
            $template = get_stylesheet_directory() . '/single-unit.php';
        }

        return $template;
    }

    /**
     * Set unit type
     *
     * @var string $post_type Unit post type
     * @return void
     */
    private function set_unit_type( string $post_type ) : void {
        $this->unit_type = $post_type;
    }

    /**
     * Set sections data
     *
     * @return void
     */
    public function set_sections_data() : void {
        if ( ! function_exists( 'get_field' ) ) {
            return;
        }

        $sections          = $this->unit_type === PostType\YouthCenter::SLUG
                            ? \get_field( 'youth_center_group' )
                            : \get_field( 'school_group' );
        $flexible_sections = \get_field( 'flexible_content' );

        if ( ! empty( $flexible_sections ) ) {
            $sections = array_merge( $sections, $flexible_sections );
        }
        if ( empty( $sections ) ) {
            return;
        }

        foreach ( $sections as $key => $value ) {
            if ( empty( $sections[ $key ]['anchor_link_text'] ) ) {
                unset( $sections[ $key ]['anchor'] );
            } else {
                $sections[ $key ]['anchor'] = [
                    'title' => $sections[ $key ]['anchor_link_text'],
                    'url'   => \sanitize_title( $sections[ $key ]['anchor_link_text'] ),
                ];
            }
        }

        $this->sections = $sections;
    }

    /**
     * Get anchors.
     *
     * @return array
     */
    public function get_anchors() : array {
        $sections = array_filter( $this->sections, function( $section ) {
            return ! empty( $section['title'] );
        } );

        if ( empty( $sections ) ) {
            return [];
        }

        return array_map( function ( $section ) {
            return [
                'title' => $section['anchor']['title'] ?? '',
                'url'   => isset( $section['anchor']['url'] ) ? '#' . $section['anchor']['url'] : '',
            ];
        }, $sections );
    }

    /**
     * Get sections.
     *
     * @return array
     */
    public function get_sections() : array {
        return $this->sections;
    }

    /**
     * Filter out some links without url.
     *
     * @return void
     */
    private function filter_some_links() : void {
        if ( empty( $this->sections ) ) {
            return;
        }

        $some_links = $this->sections['instructors_group']['some_links'] ?? [];

        if ( empty( $some_links ) ) {
            return;
        }

        $this->sections['instructors_group']['some_links'] = array_filter( $some_links, function ( $link ) {
            return ! empty( $link['some_link'] );
        } );
    }

    /**
     * Set unit.
     *
     * @return void
     */
    private function set_unit() : void {
        if ( empty( $this->sections ) ) {
            return;
        }

        $selected_unit = $this->unit_type === PostType\YouthCenter::SLUG
                        ? \get_field( 'unit' )
                        : \get_field( 'school_unit' );
        if ( empty( $selected_unit ) ) {
            return;
        }

        $palvelukartta_api = new Palvelukartta\ApiClient();
        $this->unit        = $palvelukartta_api->get_unit_by_id( $selected_unit );
    }

    /**
     * Set unit data.
     *
     * @return void
     */
    private function set_unit_data() : void {
        if ( ! $this->unit ) {
            return;
        }

        $links             = [];
        $hsl_link          = $this->unit->get_hsl_directions_link();
        $google_maps_links = $this->unit->get_google_directions_link();

        if ( ! empty( $hsl_link ) ) {
            $links[] = [
                'title' => __( 'HSL Journey Planner', 'nuhe' ),
                'url'   => $this->unit->get_hsl_directions_link(),
            ];
        }

        if ( ! empty( $google_maps_links ) ) {
            $links[] = [
                'title' => __( 'Google maps', 'nuhe' ),
                'url'   => $this->unit->get_google_directions_link(),
            ];
        }

        $unit_data = [
            'street_address'       => $this->unit->get_street_address(),
            'zip_code'             => $this->unit->get_address_zip(),
            'city'                 => $this->unit->get_address_city(),
            'address_postal_full'  => $this->unit->get_address_postal_full(),
            'google_maps_location' => $this->unit->get_google_maps_link(),
            'links'                => $links,
            'description'          => $this->unit->get_desc(),
        ];

        $this->unit_data = $unit_data;
    }

    /**
     * Set unit localized data.
     *
     * @param array $localized_data .
     *
     * @return array
     */
    private function set_unit_localized_data( array $localized_data ) : array {
        if ( ! $this->unit ) {
            return $localized_data;
        }

        $unit_data = [];
        $latitude  = $this->unit->get_latitude();
        $longitude = $this->unit->get_longitude();
        $name      = $this->unit->get_name();

        if ( ! empty( $latitude ) ) {
            $unit_data['latitude'] = $latitude;
        }

        if ( ! empty( $longitude ) ) {
            $unit_data['longitude'] = $longitude;
        }

        if ( ! empty( $name ) ) {
            $unit_data['name'] = $name;
        }

        if ( ! empty( $unit_data ) ) {
            $localized_data['unit_data'] = $unit_data;
        }

        return $localized_data;
    }

    /**
     * Get unit data.
     */
    public function get_unit_data() {
        return $this->unit_data;
    }

    /**
     * Filter out rows without instructor.
     *
     * @return void
     */
    private function filter_instructors() : void {
        if ( empty( $this->sections ) ) {
            return;
        }

        $instructors = $this->sections['instructors_group']['instructors'] ?? [];

        if ( empty( $instructors ) ) {
            return;
        }

        $this->sections['instructors_group']['instructors'] = array_filter( $instructors, function ( $instructor ) {
            return ! empty( $instructor['instructor'] );
        } );
    }

    /**
     * Get respa spaces custom field data.
     *
     * @return array|bool
     */
    public function get_respa_spaces_data() {
        $data = \get_field( 'youth_center_group' )['respa_spaces_group'] ?? '';

        if (
            empty( $this->unit )
            || empty( $data )
            || empty( $data['title'] )
            || empty( $data['respa_spaces_selection'] )
            ) {
            return false;
        }

        $selected_spaces = array_flip( $data['respa_spaces_selection'] );

        $api    = new Respa\ApiClient();
        $spaces = $api->get_spaces_by_unit_id( $this->unit->get_id() );

        if ( empty( $spaces ) ) {
            return false;
        }

        $count        = 0;
        $target_count = count( $selected_spaces );

        foreach ( $spaces as $space ) {
            $space_id = $space->get_id();

            if ( array_key_exists( $space_id, $selected_spaces ) ) {
                $data['respa_spaces'][] = [
                    'name'  => $space->get_name(),
                    'url'   => $space->get_url(),
                    'price' => $space->get_price(),
                    'desc'  => $space->get_description(),
                    'image' => $space->get_image(),
                ];
                $count++;
            }

            if ( $target_count === $count ) {
                break;
            }
        }

        if ( empty( $data['respa_spaces'] ) ) {
            return false;
        }

        $data['anchor_id'] = sanitize_title( $data['anchor_link_text'] ?: $data['title'] );

        return $data;
    }

    /**
     * Generate video embed.
     *
     * @return void
     */
    private function generate_video_embed() : void {
        if ( empty( $this->sections ) ) {
            return;
        }

        $video_url = $this->sections['video_group']['video_url'] ?? '';
        if ( empty( $video_url ) ) {
            $this->sections['video_group'] = false;

            return;
        }

        if ( strpos( $video_url, 'helsinkikanava' ) !== false ) {
            $video_url = str_replace( 'player', 'player/embed', $video_url );

            $this->sections['video_group']['video_url'] = $video_url;

            return;
        }

        $oembed = \wp_oembed_get( $video_url );
        if ( $oembed === false ) {
            return;
        }

        preg_match( '/src="(.+?)"/', $oembed, $matches );

        $this->sections['video_group']['video_url'] = $matches[1];
    }

    /**
     * Filter unit links list
     *
     * @return void
     */
    private function filter_unit_links() : void {
        if ( empty( $this->sections ) ) {
            return;
        }

        $links = $this->sections['unit_link_list']['links'];
        $links = ! empty( $links ) ? $links : [];

        if ( empty( $this->unit ) ) {
            return;
        }

        if ( $this->unit_type !== PostType\YouthCenter::SLUG ) {
            $school_website = [
                'link' => [
                    'url'   => $this->unit->get_www(),
                    'title' => __( 'School website', 'nuhe' ),
                ],
            ];

            array_unshift( $links, $school_website );
        }

        $links = array_filter( $links, function ( $link ) {
            return isset( $link['link']['url'] )
                    && ! empty( $link['link']['url'] )
                    && ! empty( $link['link']['title'] );
        } );

        if ( empty( $links ) ) {
            return;
        }

        $this->sections['unit_link_list']['links'] = array_map( function( $link ) {
            return $link['link'];
        }, $links );
    }

    /**
     * Modify intro group.
     *
     * @return void
     */
    private function modify_intro_group() : void {
        if (
            empty( $this->sections )
            || empty( $this->sections['intro_group'] )
            || empty( $this->unit )
        ) {
            return;
        }

        // add unit data
        $this->sections['intro_group']['unit'] = $this->unit_data;

        if ( $this->unit_type === PostType\YouthCenter::SLUG ) {
            return;
        }

        // add unit description
        $this->sections['intro_group']['intro_text'] = "<p>{$this->unit_data['description']}</p>";
    }

    /**
     * Get events.
     *
     * @return Event[]|bool
     */
    public function get_events() {
        if ( empty( $this->unit ) ) {
            return [];
        }
        $unit_id = $this->unit->get_id();

        $api = new ApiClient();

        return $api->get_nuta_events( [
            'location' => "tprek:{$unit_id}",
        ] );
    }
}
