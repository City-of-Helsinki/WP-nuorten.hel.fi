<?php
/**
 * Links partial
 */

$args = wp_parse_args( $args, [
    'list_classes' => [],
    'links'        => [],
    'type'         => 'link',
    'classes'      => [],
    'icon-start'   => '',
    'icon-end'     => '',
    'attributes'   => [],
] );

$links_template_part = $args['type'] === 'link' ? 'link' : 'button-link';

$list_classes = array_merge( [ 'links-list' ], $args['list_classes'] );
$list_classes = implode( ' ', $list_classes );

if ( empty( $args['links'] ) ) {
    return;
}
?>

<ul
    class="<?php echo esc_attr( $list_classes ); ?>"
    <?php
    // Ignoring coding standards here because attributes are already escaped for use in attributes.
    // @codingStandardsIgnoreStart
    echo hds_get_escaped_attributes_string( $args['attributes'] );
    // @codingStandardsIgnoreEnd
    ?>
>
    <?php foreach ( $args['links'] as $link ) : ?>

        <?php
        // Skip rendering if the link has no URL or title
        if ( empty( $link['title'] ) && empty( $link['url'] ) ) {
            continue;
        }
        ?>
        <li>
            <?php
            get_template_part( "partials/${links_template_part}", '', [
                'classes'    => $args['classes'],
                'title'      => $link['title'],
                'label'      => $link['title'],
                'url'        => $link['url'],
                'icon-start' => $args['icon-start'],
                'icon-end'   => $args['icon-end'],
                'is_active'  => $link['is_active'] ?? false,
                'attributes' => $link['attributes'] ?? [],
            ] );
            ?>
        </li>
    <?php endforeach; ?>
</ul>
