<?php
/**
 * Define the generic Page class
 */

namespace Geniem\Theme\Model;

use \Geniem\Theme\Interfaces\Model;
use \Geniem\Theme\Traits;

/**
 * Page class
 */
class Page implements Model {

    use Traits\FlexibleContent;
    use Traits\FlexibleSidebar;
    use Traits\Breadcrumbs;

    /**
     * This defines the name of this model.
     */
    const NAME = 'Page';

    /**
     * Add hooks and filters from this model
     *
     * @return void
     */
    public function hooks() : void {}

    /**
     * This holds the sections data of the unit.
     *
     * @var array $sections
     */
    private array $sections;

    /**
     * Page constructor.
     */
    public function __construct() {
        $this->sections = [];
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
     * @return array Array of classes.
     */
    public function add_classes_to_html( $classes ) : array {
        return $classes;
    }

    /**
     * Get anchors.
     *
     * @return array
     */
    public function get_anchors() : array {
        $sections = array_filter( $this->sections, function ( $section ) {
            return ! empty( $section['anchor_id'] );
        } );

        if ( empty( $sections ) ) {
            return [];
        }

        return array_map(function( $section ) {
            $section['anchor']['url'] = '#' . $section['anchor_id'];
            return $section['anchor'];
        }, $sections );
    }

    /**
     * Set sections data.
     *
     * @return void
     */
    public function set_sections_data() : void {
        $sections = \get_field( 'flexible_content' );
        if ( empty( $sections ) ) {
            return;
        }

        foreach ( $sections as $key => $value ) {
            // Check if the layout is NewsList
            if ( $value['acf_fc_layout'] === 'news_list' ) {
                if ( empty( $value['anchor_link_text'] ) ) {
                    $value['anchor_link_text'] = $value['title'];
                }

                $value['anchor'] = [
                    'title' => $value['anchor_link_text'],
                    'url'   => \sanitize_title( $value['anchor_link_text'] ),
                ];

                // Add anchor_id for the NewsList section
                $value['anchor_id'] = \sanitize_title( $value['anchor_link_text'] ?: $value['title'] );

                // Add the NewsList section to the sections array
                $this->sections[ $key ] = $value;
            }
        }
    }
}
