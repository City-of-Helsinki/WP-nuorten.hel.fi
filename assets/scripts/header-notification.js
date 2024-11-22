/**
 * HeaderNotification controller.
 *
 * This class controls the site's menu.
 */
const $ = jQuery;
import Common from './common';

/**
 * Export the class reference.
 */
export default class HeaderNotification {

    /**
     * Cache dom elements for use in the class's methods
     */
    cache() {
        this.notification = $( '.hds-notification--js' );
        this.closeNotification = $( '.hds-notification__close-button' );
    }

    /**
     * Add event listeners.
     */
    events() {
        this.closeNotification.on( 'click', this.onNotificationClose.bind( this ) );
    }

    /**
     * Show the notification if related cookie is not found.
     */
    maybeShowNotification() {
        if ( this.notification.length > 0 && ! Common.cookieExists( this.notification.data( 'notification-id' ) ) ) {
            this.notification.slideDown( 300 );
        }
    }

    /**
     * Close notification button callback
     *
     * @return {void}
     *
     * @param {Object} event Click event.
     */
    onNotificationClose( event ) {

        const $notification = $( event.currentTarget ).parent();

        Common.setCookie( $notification.data( 'notification-id' ), '', 7 );

        $notification.remove();
    }

    /**
     * Run when the document is ready.
     */
    docReady() {
        this.cache();
        this.events();
        this.maybeShowNotification();
    }
}
