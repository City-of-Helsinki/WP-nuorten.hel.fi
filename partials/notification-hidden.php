<?php
/**
 * HDS visually hidden notification
 *
 * The contents of the notification will be available to screen readers but will not be visible.
 */

?>
<div class="hiddenFromScreen" aria-atomic="true" aria-live="assertive" role="status">
    <?php get_template_part( 'partials/notification', '', $args ); ?>
</div>
