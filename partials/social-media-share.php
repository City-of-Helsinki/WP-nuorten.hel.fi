<?php
/**
 * Social media share partial
 */

if ( empty( $args['links'] ) ) {
    return;
}

?>

<div class="social-media-share">
    <span class="social-media-share__title"><?php echo esc_html( $args['share_title'] ); ?></span>
    <ul class="social-media-share__links">
        <?php
        foreach ( $args['links'] as $link ) {
            if ( $link['type'] === 'link' ) {
                ?>
                <li>
                    <?php
                    get_template_part( 'partials/link', '', [
                        'title'            => '',
                        'url'              => $link['url'],
                        'target'           => '_blank',
                        'icon'             => $link['service'],
                        'aria-label'       => $link['aria_label'],
                        'disable_external' => true,
                        'icon_size'        => 'm',
                    ] );
                    ?>
                </li>
                <?php
                continue;
            }
            ?>
            <li>
                <?php
                get_template_part( 'partials/button', '', [
                    'attributes'            => [
                        'aria-label'          => $link['aria_label'],
                        'data-clipboard-text' => $link['url'],
                        'id'                  => 'js-copy-url',
                    ],
                    'icon-start'            => $link['service'],
                    'icon_size'             => 'm',
                    'clipboard_copied_text' => _x( 'Link copied into clipboard', 'socialmediashare', 'nuhe' ),
                ] );
                ?>
            </li>
            <?php
        }
        ?>
    </ul>
</div>
