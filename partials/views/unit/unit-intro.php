<?php
/**
 * Unit intro
 */

/**
 * Intro is passed as args.
 *
 * @var array $args
 */
$intro        = $args['intro'];
$links        = $args['link_list'];
$partial_path = 'partials/views/unit/unit';
?>

<div class="container unit-section" id=<?php echo esc_attr( $intro['anchor']['url'] ); ?>>
    <div class="grid-columns grid-columns--2">
        <div class="grid-columns__column">
            <h2 class="unit-section__title unit-section__title--intro">
                <?php echo esc_html( $intro['title'] ); ?>
            </h2>
        </div>
    </div>
    <div class="grid-columns grid-columns--2">
        <div class="grid-columns__column">
            <?php if ( ! empty( $intro['intro_lead_text'] ) ) : ?>
                <div class="unit-section__lead-text">
                    <?php echo wp_kses_post( $intro['intro_lead_text'] ); ?>
                </div>
            <?php endif; ?>

            <?php if ( ! empty( $intro['intro_text'] ) ) : ?>
                <div class="unit-section__text">
                    <?php echo wp_kses_post( $intro['intro_text'] ); ?>
                </div>
            <?php endif; ?>
        </div>
        <div class="grid-columns__column grid-columns__column--location-data-wrapper">
            <?php
            get_template_part( $partial_path, 'location-data', $intro['unit'] );
            ?>

            <?php if ( ! empty( $links['links'] ) ) : ?>
                <?php get_template_part( 'partials/layouts/link_list', '', $links ); ?>
            <?php endif; ?>
        </div>
    </div>
</div>
