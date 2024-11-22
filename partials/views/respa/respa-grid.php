<?php
/**
 * Respa grid
 */

$anchor_id = ! empty( $args['anchor_id'] ) ? ' id="' . $args['anchor_id'] . '"' : '';

?>

<div class="nuhe-layout nuhe-layout--respa nuhe-layout--main-content nuhe-card-list" <?php echo wp_kses_post( $anchor_id ); ?>>
    <div class="container">
        <h2 class="nuhe-card-list__title">
            <?php echo esc_html( $args['title'] ); ?>
        </h2>
        <ul class="nuhe-card-list__items grid-columns grid-columns--2">
            <?php
            foreach ( $args['respa_spaces'] as $space ) {
                get_template_part( 'partials/views/respa/respa-grid-item', '', $space );
            }
            ?>
        </ul>
    </div>
</div>
