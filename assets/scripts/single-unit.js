/**
 * SingleUnit JS controller.
 */

// Use jQuery as $ within this file scope.
const $ = jQuery; // eslint-disable-line no-unused-vars
import _ from 'lodash';
import L from 'leaflet';

/**
 * Export the class reference.
 */
export default class SingleUnit {

    /**
     * Cache dom elements for use in the class's methods
     */

    cache() {
        this.mapDiv = 'map';
        this.map = $( `#${ this.mapDiv }` );
        this.unitData = themeData.unit_data; // eslint-disable-line no-undef
        this.mapAssetsSource = themeData.stylesImagePath; // eslint-disable-line no-undef
        this.showMoreEventsBtn = $( '#js-show-more-events' );
        this.eventGridItems = $( '.event-grid__grid' );
    }

    /**
     * Run when the document is ready.
     *
     * @return {void}
     */
    docReady() {
        this.cache();
        this.getCoordinates();
        this.getUnitName();
        this.generateMap();
        this.showMoreEventsClick();
    }

    /**
     * Get coordinates of the unit.
     *
     * @return {void}
     */
    getCoordinates() {
        const latitude = this.unitData.latitude;
        const longitude = this.unitData.longitude;

        if ( _.isUndefined( latitude ) || _.isUndefined( longitude ) ) {
            this.coordinates = false;
            return;
        }

        this.coordinates = [
            latitude,
            longitude,
        ];
    }

    /**
     * Get name of the unit.
     *
     * @return {void}
     */
    getUnitName() {
        const name = this.unitData.name;

        this.UnitName = false;
        if ( ! _.isUndefined( name ) ) {
            this.UnitName = name;
        }

    }

    /**
     * Generate map.
     *
     * @return {void}
     */
    generateMap() {
        if ( ! this.coordinates ) {
            return;
        }

        const map = L.map( 'map' ).setView( this.coordinates, 13 );

        L.Icon.Default.imagePath = this.mapAssetsSource;

        L.tileLayer( 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        } ).addTo( map );

        const marker = L.marker( this.coordinates );

        marker.addTo( map );

        if ( this.UnitName ) {
            marker.bindPopup( this.UnitName ).openPopup();
        }
    }

    /**
     * Show more events click.
     *
     * @return {void}
     */
    showMoreEventsClick() {
        this.showMoreEventsBtn.on( 'click', ( e ) => {
            this.showMoreEventsBtn.hide();
            this.eventGridItems.removeClass( 'is-hidden' );
        } );
    }
}
