<?php
/**
 * Define the PageTheme class.
 */

namespace Geniem\Theme\Model;

use \Geniem\Theme\Interfaces\Model;
use Geniem\Theme\Traits;
use Geniem\Theme\ACF\Layouts\NewsList;

/**
 * PageTheme class
 */
class PageTheme implements Model {

    use Traits\FlexibleContent;
    use Traits\FlexibleSidebar;
    use Traits\Breadcrumbs;

    /**
     * This defines the name of this model.
     */
    const NAME = 'PageTheme';

    /**
     * This defines the template of this model.
     */
    const TEMPLATE = 'page-templates/page-theme.php';

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
     * PageTheme constructor.
     */
    public function __construct() {
        $this->sections = [];
    }

    /**
     * Page constructor.
     */

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

        $links = array_map( function ( $link ) {
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

    /**
     * Get anchor links.
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

        return array_map( function ( $section ) {
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

                $sections[ $key ] = $value;
            }
        }

        $this->sections = $sections;
    }
}
