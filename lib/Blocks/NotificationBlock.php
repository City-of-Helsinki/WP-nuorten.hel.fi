<?php
/**
 * Create and register the NotificationBlock
 */

namespace Geniem\Theme\Blocks;

use \Geniem\ACF\Field;

/**
 * NotificationBlock class.
 */
class NotificationBlock extends BaseBlock {
    /**
     * The block name (slug, not shown in admin).
     *
     * @var string
     */
    const NAME = 'notification';

    /**
     * Create the block and register it.
     */
    public function __construct() {
        // Define block title.
        $this->title             = 'Huomiotiedote';
        $this->prefix            = static::NAME . '_';
        $this->icon              = 'info-outline';
        $this->description       = 'Huomiotiedote';
        $this->supports['align'] = false;
        $this->mode              = 'edit';

        parent::__construct();
    }

    /**
     * Create block fields.
     *
     * @return array
     */
    public function fields() : array {
        $strings = [
            'title'       => [
                'label' => 'Otsikko',
            ],
            'description' => [
                'label' => 'Kuvausteksti',
            ],
            'links' => [
                'label' => 'Linkit',
            ],
            'link'        => [
                'label' => 'Linkki',
            ],
        ];

        $title = new Field\Text( $strings['title']['label'] );
        $title->set_key( "{$this->prefix}_title" );
        $title->set_name( 'title' );
        $this->fields[] = $title;

        $description = new Field\Textarea( $strings['description']['label'] );
        $description->set_key( "{$this->prefix}_description" );
        $description->set_name( 'description' );
        $description->set_new_lines();
        $description->redipress_include_search();
        $this->fields[] = $description;

        $links = new Field\Repeater( $strings['links']['label'] );
        $links->set_key( "{$this->prefix}_links" );
        $links->set_name( 'links' );
        $this->fields[] = $links;

        $link = new Field\Link( $strings['link']['label'] );
        $link->set_key( "{$this->prefix}_link" );
        $link->set_name( 'link' );
        $links->add_field( $link );

        return $this->fields;
    }
}
