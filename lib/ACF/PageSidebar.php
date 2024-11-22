<?php
/**
 * ACF fields for template PageSidebar
 *
 * @package Geniem\Theme\ACF
 */

namespace Geniem\Theme\ACF;

use Geniem\ACF\Exception;
use \Geniem\ACF\Field;
use \Geniem\ACF\Group;
use \Geniem\ACF\RuleGroup;
use \Geniem\Theme\PostType;
use \Geniem\Theme\ACF\Layouts;
use \Geniem\Theme\Model\FrontPage;
use Geniem\Theme\Model\PageHelbitJobs;
use Geniem\Theme\Model\PageVolunteering;
use Geniem\Theme\Model\PageYouthCouncilElections;
use Geniem\Theme\Model\PageEventsCollection;
use Geniem\Theme\Model\PageSitemap;
use Geniem\Theme\Model\PageIframe;

/**
 * Class PageSidebar
 *
 * @package Geniem\Theme\ACF
 */
class PageSidebar {

    /**
     * PageList fields.
     *
     * @var array
     */
    const PAGE_LIST_FIELDS = [
        'pages',
        'page',
    ];

    /**
     * ArticleList fields.
     *
     * @var array
     */
    const ARTICLE_LIST_FIELDS = [
        'category',
        'tag',
        'community_center',
        'amount',
        'articles',
        'article',
    ];

    /**
     * PageSidebar constructor.
     */
    public function __construct() {
        add_action( 'init', [ $this, 'register_fields' ] );
    }

    /**
     * Register fields
     */
    public function register_fields() {
        $group_title = _x( 'Oikea palsta', 'theme ACF', 'nuhe' );
        $key         = 'sidebar_fc';

        $field_group = new Group( $group_title );
        $field_group->set_key( 'fg_sidebar_flexible_content' );

        // Define rules for the field group
        $rules = [
            [
                'key'      => 'post_type',
                'value'    => PostType\Page::SLUG,
                'operator' => '==',
                'and'      => [
                    [
                        'key'      => 'page_template',
                        'value'    => FrontPage::TEMPLATE,
                        'operator' => '!=',
                    ],
                    [
                        'key'      => 'page_template',
                        'value'    => PageVolunteering::TEMPLATE,
                        'operator' => '!=',
                    ],
                    [
                        'key'      => 'page_template',
                        'value'    => PageYouthCouncilElections::TEMPLATE,
                        'operator' => '!=',
                    ],
                    [
                        'key'      => 'page_template',
                        'value'    => PageHelbitJobs::TEMPLATE,
                        'operator' => '!=',
                    ],
                    [
                        'key'      => 'page_template',
                        'value'    => PageEventsCollection::TEMPLATE,
                        'operator' => '!=',
                    ],
                    [
                        'key'      => 'page_template',
                        'value'    => PageSitemap::TEMPLATE,
                        'operator' => '!=',
                    ],
                    [
                        'key'      => 'page_template',
                        'value'    => PageIframe::TEMPLATE,
                        'operator' => '!=',
                    ],
                ],
            ],
            [
                'key'      => 'post_type',
                'value'    => PostType\Post::SLUG,
                'operator' => '==',
            ],
        ];

        $rules = apply_filters( 'nuhe_modify_page_sidebar_rules', $rules );

        // add rules
        array_walk( $rules, function( array $rule ) use ( $field_group ) {
            $rule_group = new RuleGroup();
            $rule_group->add_rule( $rule['key'], $rule['operator'], $rule['value'] );

            if ( isset( $rule['and'] ) && is_array( $rule['and'] ) ) {
                array_walk( $rule['and'], function( array $and_rule ) use ( $rule_group ) {
                    $rule_group->add_rule( $and_rule['key'], $and_rule['operator'], $and_rule['value'] );
                } );
            }

            $field_group->add_rule_group( $rule_group );
        } );

        $field_group->set_position( 'normal' );
        $field_group->set_menu_order( 2 );

        $field_group->set_hidden_elements( [
            'discussion',
            'comments',
            'format',
            'send-trackbacks',
        ] );

        $strings = [
            'flexible_content' => [
                'label'  => 'Sivupalkki',
                'button' => 'Lisää moduuli',
            ],
        ];

        $flexible_content = new Field\FlexibleContent( $strings['flexible_content']['label'] );
        $flexible_content->set_key( "${key}_flexible_content" );
        $flexible_content->set_name( 'sidebar_flexible_content' );
        $flexible_content->hide_label();
        $flexible_content->set_button_label( $strings['flexible_content']['button'] );

        try {
            $flexible_content->add_layout( new Layouts\LinkList( $key ) );
            $flexible_content->add_layout( new Layouts\PageList( $key, static::PAGE_LIST_FIELDS ) );
            $flexible_content->add_layout( new Layouts\ArticleList( $key, static::ARTICLE_LIST_FIELDS ) );
        }
        catch ( Exception $e ) {
            ( new \Geniem\Theme\Logger() )->error( $e->getMessage(), $e->getTrace() );
        }

        $important_links = $this->register_important_links( $key );
        $field_group->add_fields( [ $important_links, $flexible_content ] );

        $field_group->register();
    }

    /**
     * Register important links group field
     *
     * @param string $key Field group key.
     * @return Field\Group Returns group field.
     */
    private function register_important_links( string $key ) {
        $strings = [
            'title_icon' => [
                'label' => 'Otsikon ikoni',
            ],
            'group' => [
                'label' => 'Tärkeimmät linkit',
            ],
            'link' => [
                'label' => 'Linkit',
            ],
            'links' => [
                'label' => 'Linkit',
            ],
        ];

        // Add title icon field
        $title_icon = new Field\Image( $strings['title_icon']['label'] );
        $title_icon->set_key( "${key}_title_icon" );
        $title_icon->set_name( 'title_icon' );

        // Add link field
        $link = new Field\Link( $strings['link']['label'] );
        $link->set_key( "${key}_link" );
        $link->set_name( 'link' );

        // Add links repeater field
        $links = new Field\Repeater( $strings['links']['label'] );
        $links->set_key( "${key}_links" );
        $links->set_name( 'important_links' );
        $links->set_max( 4 );
        $links->set_button_label( 'Lisää linkki' );
        $links->hide_label();
        $links->add_field( $link );

        // Add important links group field
        $group = new Field\Group( $strings['group']['label'] );
        $group->set_key( "${key}_important_links" );
        $group->set_name( 'important_links_group' );

        // Add fields to the field grouo
        return $group->add_fields( [
            $title_icon,
            $links,
        ] );
    }

}

new PageSidebar();
