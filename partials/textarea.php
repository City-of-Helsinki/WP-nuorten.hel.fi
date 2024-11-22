<?php
/**
 * HDS Textarea partial
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
] );

?>
<div class="hds-text-input <?php echo $args['invalid'] ? 'hds-text-input--invalid' : ''; ?>">
    <label for="<?php echo esc_attr( $args['id'] ); ?>" class="hds-text-input__label">
        <?php echo esc_html( $args['label'] ); ?>
        <?php if ( $args['required'] ) : ?>
        <span class="hds-text-input__required">*</span>
        <?php endif; ?>
    </label>
    <div class="hds-text-input__input-wrapper">
        <textarea 
            id="<?php echo esc_attr( $args['id'] ); ?>"
            class="hds-text-input__input"
            type="<?php echo esc_attr( $args['type'] ); ?>"
            name="<?php echo esc_attr( $args['name'] ); ?>"
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
        >
        <?php
        if ( $args['value'] ) {
            echo esc_html( $args['value'] );
		}
		?>
        </textarea>
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
