<?php
/**
 * FlexibleSidebar
 */

namespace Geniem\Theme\Traits;

/**
 * Trait FlexibleSidebar
 *
 * @package Geniem\Theme\Traits
 */
trait FlexibleSidebar {

    use FlexibleCommon;

    /**
     * Flexible Sidebar Content
     *
     * @param bool $echo Echo content or not. Defaults to true.
     * @return bool|void
     */
    public function sidebar( $echo = true ) {
        $content = get_field( 'sidebar_flexible_content' );

        if ( $echo === false ) {
            return $content;
        }

        if ( empty( $content ) ) {
            return;
        }

        foreach ( $content as $layout ) {
            $data = $layout;

            if ( method_exists( $this, 'sidebar_layout_' . $layout['acf_fc_layout'] ) ) {
                $data = $this->{'sidebar_layout_' . $layout['acf_fc_layout']}( $layout );
            }

            if ( ! empty( $data ) ) {
                \get_template_part( "partials/layouts/${layout['acf_fc_layout']}", '', $data );
            }
        }
    }

    /**
     * Page list layout
     *
     * @param array $layout_data ACF layout data.
     * @return array
     */
    private function sidebar_layout_page_list( array $layout_data ) : array {
        $layout_data['context']       = 'sidebar';
        $layout_data['use_container'] = false;
        $layout_data['use_grid']      = false;
        return $this->page_list( $layout_data );
    }

    /**
     * Article list layout
     *
     * @param array $layout_data ACF layout data.
     * @return array
     */
    private function sidebar_layout_article_list( array $layout_data ) : array {
        $layout_data['context']       = 'sidebar';
        $layout_data['use_container'] = false;
        $layout_data['use_grid']      = false;
        return $this->article_list( $layout_data );
    }

    /**
     * Link list layout
     *
     * @param array $layout_data ACF layout data.
     * @return array
     */
    private function sidebar_layout_link_list( array $layout_data ) : array {
        if ( empty( $layout_data['links'] ) ) {
            return $layout_data;
        }

        $links = array_filter( $layout_data['links'], function ( $link ) {
            return isset( $link['link']['url'] )
                    && ! empty( $link['link']['url'] )
                    && ! empty( $link['link']['title'] );
        } );

        $layout_data['links'] = array_map( function( $link ) {
            return $link['link'];
        }, $links );

        return $layout_data;
    }
}
