/**
 * UnitSearch JS controller.
 */

// Use jQuery as $ within this file scope.
const $ = jQuery; // eslint-disable-line no-unused-vars
import L from 'leaflet';

/**
 * Export the class reference.
 */
export default class UnitSearch {

    /**
     * Constructor
     */
    constructor() {
        this.searchTerms = [];
    }

    /**
     * Cache elements
     *
     * @return {void}
     */
    cache() {
        this.mapAssetsSource = themeData.stylesImagePath; // eslint-disable-line no-undef
        this.mapContainer = 'unit-search-results-map';
    }

    /**
     * Run when the document is ready.
     *
     * @return {void}
     */
    docReady() {
        this.cache();

        if ( this.shouldDisplayMap() ) {
            this.generateMap();
        }
    }

    /**
     * Should display map
     *
     * @return {boolean} True if map should be displayed
     */
    shouldDisplayMap() {
        if ( typeof window.themeData.units === 'undefined' ) {
            return false;
        }

        if ( window.themeData.units.length === 0 ) {
            return false;
        }

        return $( '#' + this.mapContainer ).length > 0;
    }

    /**
     * Generate map.
     *
     * @return {void}
     */
    generateMap() {
        const units = window.themeData.units;
        const map = L.map( this.mapContainer );

        L.Icon.Default.imagePath = this.mapAssetsSource;

        L.tileLayer( 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        } ).addTo( map );

        this.addMarkers( map, units );
    }

    /**
     * Add markers to map
     *
     * @param {L.map} map Leaflet map
     * @param {Array} units Array of youth centers
     *
     * @return {void}
     */
    addMarkers( map, units ) {
        const markers = [];

        units.forEach( ( item ) => {
            const marker = L.marker( [
                item.latitude,
                item.longitude,
            ] );

            marker.addTo( map );
            marker.bindPopup( `<a href="${ item.url }"> <h3>${ item.title }</h3> </a>` );
            markers.push( marker );
        } );

        const boundOptions = {
            padding: [ 20, 20 ],
        };

        const bounds = L.latLngBounds( markers.map( ( item ) => item.getLatLng() ) );

        map.fitBounds( bounds, boundOptions );
    }
}
