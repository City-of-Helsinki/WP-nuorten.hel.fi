/**
 * PageEventsCollection scripts
 */

// Use jQuery as $ within this file scope.
const $ = jQuery;
/**
 * Export the class reference.
 */
export default class PageEventsCollection {
    /**
     * Cache dom elements for use in the class's methods
     *
     * @return {void}
     */
    cache() {
        this.$showMoreBtn = $( '.hds-button--show-more-events' );
    }

    /**
     * All common events
     *
     * @return {void}
     */
    events() {
        if ( this.$showMoreBtn.length === 0 ) {
            return;
        }

        this.$showMoreBtn.on( 'click', ( e ) => {
            const $btn = $( e.currentTarget );
            const $events = $btn.closest( '.event-grid' ).find( '.event-grid-item' );
            $events.removeClass( 'is-hidden' );
            $btn.hide();
        } );
    }
    /**
     * Run when the document is ready.
     *
     * @return {void}
     */
    docReady() {
        this.cache();
        this.events();
    }
}
