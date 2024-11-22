<?php
/**
 * ACF fields for template PageModules
 *
 * @package Geniem\Theme\ACF
 */

namespace Geniem\Theme\ACF;

use Geniem\ACF\Exception;
use \Geniem\ACF\Field;
use Geniem\ACF\Field\Flexible\Layout;
use \Geniem\ACF\Group;
use \Geniem\ACF\RuleGroup;
use \Geniem\Theme\PostType;
use \Geniem\Theme\ACF\Layouts;
use Geniem\Theme\Model\PageYouthCouncilElections;
use Geniem\Theme\Model\PageEventsCollection;
use Geniem\Theme\Model\PageHelbitJobs;
use Geniem\Theme\Model\PageSitemap;
use Geniem\Theme\Model\PageIframe;

/**
 * Class PageModules
 *
 * @package Geniem\Theme\ACF
 */
class PageModules {

    /**
     * PageList fields.
     *
     * @var array
     */
    const PAGE_LIST_FIELDS = [
        'bg_color',
        'title',
        'description',
        'pages',
        'page',
        'short_desc',
        'link',
        'anchor_link_text',
    ];

    /**
     * ArticleList fields.
     *
     * @var array
     */
    const ARTICLE_LIST_FIELDS = [
        'bg_color',
        'title',
        'category',
        'tag',
        'community_center',
        'amount',
        'articles',
        'article',
        'link',
        'anchor_link_text',
    ];

    /**
     * PageModules constructor.
     */
    public function __construct() {
        add_action( 'init', [ $this, 'register_fields' ] );
    }

    /**
     * Register fields
     */
    public function register_fields() {
        $group_title = _x( 'Vapaat nostot', 'theme ACF', 'nuhe' );
        $key         = 'fc';

        $field_group = new Group( $group_title );
        $field_group->set_key( 'fg_flexible_content' );

        // Define 'or' rules for the field group
        $rules = [
            [
                'key'      => 'post_type',
                'value'    => PostType\Page::SLUG,
                'operator' => '==',
                'and'      => [
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
            [
                'key'      => 'post_type',
                'value'    => PostType\YouthCenter::SLUG,
                'operator' => '==',
            ],
            [
                'key'      => 'post_type',
                'value'    => PostType\HighSchool::SLUG,
                'operator' => '==',
            ],
            [
                'key'      => 'post_type',
                'value'    => PostType\VocationalSchool::SLUG,
                'operator' => '==',
            ],
        ];

        $rules = apply_filters( 'nuhe_modify_page_modules_rules', $rules );

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
        $field_group->set_menu_order( 3 );

        $field_group->set_hidden_elements( [
            'discussion',
            'comments',
            'format',
            'send-trackbacks',
        ] );

        $strings = [
            'flexible_content' => [
                'label'  => 'Vapaat nostot',
                'button' => 'Lisää moduuli',
            ],
        ];

        $flexible_content = new Field\FlexibleContent( $strings['flexible_content']['label'] );
        $flexible_content->set_key( "${key}_flexible_content" );
        $flexible_content->set_name( 'flexible_content' );
        $flexible_content->hide_label();
        $flexible_content->set_button_label( $strings['flexible_content']['button'] );

        try {
            $flexible_content->add_layout( new Layouts\PageList( $key, static::PAGE_LIST_FIELDS ) );
            $flexible_content->add_layout( new Layouts\ArticleList( $key, static::ARTICLE_LIST_FIELDS ) );
            $flexible_content->add_layout( new Layouts\NewsList( $key ) );
            $flexible_content->add_layout( new Layouts\Events( $key ) );
            $flexible_content->add_layout( new Layouts\PeopleList( $key ) );
            $flexible_content->add_layout( new Layouts\TagCloud( $key ) );
            $flexible_content->add_layout( new Layouts\AttentionBulletin( $key ) );
            $flexible_content->add_layout( new Layouts\ImageText( $key ) );
            $flexible_content->add_layout( new Layouts\TextBanner( $key ) );
            $flexible_content->add_layout( new Layouts\SubPageList( $key ) );
            $flexible_content->add_layout( new Layouts\SomeEmbed( $key ) );
            $flexible_content->add_layout( new Layouts\MultiColumns( $key ) );
            $flexible_content->add_layout( new Layouts\TextBoxImage( $key ) );
            $flexible_content->add_layout( new Layouts\InitiativeList( $key ) );
            $flexible_content->add_layout( new Layouts\Accordion( $key ) );

            $flexible_content = apply_filters(
                'nuhe_acf_flexible_content_' . $key,
                $flexible_content,
                $key
            );
        }
        catch ( Exception $e ) {
            ( new \Geniem\Theme\Logger() )->error( $e->getMessage(), $e->getTrace() );
        }

        $field_group->add_field( $flexible_content );

        $field_group->register();
    }

}

new PageModules();
