<?php
/**
 * Search results grid
 */

/**
 * Partial args
 *
 * @var array $args
 */
foreach ( $args['results'] as $group => $data ) :
    if ( empty( $data['posts'] ) ) {
        continue;
    }
    ?>
    <div class="search__group search__group--<?php echo esc_attr( $group ); ?>">
        <?php if ( $data['title'] ) : ?>
            <h2 class="search__group-title" id="search__grid-<?php echo esc_attr( $group ); ?>">
                <?php echo esc_html( $data['title'] ); ?>
            </h2>
        <?php endif; ?>

        <ul class="search__grid" aria-labelledby="search__grid-<?php echo esc_attr( $group ); ?>">
            <?php foreach ( $data['posts'] as $chunk ) : ?>
                    <?php
                    foreach ( $chunk['posts'] as $item ) {
                        get_template_part(
                            $args['item_view'],
                            '',
                            [
                                'item'        => $item,
                                'group_class' => $chunk['group_class'],
                                'group'       => $chunk['group'],
                                'tag'         => $data['pt_tag'] ?? '',
                                'post_type'   => $group,
                            ]
                        );
                    }
                    ?>
                <?php if ( $chunk['show_button'] ) : ?>
                    <div class="search__more has-text-centered<?php echo esc_attr( $chunk['group_class'] . ' ' . $chunk['group'] ); ?>">
                        <?php
                        get_template_part( 'partials/button', '', [
                            'classes'    => [ 'theme-black-bg', 'js-more-toggle' ],
                            'label'      => __( 'Show more', 'nuhe' ),
                            'attributes' => [
                                'data-controls' => $chunk['button_controls'],
                            ],
                        ] );
                        ?>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endforeach; ?>
