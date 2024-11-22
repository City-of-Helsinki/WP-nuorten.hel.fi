<?php
/**
 * Accordion partial layout
 */

$args = \wp_parse_args( $args, [
    'data' => [],
] );

$close = __( 'Close', 'nuhe' );
$args['anchor_id'] = \sanitize_title( strtolower( ! empty( $args['anchor_link_text'] ) ? $args['anchor_link_text'] : ( !empty ( $args['title'] ) ? $args['title'] : '' ) ) );
$anchor_id         = ! empty( $args['anchor_id'] ) ? ' id="' . $args['anchor_id'] . '"' : '';
?>

<div class="nuhe-layout nuhe-layout--main-content hds-accordion"<?php echo \wp_kses_post( $anchor_id ); ?>>
    <div class="accordion container">

        <?php if ( ! empty( $args['title'] ) ) : ?>
            <h2 class="nuhe-accordion__title">
                <?php echo \esc_html( $args['title'] ); ?>
            </h2>
        <?php endif; ?>

        <?php if ( ! empty( $args['description'] ) ) : ?>
            <div class="nuhe-accordion__description">
                <?php echo \wp_kses_post( $args['description'] ); ?>
            </div>
        <?php endif; ?>

        <?php
        $count = 0;
        foreach ( $args['rows'] as $row ) {
            $id = \wp_unique_id();
            ?>
            <div class="accordion-item border">
                <h3 class="accordion-item__title accordion-item__heading-container">
                    <button type="button" id="accordion-item-button-<?php echo \esc_attr( $id ); ?>" class="accordion-item__button js-toggle accordion-item__header" aria-expanded="false" aria-controls="accordion-content-<?php echo \esc_attr( $id ); ?>">
                        <span class="accordion-item__button-text">
                            <?php echo \esc_html( $row['row_title'] ); ?>
                        </span>
                        <span class="accordion-item__button-icon" aria-hidden="true">
                            <?php
                            \get_template_part( 'partials/icon', '', [
                                'icon' => 'angle-down',
                            ] );
                            ?>
                        </span>
                    </button>
                </h3>
                <div id="accordion-content-<?php echo \esc_attr( $id ); ?>" class="accordion-item__content accordion-item__content-with-close-button">
                    <?php echo \wp_kses_post( $row['row_content'] ); ?>
                    <div class="accordion-item__close js-toggle" aria-controls="accordion-content-<?php echo \esc_attr( $id ); ?>">
                        <span class="accordion-item__close-button">
                                <?php echo \esc_html( $close ); ?>
                        </span>
                        <span class="accordion-item__close-button-icon" aria-hidden="true">
                            <?php
                                \get_template_part( 'partials/icon', '', [
                                    'icon' => 'angle-up',
                                    'size' => 's',
                                ] );
                            ?>
                        </span>
                    </div>
                </div>
            </div>
            <?php
            $count++;
        }
        ?>
    </div>
</div>
