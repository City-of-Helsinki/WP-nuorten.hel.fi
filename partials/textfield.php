<?php
/**
 * HDS Textfield partial
 */

$args = wp_parse_args( $args, [
    'label'          => '',
    'value'          => '', // Use if you want to prefill the value
    'name'           => '',
    'id'             => '',
    'placeholder'    => '',
    'type'           => 'text',
    'assistive_text' => '',
    'invalid'        => false,
    'required'       => false,
    'readonly'       => false,
    'disabled'       => false,
    'hide_label'     => false,
    'attributes'     => [],
    'classes'        => [],
] );

?>
<div class="hds-text-input<?php echo $args['invalid'] ? ' hds-text-input--invalid' : ''; ?> <?php echo esc_attr( implode( ' ', $args['classes'] ) ); ?>">
    <label 
        for="<?php echo esc_attr( $args['id'] ); ?>" 
        class="hds-text-input__label <?php echo $args['hide_label'] ? 'hiddenFromScreen' : ''; ?>"
    >
        <?php echo esc_html( $args['label'] ); ?>
        <?php if ( $args['required'] ) : ?>
        <span class="hds-text-input__required">*</span>
        <?php endif; ?>
    </label>
    <div class="hds-text-input__input-wrapper">
        <input 
            id="<?php echo esc_attr( $args['id'] ); ?>"
            class="hds-text-input__input"
            type="<?php echo esc_attr( $args['type'] ); ?>"
            name="<?php echo esc_attr( $args['name'] ); ?>"
        <?php if ( $args['value'] ) : ?>
            value="<?php echo esc_attr( $args['value'] ); ?>"
        <?php endif; ?>            
        <?php if ( $args['placeholder'] ) : ?>
            placeholder="<?php echo esc_attr( $args['placeholder'] ); ?>"
        <?php endif; ?>
        <?php if ( ! empty( $args['assistive_text'] ) ) : ?>
            aria-describedby="<?php echo esc_attr( $args['id'] ) . '-helptext'; ?>"
        <?php endif; ?>
        <?php if ( $args['required'] ) : ?>
            required
        <?php endif; ?>
        <?php if ( $args['disabled'] ) : ?>
            disabled
        <?php endif; ?>
        <?php if ( $args['readonly'] ) : ?>
            readonly
        <?php endif; ?>
        <?php if ( $args['invalid'] ) : ?>
            invalid
        <?php endif; ?>
        <?php
            // Ignoring coding standards here because attributes are already escaped for use in attributes.
            // @codingStandardsIgnoreStart
            echo hds_get_escaped_attributes_string( $args['attributes'] );
            // @codingStandardsIgnoreEnd
        ?>
                
        >
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
