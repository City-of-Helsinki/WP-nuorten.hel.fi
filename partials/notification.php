<?php
/**
 * HDS notification partial
 *
 * Accepts args:
 *  - classes: <Array>
 *  - icon: <string>
 *  - label: <string>
 *  - text: <HTML string>
 *  - close: <bool>
 */

$args = wp_parse_args( $args, [
    'classes'         => [],
    'icon'            => '',
    'label'           => '',
    'text'            => '',
    'close'           => false,
    'notification_id' => '',
] );

$notification_id = empty( $args['notification_id'] ) ? '' : " data-notification-id={$args['notification_id']}";

?>
<div class="hds-notification 
<?php
echo esc_attr(
    hds_get_prefixed_classes_string( $args['classes'], 'hds-notification--' )
);
?>
"
<?php echo esc_attr( $notification_id ); ?>
>

    <div class="hds-notification__label">
        <?php
        if ( ! empty( $args['icon'] ) ) {
            get_template_part( 'partials/icon', '', [ 'icon' => $args['icon'] ] );
        }
        ?>
        <?php if ( ! empty( $args['label'] ) ) : ?>
            <span><?php echo esc_html( $args['label'] ); ?></span>
        <?php endif; ?>
    </div>
    <div class="hds-notification__body">
        <?php
            echo wp_kses( $args['text'], [
                'a'      => [
                    'href'  => [],
                    'title' => [],
                ],
                'p'      => [
                    'class' => [],
                ],
                'br'     => [],
                'em'     => [],
                'strong' => [],
            ] );
			?>
    </div>

    <?php if ( $args['close'] ) : ?>
    <button 
        class="hds-notification__close-button"
        aria-label="<?php esc_attr_e( 'Close', 'nuhe' ); ?>" 
        type="button"
    >
        <span class="hds-icon hds-icon--cross" aria-hidden="true"></span>
    </button>
    <?php endif; ?>
</div>
