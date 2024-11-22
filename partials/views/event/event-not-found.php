<?php
/**
 * Event 404
 */

?>
<div class="event event--not-found">
    <header class="event__header">
        <div class="container">

            <h1 class="event__title">
                <?php esc_html_e( 'Event not found', 'nuhe' ); ?>
            </h1>
        </div>
    </header>

    <div class="event__details">
        <div class="container">
            <p class="event__not-found-text">
                <?php esc_html_e( 'We can\'t seem to find the event you are looking for.', 'nuhe' ); ?>
            </p>
        </div>
    </div>
</div>
