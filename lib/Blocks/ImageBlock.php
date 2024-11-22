<?php
/**
 * Create and register the ImageBlock
 */

namespace Geniem\Theme\Blocks;

use \Geniem\ACF\Field;
use \Geniem\ACF\ConditionalLogicGroup;

/**
 * ImageBlock class.
 */
class ImageBlock extends BaseBlock {
    /**
     * The block name (slug, not shown in admin).
     *
     * @var string
     */
    const NAME = 'image';

    /**
     * Create the block and register it.
     */
    public function __construct() {
        // Define block title.
        $this->title       = 'Kuva';
        $this->prefix      = static::NAME . '_';
        $this->icon        = 'format-image';
        $this->description = 'Huomioithan käyttämäsi kuvan tiedoissa kuvaajan tiedot sekä kuvan käyttöoikeudet';

        parent::__construct();
    }

    /**
     * Create block fields.
     *
     * @return array
     */
    public function fields() : array {
        $strings = [
            'image' => [
                'label' => 'Kuva',
            ],
            'hide_p_name' => [
                'label' => 'Piilota valokuvaajan nimi',
            ],
            'use_alternative_description' => [
                'label' => 'Käytä vaihtoehtoista kuvatekstiä/piilota kuvateksti',
            ],
            'alternative_description' => [
                'label' => 'Vapaaehtoinen kuvateksti (jätä tyhjäksi, jos haluat piilottaa kuvatekstin',
            ],
        ];

        // image
        $image = new Field\Image( $strings['image']['label'] );
        $image->set_key( $this->prefix . 'image' );
        $image->set_name( 'image' );
        $this->fields[] = $image;

        // hide photographer name -field
        $hide_p_name = new Field\TrueFalse( $strings['hide_p_name']['label'] );
        $hide_p_name->set_key( $this->prefix . 'hide_p_name' );
        $hide_p_name->set_name( 'hide_p_name' );
        $this->fields[] = $hide_p_name;

        // use alternative description -field
        $use_alt_desc = new Field\TrueFalse( $strings['use_alternative_description']['label'] );
        $use_alt_desc->set_key( $this->prefix . 'use_alt_desc' );
        $use_alt_desc->set_name( 'use_alt_desc' );
        $this->fields[] = $use_alt_desc;

        // alt desc conditional logic
        $alt_desc_conditional_logic = new ConditionalLogicGroup();
        $alt_desc_conditional_logic->add_rule( $use_alt_desc, '==', 1 );

        // alternative description field
        $alt_desc = new Field\Textarea( $strings['alternative_description']['label'] );
        $alt_desc->set_key( $this->prefix . 'alt_desc' );
        $alt_desc->set_name( 'alt_desc' );
        $alt_desc->add_conditional_logic( $alt_desc_conditional_logic );
        $this->fields[] = $alt_desc;

        // Return all fields of this block.
        return $this->fields;
    }

    /**
     * This filters the block ACF data.
     *
     * @param array            $data        Block's ACF data.
     * @param Geniem\ACF\Block $instance    The block instance.
     * @param array            $block       The original ACF block array.
     * @param string           $content     The HTML content.
     * @param bool             $is_preview  A flag that shows if we're in preview.
     * @param int              $post_id     The parent post's ID.
     *
     * @return array The block data.
     */
    public function filter_data( $data, $instance, $block, $content, $is_preview, $post_id ) : array {
        if ( $data === false ) {
            return [];
        }

        $align = $block['align'];
        if ( ! empty( $align ) ) {
            $data['align'] = $align;
        }

        if ( isset( $data['image'] ) && ! empty( $data['image'] ) ) {
            $photographer_name = \get_field( 'photographer_name', $data['image']['ID'] );

            if ( ! empty( $photographer_name ) && ! $data['hide_p_name'] ) {
                $data['image']['photographer_name'] = $photographer_name;
            }

            if ( ! empty( $data['use_alt_desc'] ) ) {
                $data['image']['caption'] = $data['alt_desc'] ?? '';
            }
        }

        return $data;
    }
}
