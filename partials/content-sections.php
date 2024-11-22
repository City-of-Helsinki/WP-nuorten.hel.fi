<?php
/**
 * Front page hero partial
 */

$args = wp_parse_args( $args, [
    'sections' => [],
] );

if ( empty( $args['sections'] ) ) {
    return;
}
?>

<div class="content-sections grid-columns grid-columns--2">
    <?php foreach ( $args['sections'] as $section ) : ?>
        <div class="content-sections__section grid-columns__column <?php echo esc_attr( $section['color_theme'] ); ?>">
            <div class="links">
                <?php if ( ! empty( $section['section_title'] ) ) : ?>
                    <?php
                    get_template_part( 'partials/heading', '', [
                        'title' => $section['section_title'],
                        'level' => 'h3',
                        'class' => 'links-title',
                    ] );
                    ?>
                <?php endif; ?>

                <?php if ( ! empty( $section['section_description'] ) ) : ?>
                    <div class="links-description">
                        <?php echo wp_kses_post( $section['section_description'] ); ?>
                    </div>
                <?php endif; ?>

                <?php if ( ! empty( $section['section_links'] ) ) : ?>
                    <?php
                    get_template_part( 'partials/links', '', [
                        'links'      => $section['section_links'],
                        'type'       => 'link',
                        'classes'    => [ 'text-bold', 'text-md' ],
                        'icon-end'   => '',
                        'icon-start' => 'arrow-right',
                    ] );
                    ?>
                <?php endif; ?>

                <?php if ( ! empty( $section['read_more'] ) ) : ?>
                    <?php
                    get_template_part( 'partials/button-link', '', [
                        'classes' => [ 'outlined', 'border-black', 'small' ],
                        'title'   => $section['read_more'],
                        'label'   => $section['read_more'],
                        'url'     => $section['url'],
                    ] );
                    ?>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>
