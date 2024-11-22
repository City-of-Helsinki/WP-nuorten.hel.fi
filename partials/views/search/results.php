<?php
/**
 * Search results
 */

/**
 * View model
 *
 * @var \Geniem\Theme\Model\Search $args
 */
$model = $args;
?>
<div class="search__results">
    <div class="container">
        <?php
        $results = $model->get_results();

        get_template_part(
            'partials/views/search/results-grid',
            '',
            [
                'results'   => $results,
                'item_view' => 'partials/views/search/search-item',
            ]
        );

        $results = $model->get_external_results( [ 'ext-event' ] );

        get_template_part(
            'partials/views/search/results-grid',
            '',
            [
                'results'   => $results,
                'item_view' => 'partials/views/search/search-item-external',
            ]
        );
        ?>

        <?php if ( ! $model->has_search_results() ) : ?>
            <div class="search__group">
                <h2 tabindex="0" class="search__group-title" aria-label="<?php esc_attr_e( 'No search results', 'nuhe' ); ?>">
                    <?php echo esc_html( __( 'No search results', 'nuhe' ) ); ?>
                </h2>
            </div>
        <?php endif; ?>
    </div>
</div>
