<?php
/**
 * Accordion partial
 */

if ( empty( $fields['data'] ) ) {
    return;
}

?>

<div class="nuhe-block nuhe-accordion">
    <div class="accordion">
        <?php
        $count = 0;
        foreach ( $fields['data'] as $row ) {
            ?>
            <div class="accordion-item">
                <h3 class="accordion-item__title">
                    <button type="button" id="accordion-item-button-<?php echo esc_attr( $count ); ?>" class="accordion-item__button js-toggle" aria-expanded="false" aria-controls="accordion-content-<?php echo esc_attr( $count ); ?>">
                        <span class="accordion-item__button-text">
                            <?php echo esc_html( $row['row_title'] ); ?>
                        </span>
                        <span class="accordion-item__button-icon" aria-hidden="true">
                            <?php
                            get_template_part( 'partials/icon', '', [
                                'icon' => 'angle-down',
                            ] );
                            ?>
                        </span>
                    </button>
                </h3>
                <div id="accordion-content-<?php echo esc_attr( $count ); ?>" class="accordion__content">
                <?php echo wp_kses_post( $row['row_content'] ); ?>
                </div>
            </div>
            <?php
            $count++;
        }
        ?>
    </div>
</div>
