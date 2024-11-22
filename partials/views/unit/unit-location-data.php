<?php
/**
 * Unit intro location data
 */

$unit_data = $args;

if ( empty( $unit_data ) ) {
    return;
}

?>

<div class="unit-location-data">
    <div class="unit-location-data__header has-bg-color has-bg-color--grey">
        <div class="detail-block">
            <div class="detail-block__title detail-block__title--flex icon-start">
                <?php
                get_template_part( 'partials/icon', '', [
                    'icon' => 'location',
                    'size' => 's',
                ] );
                ?>
                <span>
                    <?php esc_html_e( 'Location', 'nuhe' ); ?>
                </span>
            </div>
        </div>
        <div class="detail-block">
            <div class="detail-block__content detail-block__content--flex detail-block__content--font-small icon-end">
                <?php if ( ! empty( $unit_data['google_maps_location'] ) ) : ?>
                    <a class="open-unit-location" href="<?php echo esc_url( $unit_data['google_maps_location'] ); ?>" target="_blank" rel="noopener noreferrer">
                        <?php esc_html_e( 'Open map in new tab', 'nuhe' ); ?>
                        <?php
                        get_template_part( 'partials/icon', '', [
                            'icon' => 'link-external',
                            'size' => 'xs',
                        ] );
                        ?>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div id="map" class="unit-location-data__map">
    </div>
    <div class="unit-location-data__details has-bg-color has-bg-color--grey">
        <div class="unit-location-data__details__block">
            <div class="detail-block">
                <div class="detail-block__title">
                    <?php esc_html_e( 'Visiting address', 'nuhe' ); ?>
                </div>
                <div class="detail-block__content">
                    <?php
                    echo wp_kses_post( sprintf(
                        '<div>%s</div> <div>%s</div>',
                        $unit_data['street_address'],
                        $unit_data['zip_code'] . ' ' . $unit_data['city']
                    ) );
                    ?>
                </div>
            </div>
            <div class="detail-block">
                <div class="detail-block__title">
                <?php esc_html_e( 'Postal address', 'nuhe' ); ?>
                </div>
                <div class="detail-block__content">
                    <div>
                        <?php echo wp_kses_post( $unit_data['address_postal_full'] ); ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="unit-location-data__details__block">
            <div class="detail-block">
                <div class="detail-block__title">
                <?php esc_html_e( 'Transport connections', 'nuhe' ); ?>
                </div>
                <div class="detail-block__content">
                </div>
            </div>
            <div class="detail-block">
                <div class="detail-block__content">
                    <?php
                    get_template_part( 'partials/links', '', [
                        'list_classes' => [ 'links' ],
                        'links'        => $unit_data['links'],
                        'icon-start'   => 'link-external',
                    ] );
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
