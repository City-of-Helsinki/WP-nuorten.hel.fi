<?php
/**
 * Trait for Flexible Content
 */

namespace Geniem\Theme\Traits;

use Geniem\Theme\Integrations\LinkedEvents\ApiClient;
use WP_Query;
use \Geniem\Theme\Localization;
use \Geniem\Theme\PostType;

/**
 * Trait FlexibleContent
 *
 * @package Geniem\Theme\Traits
 */
trait FlexibleContent {

    use FlexibleCommon;
    use FeaturedImage;

    /**
     * Layouts
     *
     * @var array|null $layouts
     */
    private $layouts = null;

    /**
     * Get layout array
     *
     * @return array
     */
    public function get_layouts_array() {
        $content = get_field( 'flexible_content' );
        $handled = [];

        if ( empty( $content ) ) {
            return [];
        }

        foreach ( $content as $layout ) {
            $data = $layout;

            if ( method_exists( $this, 'layout_' . $layout['acf_fc_layout'] ) ) {
                $data = $this->{'layout_' . $layout['acf_fc_layout']}( $layout );
            }

            $data = apply_filters( 'nuhe_acf_layout_' . $layout['acf_fc_layout'], $data );

            if ( ! empty( $data ) ) {
                $handled[] = [
                    'layout' => $layout['acf_fc_layout'],
                    'data'   => $data,
                ];
            }
        }

        $this->layouts = $handled;

        return $handled;
    }

    /**
     * Flexible content
     *
     * @return void
     */
    public function content() {
        if ( $this->layouts !== null && count( $this->layouts ) === 0 ) {
            return;
        }

        $layouts = $this->layouts === null ? $this->get_layouts_array() : $this->layouts;

        foreach ( $layouts as $layout ) {
            \get_template_part( "partials/layouts/${layout['layout']}", '', $layout['data'] );
        }
    }

    /**
     * Page list layout
     *
     * @param array $layout_data ACF layout data.
     *
     * @return array
     */
    private function layout_page_list( array $layout_data ) : array {
        $layout_data['context']       = 'main-content';
        $layout_data['use_container'] = true;
        $layout_data['use_grid']      = true;

        return $this->page_list( $layout_data );
    }

    /**
     * Article list layout
     *
     * @param array $layout_data ACF layout data.
     *
     * @return array
     */
    private function layout_article_list( array $layout_data ) : array {
        $layout_data['context']       = 'main-content';
        $layout_data['use_container'] = true;
        $layout_data['use_grid']      = true;

        return $this->article_list( $layout_data );
    }

    /**
     * Events layout
     *
     * @param array $layout_data ACF layout data.
     *
     * @return array
     */
    private function layout_events( array $layout_data ) : array {
        $api_client = new ApiClient();
        $params = [
            'language' => Localization::get_current_language(),
        ];

        if ( $layout_data['keywords'] ) {
            $params['keyword'] = implode( ',', $layout_data['keywords'] );
        }

        if ( $layout_data['disable_youth_service_restriction'] ) {
            $params['disable_youth_service_restriction'] = $layout_data['disable_youth_service_restriction'];
        }

        $layout_data['events'] = $api_client->get_events(
            $params,
            intval( $layout_data['limit'] )
        );

        return $layout_data;
    }

    /**
     * People list layout
     *
     * @param array $layout_data ACF layout data.
     *
     * @return array
     */
    private function layout_people_list( array $layout_data ) : array {
        if ( empty( $layout_data['people'] ) ) {
            return [];
        }

        $layout_data['columns'] = $this->get_columns_count( $layout_data['people'] );
        $layout_data['people']  = array_filter( $layout_data['people'], function ( $item ) {
            return ! empty( $item['person_name'] ) && ! empty( $item['person_image'] );
        } );

        if ( empty( $layout_data['people'] ) ) {
            return [];
        }

        return $layout_data;
    }

    /**
     * Tag cloud layout
     *
     * @param array $layout_data ACF layout data.
     *
     * @return array
     */
    private function layout_tag_cloud( array $layout_data ) : array {
        if ( empty( $layout_data['tags'] ) ) {
            return [];
        }

        // filter out tag links without link and title
        $layout_data['tags'] = array_filter( $layout_data['tags'], function ( $tag ) {
            return ! empty( $tag['tag_link'] ) && ! empty( $tag['tag_link']['title'] );
        } );

        if ( empty( $layout_data['tags'] ) ) {
            return [];
        }

        $layout_data['tags'] = array_map( function ( $tag ) {
            return $tag['tag_link'];
        }, $layout_data['tags'] );

        return $layout_data;

    }

    /**
     * Attention bulletin layout
     *
     * @param array $layout_data ACF layout data.
     *
     * @return array
     */
    private function layout_attention_bulletin( array $layout_data ) : array {
        if ( empty( $layout_data['links'] ) ) {
            return [];
        }

        // filter out links without link and title
        $layout_data['links'] = array_filter( $layout_data['links'], function ( $link ) {
            return ! empty( $link['link'] ) && ! empty( $link['link']['title'] );
        } );

        if ( empty( $layout_data['links'] ) ) {
            return [];
        }

        $layout_data['links'] = array_map( function ( $link ) {
            return $link['link'];
        }, $layout_data['links'] );

        if ( empty( $layout_data['title'] ) ) {
            return $layout_data;
        }

        $layout_data['anchor_id'] = sanitize_title( $layout_data['title'] );

        return $layout_data;
    }

    /**
     * Image text layout
     *
     * @param array $layout_data ACF layout data.
     *
     * @return array
     */
    private function layout_image_text( array $layout_data ) : array {
        if ( ! empty( $layout_data['ctas'] ) ) {
            // filter out ctas without link and title
            $layout_data['ctas'] = array_filter( $layout_data['ctas'], function ( $cta ) {
                return ! empty( $cta['cta'] ) && ! empty( $cta['cta']['title'] );
            } );

            if ( ! empty( $layout_data['ctas'] ) ) {
                $layout_data['ctas'] = array_map( function ( $cta ) {
                    return $cta['cta'];
                }, $layout_data['ctas'] );
            }
        }

        return $layout_data;
    }

    /**
     * Text banner layout
     *
     * @param array $layout_data ACF layout data.
     *
     * @return array
     */
    private function layout_text_banner( array $layout_data ) : array {
        $layout_data['link'] = ! empty( $layout_data['link'] ) && ! empty( $layout_data['link']['title'] )
            ? $layout_data['link']
            : [];

        return $layout_data;
    }

    /**
     * Sub page list layout
     *
     * @param array $layout_data ACF layout data.
     *
     * @return array
     */
    private function layout_sub_page_list( array $layout_data ) : array {
        if ( $layout_data['list_type'] === 'auto' ) {
            return $this->sub_page_auto_listing( $layout_data );
        }

        return $this->sub_page_manual_listing( $layout_data );
    }

    /**
     * Sub page auto listing
     *
     * @param array $layout_data ACF layout data.
     *
     * @return array
     */
    private function sub_page_auto_listing( array $layout_data ) : array {
        global $post;

        $default_args = [
            'post_type'      => PostType\Page::SLUG,
            'posts_per_page' => - 1,
            'order'          => 'ASC',
            'orderby'        => 'title',
        ];

        $pages = new WP_Query( array_merge( $default_args, [
            'post_parent' => $post->ID,
        ] ) );

        // return early if current page doesn't have child pages
        if ( ! $pages->have_posts() ) {
            return [];
        }

        $pages_ids   = array_map( fn( $page ) => $page->ID, $pages->posts );
        $child_pages = new WP_Query( array_merge( $default_args, [
            'post_parent__in' => $pages_ids,
        ] ) );
        $children    = $child_pages->posts;

        $items = [];
        foreach ( $pages->posts as $page ) {
            $page_item = [
                'image' => $this->get_featured_image( 'large', $page->ID ),
                'link'  => [
                    'url'   => \get_the_permalink( $page->ID ),
                    'title' => $page->post_title,
                ],
            ];

            if ( empty( $children ) ) {
                $items[] = $page_item;
                continue;
            }

            foreach ( $children as $key => $child ) {
                // ignore children without parent id match
                if ( $child->post_parent !== $page->ID ) {
                    continue;
                }

                $page_item['links'][] = [
                    'url'   => \get_the_permalink( $child->ID ),
                    'title' => $child->post_title,
                ];

                unset( $children[ $key ] );
            }

            $items[] = $page_item;
        }

        $layout_data['items'] = $items;

        $layout_data['columns'] = $this->get_subpage_columns_count( $layout_data['items'] );

        return $layout_data;
    }

    /**
     * Sub page manual listing
     *
     * @param array $layout_data ACF layout data.
     *
     * @return array
     */
    private function sub_page_manual_listing( array $layout_data ) : array {
        if ( empty( $layout_data['items'] ) ) {
            return [];
        }

        $layout_data['items'] = array_map( function ( $item ) {
            if ( empty( $item['links'] ) ) {
                return $item;
            }

            // filter out links without link and title
            $item['links'] = array_filter( $item['links'], function ( $link ) {
                return ! empty( $link['link'] ) && ! empty( $link['link']['title'] );
            } );

            if ( empty( $item['links'] ) ) {
                return $item;
            }

            $item['links'] = array_map( function ( $link ) {
                return $link['link'];
            }, $item['links'] );

            return $item;

        }, $layout_data['items'] );

        $layout_data['columns'] = $this->get_subpage_columns_count( $layout_data['items'] );

        return $layout_data;
    }

    /**
     * Get sub page columns count.
     *
     * @param array $items Item to count.
     *
     * @return int
     */
    private function get_subpage_columns_count( $items ) : int {
        $item_count = count( $items );

        return $item_count < 3 ? 2 : 3;
    }

    /**
     * Some feed layout
     *
     * @param array $layout_data ACF layout data.
     *
     * @return array
     */
    private function layout_some_embed( array $layout_data ) : array {
        $embed_id                     = ! empty( $layout_data['title'] ) ? md5( $layout_data['title'] ) : md5( 'some-feed' );
        $layout_data['skip_embed_id'] = "skip-embed-{$embed_id}";

        if ( empty( $layout_data['anchor_link_text'] ) && empty( $layout_data['title'] ) ) {
            return $layout_data;
        }

        $layout_data['anchor_id'] = sanitize_title( $layout_data['anchor_link_text'] ?: $layout_data['title'] );

        return $layout_data;
    }

    /**
     * Initiative list layout
     *
     * @param array $layout_data ACF layout data.
     *
     * @return array
     */
    private function layout_initiative_list( array $layout_data ) : array {
        return $this->initiative_list( $layout_data );
    }
}
