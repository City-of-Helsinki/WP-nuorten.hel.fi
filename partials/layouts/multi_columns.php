<?php
/**
 * MultiColumns partial
 *
 * Accepts args:
 *  - title: <string>
 *  - bg_color: <string>
 *  - colums: <array>
 */

$args = \wp_parse_args( $args, [
    'title'    => '',
    'bg_color' => '',
    'columns'  => [],
] );

if ( empty( $args['columns'] ) ) {
    return;
}

$column_count   = count( $args['columns'] );
$bg_color_class = empty( $args['bg_color'] ) || $args['bg_color'] === 'default' ? '' : " has-bg-color has-bg-color--{$args['bg_color']}";
$args['anchor_id'] = \sanitize_title( strtolower( ! empty( $args['anchor_link_text'] ) ? $args['anchor_link_text'] : ( !empty ( $args['title'] ) ? $args['title'] : '' ) ) );
$anchor_id         = ! empty( $args['anchor_id'] ) ? ' id="' . $args['anchor_id'] . '"' : '';
?>

<div class="nuhe-layout nuhe-layout--main-content nuhe-multi-columns<?php echo \esc_attr( $bg_color_class ); ?>"<?php echo \wp_kses_post( $anchor_id ); ?>>
    <div class="container">
        <?php
        if ( ! empty( $args['title'] ) ) {
            \get_template_part( 'partials/heading', '', [
                'title' => $args['title'],
                'level' => 'h2',
                'class' => 'multi-columns-title',
            ] );
        }
        ?>
        <div class="multi-columns-wrapper grid-columns grid-columns--<?php echo \esc_attr( $column_count ); ?>">
                <?php foreach ( $args['columns'] as $column ) : ?>
                    <div class="grid-columns__column multi-columns-item">
                        <?php
                        if ( ! empty( $column['column_title'] ) ) {
                            \get_template_part( 'partials/heading', '', [
                                'title' => $column['column_title'],
                                'level' => 'h3',
                                'class' => 'multi-columns-item__title',
                            ] );
                        }
                        ?>

                        <?php if ( ! empty( $column['column_content'] ) ) : ?>
                            <div class="multi-columns-item__content">
                                <?php echo \wp_kses_post( $column['column_content'] ); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
        </div>
    </div>
</div>
