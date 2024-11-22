<?php
/**
 * Define the PageVolunteering class.
 */

namespace Geniem\Theme\Model;

use \Geniem\Theme\Interfaces\Model;
use Geniem\Theme\Traits;

/**
 * PageVolunteering class
 */
class PageVolunteering implements Model {

    use Traits\FlexibleContent;
    use Traits\Breadcrumbs;

    /**
     * This defines the name of this model.
     */
    const NAME = 'PageVolunteering';

    /**
     * This defines the template of this model.
     */
    const TEMPLATE = 'page-templates/page-volunteering.php';

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
     * Get important links.
     *
     * @return array|false Returns array with title and links if links found, else returns false.
     */
    public function get_important_links() {
        $links_group = \get_field( 'important_links_group' );
        $links       = ! empty( $links_group ) ? $links_group['important_links'] : [];

        if ( empty( $links ) ) {
            return false;
        }

        $links = array_filter( $links, function( $link ) {
            return is_array( $link['link'] ) && ! empty( $link['link']['title'] ) && ! empty( $link['link']['url'] );
        } );

        if ( empty( $links ) ) {
            return false;
        }

        $links = array_map( function( $link ) {
            return $link['link'];
        }, $links );

        $icon     = $links_group['title_icon'];
        $icon_url = ! empty( $icon ) ? $icon['url'] : '';

        return [
            'title' => __( 'Important links', 'nuhe' ),
            'icon'  => $icon_url,
            'links' => $links,
        ];
    }
}
