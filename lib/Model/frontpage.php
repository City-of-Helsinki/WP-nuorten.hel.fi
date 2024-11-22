<?php
/**
 * Define the FrontPage class
 */

namespace Geniem\Theme\Model;

use \Geniem\Theme\Interfaces\Model;
use \Geniem\Theme\Traits;

/**
 * FrontPage class
 */
class FrontPage implements Model {

    use Traits\FlexibleContent;
    use Traits\FlexibleSidebar;
    use Traits\ThemeColor;

    /**
     * This defines the name of this model.
     */
    const NAME = 'FrontPage';

    /**
     * This defines the template of this model.
     */
    const TEMPLATE = 'frontpage.php';

    /**
     * Add hooks and filters from this model
     *
     * @return void
     */
    public function hooks() : void {}

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
     * Get content sections.
     *
     * @return void|array Returns array of sections, void if no data.
     */
    public function get_content_sections() {
        $show_sections    = \get_field( 'show_sections' );
        $content_sections = \get_field( 'content_sections' );

        if ( $show_sections === false || empty( $content_sections ) ) {
            return;
        }

        return array_map( function( $section, $key ) {
            $section['url']         = \get_the_permalink( $key );
            $section['color_theme'] = $this->get_theme_color( $key );

            $links = empty( $section['section_links'] )
                    ? false
                    : array_map( fn( $link ) => $link['link'], $section['section_links'] );

            $section['section_links']       = empty( $section['section_description'] ) ? $links : false;
            $section['section_description'] = empty( $section['section_links'] ) ? $section['section_description'] : '';

            return $section;

        }, $content_sections, array_keys( $content_sections ) );
    }
}
