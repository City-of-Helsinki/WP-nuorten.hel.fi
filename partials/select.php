<?php
/**
 * Select partial
 */

/**
 * Partial args
 *
 * @var array $args
 */
$args = wp_parse_args( $args, [
    'label'          => '',
    'selected'       => '',
    'name'           => '',
    'id'             => '',
    'choices'        => [],
    'hide_label'     => false,
    'attributes'     => [],
    'assistive_text' => false,
] );

?>
<div class="hds-select-input">
    <label
        for="<?php echo esc_attr( $args['id'] ); ?>"
        class="hds-select-input__label <?php echo $args['hide_label'] ? 'hiddenFromScreen' : ''; ?>"
    >
        <?php echo esc_html( $args['label'] ); ?>
    </label>

    <div class="hds-select-input__input-wrapper">
        <?php get_template_part( 'partials/icon', '', [ 'icon' => 'angle-down', 'size' => 's' ] ); ?>
        <select
            id="<?php echo esc_attr( $args['id'] ); ?>"
            class="hds-select-input__input"
            name="<?php echo esc_attr( $args['name'] ); ?>"
            <?php
            // Ignoring coding standards here because attributes are already escaped for use in attributes.
            // @codingStandardsIgnoreStart
            echo hds_get_escaped_attributes_string( $args['attributes'] );
            // @codingStandardsIgnoreEnd
            ?>
        >
            <?php foreach ( $args['choices'] as $value => $choice ) : ?>
                <option
                    value="<?php echo esc_attr( $value ); ?>" <?php selected( $args['selected'], $value ); ?>>
                    <?php echo esc_html( $choice ); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <?php if ( ! empty( $args['assistive_text'] ) ) : ?>
        <span
            class="hds-text-input__helper-text"
            id="<?php echo esc_attr( $args['id'] ) . '-helptext'; ?>"
        >
            <?php echo esc_html( $args['assistive_text'] ); ?>
        </span>
    <?php endif; ?>
</div>
