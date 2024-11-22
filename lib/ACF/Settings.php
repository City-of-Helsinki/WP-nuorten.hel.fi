<?php
/**
 * Site settings fields for PostType\Settings.
 */

namespace Geniem\Theme\ACF;

use \Geniem\ACF\Field;
use \Geniem\ACF\Group;
use \Geniem\ACF\RuleGroup;
use Geniem\Theme\Integrations\Palvelukartta\ApiClient;
use \Geniem\Theme\PostType;
use Geniem\Theme\Taxonomy;
use Geniem\Theme\Localization;
use WP_Query;


add_action( 'init', function () {
    $group_title = _x( 'Site settings', 'theme ACF', 'nuhe' );

    $key = 'settings';

    // Create a field group
    $field_group = new Group( $group_title );
    $field_group->set_key( 'fg_site_settings' );

    // Define the rules for the group
    $rule_group = new RuleGroup();
    $rule_group->add_rule( 'post_type', '==', PostType\Settings::SLUG );
    $field_group->add_rule_group( $rule_group );

    // Set group position.
    $field_group->set_position( 'normal' );

    // Define which elements are hidden from the admin.
    $field_group->set_hidden_elements(
        [
            'discussion',
            'comments',
            'format',
            'send-trackbacks',
        ]
    );

    /**
     * Add header tab and its fields
     */

    // Strings for field titles and instructions.
    $header_strings = [
        'tab' => 'Ylätunniste',
        'head_scripts' => [
            'title' => 'Lisää head-tagiin tulostettavat scriptit.',
        ],
        'sote_chatbot_scripts' => [
            'title' => 'Lisää head-tagiin tulostettavat Sote chatbot scriptit.',
        ],
        'sote_chatbot_scripts_allowed_url' => [
            'title' => 'Aseta www-osoitteen alku, joilla Sote chatbot näytetään.',
        ],
        'sote_chatbot_scripts_allowed_urls' => [
            'title' => 'Aseta www-osoitteen alku, joilta piilotetaan chatbot.',
        ],
        'sote_chatbot_scripts_disabled' => [
            'title' => 'Listaa poikkeussivut joilla ei näytetä Sote chatbottia.',
        ],
        'summer_voucher_chatbot_scripts' => [
            'title' => 'Lisää head-tagiin tulostettavat Kesäseteli chatbot scriptit.',
        ],
        'summer_voucher_chatbot_scripts_allowed' => [
            'title' => 'Valitse sivut, joille haluat lisätä Kesäseteli chatbotin. Tämä yliajaa mahdollisen Sote chatbotin.',
        ],
        'logo' => [
            'title'        => 'Logo',
            'instructions' => 'Lisää palvelun logo tähän.',
        ],
        'service_name' => [
            'title'        => 'Palvelun nimi',
            'instructions' => 'Lisää palvelun nimi tähän.',
        ],
        'search_results_url' => [
            'title'        => 'Hakutulossivun osoite',
            'instructions' => 'Lisää hakutulossivun osoite tähän.',
        ],
        'hide_search' => [
            'title'        => 'Piilota haku',
            'instructions' => 'Piilota haku ylätunnisteesta',
        ],
        'hide_lang_menu' => [
            'title'        => 'Piilota kielivalikko',
            'instructions' => 'Piilota kielivalikko ylätunnisteesta',
        ],
        'notification_in_use' => [
            'title' => 'Huomiotiedote käytössä',
        ],
        'notification_title' => [
            'title' => 'Huomiotiedotteen otsikko',
        ],
        'notification_content' => [
            'title' => 'Huomiotiedotteen sisältö',
        ],
    ];

    // Header tab.
    $header_tab = new Field\Tab( $header_strings['tab'] );
    $header_tab->set_placement( 'left' );
    $field_group->add_field( $header_tab );

    // Header tag script field.
    $head_scripts = new Field\Textarea( $header_strings['head_scripts']['title'] );
    $head_scripts->set_key( "{$key}_head_scripts" );
    $head_scripts->set_name( 'head_scripts' );
    $header_tab->add_field( $head_scripts );

    // Header Sote chatbot script field.
    $sote_chatbot_scripts = new Field\Textarea( $header_strings['sote_chatbot_scripts']['title'] );
    $sote_chatbot_scripts->set_key( "{$key}_sote_chatbot_scripts" );
    $sote_chatbot_scripts->set_name( 'sote_chatbot_scripts' );
    $header_tab->add_field( $sote_chatbot_scripts );

    // Add url field
    $sote_chatbot_scripts_allowed_url = new Field\URL( $header_strings['sote_chatbot_scripts_allowed_url']['title'] );
    $sote_chatbot_scripts_allowed_url->set_key( "{$key}_sote_chatbot_scripts_allowed_url" );
    $sote_chatbot_scripts_allowed_url->set_name( 'sote_chatbot_scripts_allowed_url' );
    $sote_chatbot_scripts_allowed_url->set_required();

    // Allowed Sote chatbot by url.
    $sote_chatbot_scripts_allowed_urls = new Field\Repeater( $header_strings['sote_chatbot_scripts_allowed_urls']['title'] );
    $sote_chatbot_scripts_allowed_urls->set_key( "{$key}_sote_chatbot_scripts_allowed_urls" );
    $sote_chatbot_scripts_allowed_urls->set_name( 'sote_chatbot_scripts_allowed_urls' );
    $sote_chatbot_scripts_allowed_urls->set_button_label( 'Lisää url' );
    $sote_chatbot_scripts_allowed_urls->hide_label();
    $sote_chatbot_scripts_allowed_urls->add_field( $sote_chatbot_scripts_allowed_url );
    $header_tab->add_field( $sote_chatbot_scripts_allowed_urls );

    // Disabled Sote chatbot pages.
    $sote_chatbot_scripts_disabled = new Field\Select( $header_strings['sote_chatbot_scripts_disabled']['title'] );
    $sote_chatbot_scripts_disabled->set_key( "{$key}_sote_chatbot_scripts_disabled" );
    $sote_chatbot_scripts_disabled->set_name( 'sote_chatbot_scripts_disabled' );
    $sote_chatbot_scripts_disabled->use_ui();
    $sote_chatbot_scripts_disabled->allow_multiple();
    $sote_chatbot_scripts_disabled->set_choices( \apply_filters( 'generate_page_choices', [] ) );
    $header_tab->add_field( $sote_chatbot_scripts_disabled );

    // Header Kesäseteli chatbot script field.
    $summer_voucher_chatbot_scripts = new Field\Textarea( $header_strings['summer_voucher_chatbot_scripts']['title'] );
    $summer_voucher_chatbot_scripts->set_key( "{$key}_summer_voucher_chatbot_scripts" );
    $summer_voucher_chatbot_scripts->set_name( 'summer_voucher_chatbot_scripts' );
    $header_tab->add_field( $summer_voucher_chatbot_scripts );

    // Allowed "Kesäseteli" chatbot pages.
    $summer_voucher_chatbot_scripts_allowed = new Field\Select( $header_strings['summer_voucher_chatbot_scripts_allowed']['title'] );
    $summer_voucher_chatbot_scripts_allowed->set_key( "{$key}_summer_voucher_chatbot_scripts_allowed" );
    $summer_voucher_chatbot_scripts_allowed->set_name( 'summer_voucher_chatbot_scripts_allowed' );
    $summer_voucher_chatbot_scripts_allowed->use_ui();
    $summer_voucher_chatbot_scripts_allowed->allow_multiple();
    $summer_voucher_chatbot_scripts_allowed->set_choices( \apply_filters( 'generate_page_choices', [] ) );
    $header_tab->add_field( $summer_voucher_chatbot_scripts_allowed );

    // Header logo field.
    $logo = new Field\Image( $header_strings['logo']['title'] );
    $logo->set_key( "{$key}_logo" );
    $logo->set_name( 'logo' );
    $logo->set_instructions( $header_strings['logo']['instructions'] );
    $header_tab->add_field( $logo );

    // Header service name field.
    $service_name = new Field\Text( $header_strings['service_name']['title'] );
    $service_name->set_key( "{$key}_service_name" );
    $service_name->set_name( 'service_name' );
    $service_name->set_instructions( $header_strings['service_name']['instructions'] );
    $header_tab->add_field( $service_name );

    // Header search results url field.
    $search_results_url = new Field\Text( $header_strings['search_results_url']['title'] );
    $search_results_url->set_key( "{$key}_search_results_url" );
    $search_results_url->set_name( 'search_results_url' );
    $search_results_url->set_instructions( $header_strings['search_results_url']['instructions'] );
    $header_tab->add_field( $search_results_url );

    // Hide search from header
    $hide_search = new Field\TrueFalse( $header_strings['hide_search']['title'] );
    $hide_search->set_key( "{$key}_hide_search" );
    $hide_search->set_name( 'hide_search' );
    $hide_search->set_default_value( false );
    $hide_search->use_ui();
    $hide_search->set_instructions( $header_strings['hide_search']['instructions'] );
    $header_tab->add_field( $hide_search );

    // Hide lang_menu from header
    $hide_lang_menu = new Field\TrueFalse( $header_strings['hide_lang_menu']['title'] );
    $hide_lang_menu->set_key( "{$key}_hide_lang_menu" );
    $hide_lang_menu->set_name( 'hide_lang_menu' );
    $hide_lang_menu->set_default_value( false );
    $hide_lang_menu->use_ui();
    $hide_lang_menu->set_instructions( $header_strings['hide_lang_menu']['instructions'] );
    $header_tab->add_field( $hide_lang_menu );

    // Notification in use
    $notification_in_use = new Field\TrueFalse( $header_strings['notification_in_use']['title'] );
    $notification_in_use->set_key( "{$key}_notification_in_use" );
    $notification_in_use->set_name( 'notification_in_use' );
    $notification_in_use->use_ui();
    $header_tab->add_field( $notification_in_use );

    // Notification title.
    $notification_title = new Field\Text( $header_strings['notification_title']['title'] );
    $notification_title->set_key( "{$key}_notification_title" );
    $notification_title->set_name( 'notification_title' );
    $header_tab->add_field( $notification_title );

    // Notification content.
    $notification_content = new Field\Wysiwyg( $header_strings['notification_content']['title'] );
    $notification_content->set_key( "{$key}_notification_content" );
    $notification_content->set_name( 'notification_content' );
    $header_tab->add_field( $notification_content );

    /**
     * Add footer tab and its fields
     */

    // Strings for field titles and instructions.
    $footer_strings = [
        'tab'             => 'Alatunniste',
        'group_title'     => 'Alatunniste',
        'askem_script' => [
            'title' => 'Askem-scripti',
        ],
        'askem_scripts_disabled' => [
            'title' => 'Listaa poikkeussivut joilla ei näytetä Askem-palautenappeja.',
        ],
        'links'           => [
            'title'      => 'Linkkipalkki',
            'link_title' => 'Linkit',
        ],
        'sub_bar'         => [
            'group_title'   => 'Alapalkki',
            'sub_bar_text'  => [
                'title' => 'Tekstikenttä',
            ],
            'sub_bar_links' => [
                'title'      => 'Alapalkin linkit',
                'link_title' => 'Linkit',
            ],
        ],
        'footer_bg_color' => [
            'label' => 'Footerin taustaväri',
        ],
    ];

    // Footer tab.
    $footer_tab = new Field\Tab( $footer_strings['tab'] );
    $footer_tab->set_placement( 'left' );
    $field_group->add_field( $footer_tab );

    // Askem script field.
    $askem_script = new Field\Textarea( $footer_strings['askem_script']['title'] );
    $askem_script->set_key( "{$key}_askem_script" );
    $askem_script->set_name( 'askem_script' );
    $footer_tab->add_field( $askem_script );

    // Disabled Askem-feedback pages.
    $askem_scripts_disabled = new Field\Select( $footer_strings['askem_scripts_disabled']['title'] );
    $askem_scripts_disabled->set_key( "{$key}'_askem_scripts_disabled" );
    $askem_scripts_disabled->set_name( 'askem_scripts_disabled' );
    $askem_scripts_disabled->use_ui();
    $askem_scripts_disabled->allow_multiple();
    $askem_scripts_disabled->set_choices( \apply_filters( 'generate_page_choices', [] ) );
    $footer_tab->add_field( $askem_scripts_disabled );

    // Footer link field
    $link_field = new Field\Link( $footer_strings['links']['link_title'] );
    $link_field->set_key( "{$key}_footer_link" );
    $link_field->set_name( 'footer_link' );

    // Footer links repeater field
    $links_field = new Field\Repeater( $footer_strings['links']['title'] );
    $links_field->set_key( "{$key}_footer_links" );
    $links_field->set_name( 'footer_links' );
    $links_field->add_field( $link_field );

    // Footer sub bar group field
    $sub_bar_field = new Field\Group( $footer_strings['sub_bar']['group_title'] );
    $sub_bar_field->set_key( "{$key}_footer_sub_bar" );
    $sub_bar_field->set_name( 'footer_sub_bar' );

    // Footer sub bar text field
    $sub_bar_text_field = new Field\Textarea( $footer_strings['sub_bar']['sub_bar_text']['title'] );
    $sub_bar_text_field->set_key( "{$key}_footer_sub_bar_text" );
    $sub_bar_text_field->set_name( 'footer_sub_bar_text' );

    // Footer sub bar links
    $sub_bar_links_field = new Field\Repeater( $footer_strings['sub_bar']['sub_bar_links']['title'] );
    $sub_bar_links_field->set_key( "{$key}_footer_sub_bar_links" );
    $sub_bar_links_field->set_name( 'footer_sub_bar_links' );
    $sub_bar_links_field->set_min( 1 );
    $sub_bar_links_field->set_max( 4 );
    $sub_bar_links_field->add_field( $link_field );

    $sub_bar_field->add_fields( [
        $sub_bar_text_field,
        $sub_bar_links_field,
    ] );

    // Add footer bg color field
    $footer_bg_color = new Field\Select( $footer_strings['footer_bg_color']['label'] );
    $footer_bg_color->set_key( "{$key}_footer_bg_color" );
    $footer_bg_color->set_name( 'footer_bg_color' );
    $colors = [
        'default' => 'Oletus',
        'pink'    => 'Pinkki',
    ];
    $colors = apply_filters( 'nuhe_acf_color_choices', $colors );
    $footer_bg_color->set_choices( $colors );

    // Footer group field
    $footer_field = new Field\Group( $footer_strings['group_title'] );
    $footer_field->set_key( "{$key}_footer" );
    $footer_field->set_name( 'footer' );

    $footer_fields = [
        $links_field,
        $sub_bar_field,
        $footer_bg_color,
    ];

    $footer_fields = apply_filters( 'nuhe_settings_footer_fields', $footer_fields );
    $footer_field->add_fields( $footer_fields );
    $footer_tab->add_field( $footer_field );

    /**
     * Add general tab and its fields.
     */

    // Strings for field titles and instructions.
    $general_strings = [
        'tab'                      => 'Yleinen',
        'default_page_image'       => [
            'label' => 'Sisältösivun oletuskuva',
        ],
        'default_post_image'       => [
            'label' => 'Artikkelin oletuskuva',
        ],
        'default_initiative_image' => [
            'label' => 'Aloitteen oletuskuva',
        ],
    ];

    // General tab.
    $general_tab = new Field\Tab( $general_strings['tab'] );
    $general_tab->set_placement( 'left' );
    $field_group->add_field( $general_tab );

    // Page default image field
    $default_page_image = new Field\Image( $general_strings['default_page_image']['label'] );
    $default_page_image->set_key( "{$key}_default_page_image" );
    $default_page_image->set_name( 'default_page_image' );

    // Post default image field
    $default_post_image = new Field\Image( $general_strings['default_post_image']['label'] );
    $default_post_image->set_key( "{$key}_default_post_image" );
    $default_post_image->set_name( 'default_post_image' );

    // Initiative default image field
    $default_initiative_image = new Field\Image( $general_strings['default_initiative_image']['label'] );
    $default_initiative_image->set_key( "{$key}_default_initiative_image" );
    $default_initiative_image->set_name( 'default_initiative_image' );

    $general_tab->add_fields( [
        $default_page_image,
        $default_post_image,
        $default_initiative_image,
    ] );

    // Strings for field titles and instructions.
    $color_themes_strings = [
        'tab'               => 'Väriteemat',
        'categories_colors' => [
            'label' => 'Kategorioiden väriteemat',
        ],
        'pages_colors'      => [
            'label' => 'Sivujen väriteemat',
        ],
        'color_theme'       => [
            'label' => 'Väriteema',
        ],
        'taxonomy_field'    => [
            'label' => 'Taksonomia',
        ],
        'page_field'        => [
            'label' => 'Sivu',
        ],
    ];

    // General tab.
    $color_themes_tab = new Field\Tab( $color_themes_strings['tab'] );
    $color_themes_tab->set_placement( 'left' );
    $field_group->add_field( $color_themes_tab );

    $color_theme = new Field\Select( $color_themes_strings['color_theme']['label'] );
    $color_theme->set_key( "{$key}_color_theme" );
    $color_theme->set_name( 'color_theme' );
    $color_theme->set_wrapper_width( 50 );
    $color_theme->set_choices( [
        'color-theme-1' => 'Punainen',
        'color-theme-2' => 'Vihreä',
        'color-theme-3' => 'Keltainen',
        'color-theme-4' => 'Sininen',
    ] );

    $taxonomy_field = new Field\Taxonomy( $color_themes_strings['taxonomy_field']['label'] );
    $taxonomy_field->set_key( "{$key}_taxonomy_field" );
    $taxonomy_field->set_name( 'taxonomy_field' );
    $taxonomy_field->set_field_type( 'select' );
    $taxonomy_field->set_wrapper_width( 50 );
    $taxonomy_field->set_taxonomy( Taxonomy\Category::SLUG );

    $categories_colors_field = new Field\Repeater( $color_themes_strings['categories_colors']['label'] );
    $categories_colors_field->set_key( "{$key}_categories_colors" );
    $categories_colors_field->set_name( 'categories_colors' );
    $categories_colors_field->add_fields( [
        $color_theme,
        $taxonomy_field,
    ] );

    $page_field = new Field\PostObject( $color_themes_strings['page_field']['label'] );
    $page_field->set_key( "{$key}_page_field" );
    $page_field->set_name( 'page_field' );
    $page_field->set_return_format( 'id' );
    $page_field->set_post_types( [ PostType\Page::SLUG ] );

    $pages_colors_field = new Field\Repeater( $color_themes_strings['pages_colors']['label'] );
    $pages_colors_field->set_key( "{$key}_pages_colors" );
    $pages_colors_field->set_name( 'pages_colors' );
    $pages_colors_field->add_fields( [
        $color_theme,
        $page_field,
    ] );

    $color_themes_tab->add_fields( [
        $categories_colors_field,
        $pages_colors_field,
    ] );

    $integrations_strings = [
        'tab'                         => 'Integraatiot',
        'linked_events_page'          => [
            'label' => 'Tapahtumien sivu',
        ],
        'linked_events_external_url'  => [
            'label' => 'tapahtumat.helsinki.fi www-osoite',
        ],
        'linked_events_event_keyword' => [
            'label'        => 'Tapahtumien avainsana',
            'instructions' => 'Avainsanan ID, jolla haetaan tapahtumia',
        ],
        'youth_center_keyword'        => [
            'label' => 'Palvelukartta: nuorisotalo-valinnan hakusanat',
        ],
        'iframe'                      => [
            'label' => 'Pelin upotusosoite',
        ],
    ];

    // General tab.
    $integrations_tab = new Field\Tab( $integrations_strings['tab'] );
    $integrations_tab->set_placement( 'left' );
    $field_group->add_field( $integrations_tab );

    $linked_events_page = new Field\PostObject( $integrations_strings['linked_events_page']['label'] );
    $linked_events_page->set_key( "{$key}_linked_events_page" );
    $linked_events_page->set_name( 'linked_events_page' );
    $linked_events_page->set_post_types( [ \Geniem\Theme\PostType\Page::SLUG ] );
    $linked_events_page->set_return_format( 'id' );
    $field_group->add_field( $linked_events_page );

    $linked_events_external_url = new Field\Link( $integrations_strings['linked_events_external_url']['label'] );
    $linked_events_external_url->set_key( "{$key}_linked_events_external_url" );
    $linked_events_external_url->set_name( 'linked_events_external_url' );
    $field_group->add_field( $linked_events_external_url );

    $linked_events_event_keyword = new Field\Text( $integrations_strings['linked_events_event_keyword']['label'] );
    $linked_events_event_keyword->set_key( "{$key}_linked_events_event_keyword" );
    $linked_events_event_keyword->set_name( 'linked_events_event_keyword' );
    $linked_events_event_keyword->set_default_value( 'yso:p11617' );
    $field_group->add_field( $linked_events_event_keyword );

    $palvelukartta_api = new ApiClient();
    $words             = $palvelukartta_api->get_all_ontology_words();
    $choices           = [];

    if ( $words ) {
        foreach ( $words as $word ) {
            $choices[ $word->id ] = $word->ontologyword_fi;
        }
    }

    $youth_center_keyword = new Field\Select( $integrations_strings['youth_center_keyword']['label'] );
    $youth_center_keyword->set_key( "{$key}_youth_center_keyword" );
    $youth_center_keyword->set_name( 'youth_center_keyword' );
    $youth_center_keyword->allow_multiple();
    $youth_center_keyword->set_choices( $choices );
    $field_group->add_field( $youth_center_keyword );

    $iframe = new Field\Text( $integrations_strings['iframe']['label'] );
    $iframe->set_key( "{$key}_iframe" );
    $iframe->set_name( 'iframe' );
    $field_group->add_field( $iframe );

    $error404_strings = [
        'tab'             => '404',
        '404_title'       => [
            'label' => '404-sivun otsikko',
        ],
        '404_description' => [
            'label' => '404-sivun sisältö',
        ],
        '404_image'       => [
            'label' => '404-sivun yläkuva',
        ],
    ];

    // 404
    $error404_tab = new Field\Tab( $error404_strings['tab'] );
    $error404_tab->set_placement( 'left' );
    $field_group->add_field( $error404_tab );

    $error404_title = new Field\Text( $error404_strings['404_title']['label'] );
    $error404_title->set_key( "{$key}_404_title" );
    $error404_title->set_name( '404_title' );
    $field_group->add_field( $error404_title );

    $error404_description = new Field\Wysiwyg( $error404_strings['404_description']['label'] );
    $error404_description->set_key( "{$key}_404_description" );
    $error404_description->set_name( '404_description' );
    $error404_description->disable_media_upload();
    $field_group->add_field( $error404_description );

    $error404_image = new Field\Image( $error404_strings['404_image']['label'] );
    $error404_image->set_key( "{$key}_404_image" );
    $error404_image->set_name( '404_image' );
    $error404_image->set_return_format( 'id' );
    $field_group->add_field( $error404_image );

    // Search
    $search_strings = [
        'tab'               => 'Haku',
        'search_page_title' => [
            'label' => 'Hakusivun otsikko',
        ],
        'search_post_types' => [
            'label' => 'Valitse sisältötyypit, joihin sivuston päähaku kohdistuu',
        ],
    ];

    $search_tab = new Field\Tab( $search_strings['tab'] );
    $search_tab->set_placement( 'left' );
    $field_group->add_field( $search_tab );

    $search_post_types = new Field\Checkbox( $search_strings['search_post_types']['label'] );
    $search_post_types->set_key( "{$key}_search_post_types" );
    $search_post_types->set_name( 'search_post_types' );
    $choices = apply_filters( 'nuhe_modify_search_post_types', [
        PostType\Post::SLUG             => __( 'Articles', 'nuhe' ),
        PostType\Page::SLUG             => __( 'Pages', 'nuhe' ),
        PostType\YouthCenter::SLUG      => __( 'Youth centers', 'nuhe' ),
        PostType\HighSchool::SLUG       => __( 'High schools', 'nuhe' ),
        PostType\VocationalSchool::SLUG => __( 'Vocational schools', 'nuhe' ),
        'ext-event'                     => __( 'Events', 'nuhe' ),
        'ext-course'                    => __( 'Courses', 'nuhe' ),
    ] );
    $search_post_types->set_choices( $choices );
    $search_post_types->set_return_format( 'array' );

    $search_page_title = new Field\Text( $search_strings['search_page_title']['label'] );
    $search_page_title->set_key( "{$key}_search_page_title" );
    $search_page_title->set_name( 'search_page_title' );
    $search_page_title->set_default_value( 'Hae sivustolta' );

    $field_group->add_fields( [
        $search_page_title,
        $search_post_types,
    ] );

    // Helbit jobs
    $helbit_strings = [
        'tab'              => 'Helbitin työpaikat',
        'background_color' => [
            'label'        => 'Työpaikkailmoituksen taustaväri.',
            'instructions' => 'Joka toinen työpaikkailmoitus tulee tässä määritetyllä taustavärillä,
                               muut ovat taustaväriltään valkoisia.',
        ],
        'employments'      => [
            'label' => 'Työpaikkailmoituksen palvelussuhdeluokitukset',
        ],
        'employment'       => [
            'label'        => 'Palvelussuhdeluokitus',
            'instructions' => 'Lisää luokituksia, joiden perusteella
            Helbit-rajapinnasta tulevia työpaikkoja suodatetaan.',
        ],
    ];

    $helbit_tab = new Field\Tab( $helbit_strings['tab'] );
    $helbit_tab->set_placement( 'left' );
    $field_group->add_field( $helbit_tab );

    // Background color
    $background_color = new Field\Color( $helbit_strings['background_color']['label'] );
    $background_color->set_key( "{$key}_background_color" );
    $background_color->set_name( 'background_color' );
    $background_color->set_instructions( $helbit_strings['background_color']['instructions'] );

    $field_group->add_field( $background_color );

    // Employment
    $employment = new Field\Text( $helbit_strings['employment']['label'] );
    $employment->set_key( "{$key}_employment" );
    $employment->set_name( 'employment' );
    $employment->set_instructions( $helbit_strings['employment']['instructions'] );

    // Employments
    $employments = new Field\Repeater( $helbit_strings['employments']['label'] );
    $employments->set_key( "{$key}_employments" );
    $employments->set_name( 'employments' );
    $employments->add_field( $employment );

    $field_group->add_field( $employments );

    $field_group = apply_filters( 'nuhe_modify_settings_fields', $field_group, $key );

    // Register the field group
    $field_group->register();
} );

/**
 * Page options for disabling chatbot.
 *
 * @param array $options
 *
 * @return array
 */
\add_filter( 'generate_page_choices', function( $options = [] ) {
        $args = [
            'post_type'      => 'page',
            'post_status'    => 'publish',
            'posts_per_page' => '1000',
            'order'          => 'ASC',
            'orderby'        => 'title',
            'lang'           => Localization::get_current_language(),
        ];

        $query = new WP_Query( $args );
        $pages = $query->posts ?? [];

        if ( empty( $pages ) ) {
            return $options;
        }

        $new_options = [];

        foreach ( $pages as $page ) {
            $new_options[ $page->ID ] = $page->post_title;
        }

        return $new_options + $options;
    }
);
