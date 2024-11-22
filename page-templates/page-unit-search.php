<?php
/**
 * Template Name: Yksikköhaku
 */

use Geniem\Theme\Model\UnitSearch;

$model   = new UnitSearch();
$results = $model->get_posts();

$breadcrumbs = $model->get_breadcrumbs();

get_header();
?>
    <main id="main-content" class="main-content unit-search">
        <header class="unit-search__header">
            <div class="container">
                <?php
                if ( ! empty( $breadcrumbs ) ) {
                    \get_template_part( 'partials/breadcrumbs', '', [
                        'breadcrumbs' => $breadcrumbs,
                    ] );
                }
                ?>
                <h1 class="unit-search__title">
                    <?php the_title(); ?>
                </h1>

                <form method="get" action="<?php the_permalink(); ?>" class="unit-search__form">
                    <div class="form-row">
                        <div class="form-group">
                            <?php
                            get_template_part(
                                'partials/textfield',
                                '',
                                [
                                    'label' => esc_html__( 'Search term', 'nuhe' ),
                                    'name'  => 'search-term',
                                ]
                            );
                            ?>
                        </div>

                        <div class="form-group">
                            <?php
                            get_template_part( 'partials/button', '', [
                                'classes'    => [ 'primary' ],
                                'icon-end'   => 'search',
                                'label'      => __( 'Search', 'nuhe' ),
                                'attributes' => [ 'id' => 'unit-search-submit' ],
                                'type'       => 'submit',
                            ] );
                            ?>
                        </div>
                    </div>

                    <div class="unit-search-filters-dropdown dropdown" data-dropdown>
                        <?php
                        get_template_part( 'partials/button', '', [
                            'label'      => __( 'Show keywords', 'nuhe' ),
                            'icon-start' => 'angle-down',
                            'classes'    => [ 'theme-black', 'border-black' ],
                            'attributes' => [
                                'aria-expanded'         => 'false',
                                'aria-label'            => __( 'Show keywords', 'nuhe' ),
                                'data-dropdown-trigger' => true,
                            ],
                        ] );
                        ?>
                        <ul class="unit-search__filters"
                            aria-label="<?php esc_attr_e( 'Filter results', 'nuhe' ); ?>">
                            <?php
                            $terms = $model->get_search_filters();

                            foreach ( $terms as $term ) :
                                ?>
                                <li>
                                    <?php
                                    get_template_part( 'partials/button-link', '', [
                                        'classes'    => [ 'small', $term['is_active'] ? 'secondary' : '' ],
                                        'attributes' => $term['is_active'] ? [ 'aria-current' => 'page' ] : [],
                                        'label'      => $term['label'],
                                        'url'        => esc_url( $term['url'] ),
                                    ] );
                                    ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </form>

                <div class="unit-search__result-meta">
                    <div class="unit-search__result-count">
                        <?php
                        echo esc_html(
                            sprintf(
                            // translators: search results.
                                _n( '%s place', '%s places', count( $results ), 'nuhe' ),
                                number_format_i18n( count( $results ) )
                            )
                        );
                        ?>
                    </div>

                    <div class="unit-search__views">
                        <?php
                        foreach ( $model->view_links() as $link ) {
                            $link['classes'] = [
                                'outlined',
                                'small',
                            ];

                            if ( ! $link['is_active'] ) {
                                $link['classes'][] = 'border-black';
                            }

                            get_template_part(
                                'partials/button-link',
                                '',
                                $link
                            );
                        }
                        ?>
                    </div>
                </div>
            </div>
        </header>

        <div class="unit-search__results container">
            <?php
            if ( empty( $results ) ) {
                get_template_part( 'partials/views/unit-search/unit-search-no-results' );
            }
            elseif ( $model->is_map_view() ) {
                get_template_part( 'partials/views/unit-search/unit-search-results-map' );
            }
            else {
                get_template_part( 'partials/views/unit-search/unit-search-results', '', $results );
            }
            ?>
        </div>
    </main>
<?php
get_footer();
