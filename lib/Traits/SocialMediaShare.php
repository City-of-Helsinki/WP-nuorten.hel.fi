<?php
/**
 * Social media share
 */

namespace Geniem\Theme\Traits;

/**
 * SocialMediaShare trait
 */
trait SocialMediaShare {

    /**
     * This constructs social media sharing links.
     *
     * @param string $share_title
     *
     * @return array Social media share links.
     */
    public function get_sharing_data( string $share_title = '' ) {
        \add_filter( 'html_classes', [ $this, 'add_classes_to_html' ] );

        $post_url = \get_permalink();

        return [
            'share_title' => $share_title,
            'links'       => [
                [
                    'service'    => 'link',
                    'url'        => $post_url,
                    'aria_label' => _x( 'Copy link address', 'socialmediashare', 'nuhe' ),
                    'type'       => 'button',
                ],
                [
                    'service'    => 'facebook',
                    'url'        => 'https://www.facebook.com/sharer/sharer.php?u=' . $post_url,
                    'aria_label' => _x( 'Share on Facebook - Opens in a new tab.', 'socialmediashare', 'nuhe' ),
                    'type'       => 'link',
                ],
                [
                    'service'    => 'twitter',
                    'url'        => 'https://twitter.com/share?&url=' . $post_url,
                    'aria_label' => _x( 'Share on Twitter - Opens in a new tab.', 'socialmediashare', 'nuhe' ),
                    'type'       => 'link',
                ],
                [
                    'service'    => 'linkedin',
                    'url'        => 'https://www.linkedin.com/shareArticle?mini=true&url=' . $post_url,
                    'aria_label' => _x( 'Share on LinkedIn - Opens in a new tab', 'socialmediashare', 'nuhe' ),
                    'type'       => 'link',
                ],
            ],
        ];
    }

    /**
     * Add classes to html.
     *
     * @param array $classes Array of classes.
     * @return array Array of classes.
     */
    public function add_classes_to_html( $classes ) : array {
        $classes[] = 'SocialMediaShare';
        return $classes;
    }
}
