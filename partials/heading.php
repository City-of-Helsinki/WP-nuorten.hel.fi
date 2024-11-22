<?php
/**
 * Page heading partial
 */

$args = wp_parse_args( $args, [
    'title' => null,
    'level' => 'h2',
    'class' => '',
    'icon'  => '',
] );

$title = $args['title'] ?? get_the_title();
$icon  = empty( $args['icon'] )
        ? ''
        : '<img src="' . esc_url( $args['icon'] ) . '" alt="" role="img" aria-hidden="true">';
?>
<<?php echo esc_attr( $args['level'] ); ?>
    class="<?php echo esc_attr( $args['class'] ); ?>"
><?php echo wp_kses_post( $icon ); ?><?php echo esc_html( $title ); ?></<?php echo esc_attr( $args['level'] ); ?>>
