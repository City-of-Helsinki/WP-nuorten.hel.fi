<?php
/**
 * Search template
 */

use Geniem\Theme\Settings;

get_header();

/**
 * Search model
 *
 * @var $model Search
 */
$model = ModelController()->get_class( 'Search' );
?>
    <main class="main-content" id="main-content">
        <div class="container">
            <h1 class="main-content__title">
                <?php echo esc_html( Settings::get_setting( 'search_page_title' ) ); ?>
            </h1>

            <form role="search" action="<?php echo esc_url( $model->get_search_action() ); ?>" method="GET" class="search__form">
                <div class="search__form-row">
                    <div class="search__form-group search__form-group--is-full-width">
                        <?php
                        get_template_part(
                            'partials/textfield',
                            '',
                            [
                                'label' => esc_html__( 'Search term', 'nuhe' ),
                                'name'  => 's',
                                'id'    => 'search-term',
                                'value' => get_search_query( true ),
                            ]
                        );
                        get_template_part(
                            'partials/textfield',
                            '',
                            [
                                'name'    => 'type',
                                'value'   => $model->get_active_search_type(),
                                'type'    => 'hidden',
                                'classes' => [ 'is-hidden' ],
                            ]
                        );
                        ?>
                        <?php
                        get_template_part( 'partials/button', '', [
                            'classes'  => [ 'theme-black-bg' ],
                            'icon-end' => 'search',
                            'label'    => __( 'Search', 'nuhe' ),
                            'type'     => 'submit',
                        ] );
                        ?>
                    </div>
                </div>
            </form>
            <?php
            get_template_part( 'partials/links', '', [
                'list_classes' => [ 'button-links', 'search__filters' ],
                'links'        => $model->get_search_types(),
                'type'         => 'button',
                'classes'      => [ 'outlined', 'border-black' ],
                'attributes'   => [
                    'aria-label' => __( 'Filter results', 'nuhe' ),
                ],
            ] );
            ?>
        </div>

        <?php get_template_part( 'partials/views/search/results', '', $model ); ?>

    </main>
<?php

get_footer();
