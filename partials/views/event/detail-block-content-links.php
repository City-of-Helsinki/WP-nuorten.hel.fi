<?php
/**
 * Detail block content links
 *
 * Accepts args: array of links
 */

/**
 * Partial args
 *
 * @var array $args
 */
$links = $args;

if ( empty( $links ) ) {
    return;
}
?>
<ul class="detail-block__content-links">
    <?php foreach ( $links as $link ) : ?>
        <li>
            <?php get_template_part( 'partials/link', '', $link ); ?>
        </li>
    <?php endforeach; ?>
</ul>
