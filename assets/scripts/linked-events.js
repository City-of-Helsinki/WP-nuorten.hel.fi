/**
 * JS for LinkedEvents
 */

// Use jQuery as $ within this file scope.
const $ = jQuery;
import _ from 'lodash';

/**
 * Class LinkedEvents
 */
export default class LinkedEvents {

    /**
     * Cache dom elements for use in the class's methods
     *
     * @return {void}
     */
    cache() {
        this.$loadMoreBtn = $( '.js-load-more-linked-events' );
        this.restApiUrl = themeData.restApiUrl; // eslint-disable-line
    }

    /**
     * All common events
     *
     * @return {void}
     */
    events() {

        if ( ! this.$loadMoreBtn.length || _.isUndefined( this.restApiUrl ) ) {
            return;
        }

        this.$loadMoreBtn.on( 'click', ( e ) => {
            const $btn = $( e.currentTarget );
            const loadMoreParams = $btn.data( 'load-more-params' );

            if ( _.isEmpty( loadMoreParams ) || _.isUndefined( loadMoreParams ) ) {
                return;
            }

            this.loadMoreEvents( loadMoreParams, $btn );
        } );
    }

    /**
     * Load more events.
     *
     * @param {string} loadMoreParams Load more url.
     * @param {Object} $btn        Btn.
     * @return {void}
     */
    loadMoreEvents( loadMoreParams, $btn ) {
        const url = this.restApiUrl;
        $.ajax( {
            type: 'POST',
            url,
            data: {
                loadMoreParams,
            },
            complete: ( response ) => {
                const { load_more_params, events, count } = response.responseJSON; // eslint-disable-line
                if ( _.isNull( load_more_params ) || _.isEmpty( events ) ) {
                    $btn.remove();
                    return;
                }

                const $btnParent = $btn.parent();

                let htmlElems = '';
                events.forEach( ( event ) => {
                    const htmlElem = this.generateEventItem( event );
                    htmlElems += htmlElem;
                } );

                $( htmlElems ).insertBefore( $btnParent );

                $btn.data( 'load-more-params', load_more_params );
                $btn.find( '.events-count' ).text( `(${ count })` );

            },
        } );
    }

    /**
     * Generate event html.
     *
     * @param {Object} event Event object.
     * @return {string} Return generated html.
     */
    generateEventItem( event ) {
        const image = event.image;
        const keywords = event.keywords;
        const tickets = event.tickets;
        const classes = ! _.isEmpty( image ) ? 'wp-linked-events-block__event--has-image' : '';
        let imageElem = '';
        let keywordsElem = '';
        let ticketsElem = '';

        if ( ! _.isEmpty( keywords ) ) {
            const floatClass = ! _.isEmpty( image ) ? ' wp-linked-events-block__keywords--floated' : '';

            const keywordsArr = keywords.map( ( keyword ) => {
                return (
                    `<li>
                        <div class="wp-linked-events-block__keyword">
                            <span class="wp-linked-events-block__keyword-label">
                                ${ keyword }
                            </span>
                        </div>
                     </li>`
                );
            } ).join( '' );

            keywordsElem = (
                `<ul class="wp-linked-events-block__keywords${ floatClass }">
                    ${ keywordsArr }
                </ul>`
            );
        }

        if ( ! _.isEmpty( image ) ) {
            imageElem = (
                `<div class="wp-linked-events-block__image-wrapper">
                    <div class="wp-linked-events-block__image-container">
                        <a href="${ event.permalink }" aria-hidden="true" tabindex="-1">
                            <img src="${ image.url }"
                                 class="wp-linked-events-block__image"
                                 alt="${ image.alt }"
                                 loading="lazy">
                        </a>
                    </div>
                    ${ keywordsElem }
                </div>`
            );
        }

        const keywordsOutsideImage = ! _.isEmpty( keywords ) && _.isEmpty( image ) ? keywordsElem : '';

        if ( ! _.isEmpty( tickets ) ) {
            const ticketsArr = tickets.map( ( ticket ) => {
                return (
                    `<div class="wp-linked-events-block__price">
                        ${ ticket.is_free ? ticket.is_free_text : ticket.price }
                    </div>`
                );
            } ).join( '' );

            ticketsElem = (
                `<div class="wp-linked-events-block__tickets">
                    ${ ticketsArr }
                </div>`
            );
        }

        return (
            `<div class="wp-linked-events-block__event ${ classes }">
                ${ imageElem }
                <div class="wp-linked-events-block__fields">
                    ${ keywordsOutsideImage }
    
                    <div class="wp-linked-event-block__date">
                        ${ event.formatted_time_string }
                    </div>
    
                    <h3 class="wp-linked-events-block__title">
                        <a href="${ event.permalink }"
                           class="wp-linked-events-lock__link">
                            ${ event.name }
                        </a>
                    </h3>
    
                    <div class="wp-linked-event-block__location">
                        ${ event.location_string }
                    </div>

                    ${ ticketsElem }
                </div>
            </div>`
        );
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
