<?php
/**
 * Ticket link
 *
 * Accepts args: url for button link
 */

/**
 * Partial args
 *
 * @var string|array $args
 */

$url     = $args;
$classes = [ 'theme-engel-dark' ];

if ( is_array( $args ) ) {
    $is_free = $args['is_free'] ?: '';
    $url     = $args['url'] ?: '';
    $classes = $args['classes'] ?: [];
}

if ( empty( $url ) ) {
    return;
}

get_template_part(
    'partials/button-link',
    '',
    [
        'url'     => $url,
        'label'   => $is_free ? __( 'Sign up', 'nuhe' ) : __( 'Buy tickets', 'nuhe' ),
        'classes' => $classes,
    ]
);
