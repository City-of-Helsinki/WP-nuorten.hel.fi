<?php
/**
 * PageIframe template
 *
 * Template Name: iFrame
 */

use Geniem\Theme\Settings;

$iframe = Settings::get_setting( 'iframe' );

?>
<!doctype html>
<html <?php language_attributes(); ?> class="<?php echo esc_attr( $html_classes ); ?>">
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>
    <body>
        <?php if ( ! empty( $iframe ) ): ?>
            <iframe class="page-iframe__content" src="<?php echo esc_url( $iframe ); ?>"></iframe>
        <?php endif; ?>
    </body>
</html>
