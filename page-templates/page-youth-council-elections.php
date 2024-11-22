<?php
/**
 * Template Name: Nuorisoneuvoston vaalit
 */

use Geniem\Theme\Model\PageYouthCouncilElections;

$model                = new PageYouthCouncilElections();
$results              = $model->get_posts();
$search_result_single = _x( 'search result', 'search result', 'nuhe' );
$search_result_plurar = _x( 'search results', 'search results', 'nuhe' );
$result_count         = count( $results );
$search_term          = $model->get_search_term( true );
$breadcrumbs          = $model->get_breadcrumbs();

get_header();
?>
    <main id="main-content" class="main-content youth-council-elections">
        <header class="youth-council-elections__header">
            <div class="container">
                <?php
                if ( ! empty( $breadcrumbs ) ) {
                    \get_template_part( 'partials/breadcrumbs', '', [
                        'breadcrumbs' => $breadcrumbs,
                    ] );
                }
                ?>
                <h1 class="youth-council-elections__title">
                    <?php the_title(); ?>
                </h1>

                <form method="get" action="<?php the_permalink(); ?>" class="youth-council-elections__form">
                    <div class="form-row">
                        <div class="form-group form-group--text-input">
                            <?php
                            get_template_part(
                                'partials/textfield',
                                '',
                                [
                                    'label' => esc_html__( 'Find a candidate based on their name or postcode', 'nuhe' ),
                                    'name'  => 'search-term',
                                    'value' => $search_term,
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
                                'attributes' => [ 'id' => 'youth-council-elections-submit' ],
                                'type'       => 'submit',
                            ] );
                            ?>
                        </div>
                    </div>
                </form>

                <?php if ( ! empty( $search_term ) ) : ?>
                    <div class="youth-council-elections__result-meta">
                        <div class="youth-council-elections__result-count">
                            <?php
                            echo esc_html(
                                sprintf(
                                    // translators:
                                    __( '%1$s %2$s', 'nuhe' ),
                                    $result_count,
                                    _n( $search_result_single, $search_result_plurar, $result_count, 'nuhe' ),  // phpcs:ignore
                                    number_format_i18n( $result_count )
                                )
                            );
                            ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </header>

        <div class="youth-council-elections__results container">
            <?php
            if ( empty( $results ) ) {
                get_template_part( 'partials/views/youth-council-elections/youth-council-elections-no-results' );
            }
            else {
                get_template_part( 'partials/views/youth-council-elections/youth-council-elections-results', '', $results );
            }
            ?>
        </div>
    </main>
<?php
get_footer();
