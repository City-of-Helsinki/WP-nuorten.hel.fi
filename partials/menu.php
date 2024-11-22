<?php
/**
 * Menu partial
 */

$args = wp_parse_args( $args, [
    'classes'    => [], // Additional classes for the <nav> wrapper.
    'aria-label' => __( 'Menu', 'nuhe' ), // Used for aria-label.
] );
?>
<nav aria-label="<?php echo esc_attr( $args['aria-label'] ); ?>" class="menu <?php echo esc_attr( hds_get_prefixed_classes_string( $args['classes'] ) ); ?>">
    <?php
    wp_nav_menu([
        'theme_location' => 'primary_navigation',
        'container'      => false,
        'items_wrap'     => '<ul id="header-%1$s" class="container container--full-small menu__list">%3$s</ul>',
        'walker'         => new Geniem\Theme\Helpers\NavWalker(),
        'depth'          => 2,
	]);
    ?>
</nav>
