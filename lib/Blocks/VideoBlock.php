<?php
/**
 * Create and register the VideoBlock
 */

namespace Geniem\Theme\Blocks;

use \Geniem\ACF\Field;

/**
 * VideoBlock class.
 */
class VideoBlock extends BaseBlock {
    /**
     * The block name (slug, not shown in admin).
     *
     * @var string
     */
    const NAME = 'video';

    /**
     * Create the block and register it.
     */
    public function __construct() {
        // Define block title.
        $this->title             = 'Video';
        $this->prefix            = static::NAME . '_';
        $this->icon              = 'format-video';
        $this->description       = 'Voit lisätä videon Helsinki Kanavasta, YouTubesta ja Vimeosta.';
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
            'title' => [
                'label' => 'Otsikko',
            ],
            'description' => [
                'label' => 'Kuvausteksti',
            ],
            'video' => [
                'label' => 'Videon osoite',
            ],
            'link' => [
                'label' => 'Linkki',
            ],
        ];

        // title
        $title = new Field\Text( $strings['title']['label'] );
        $title->set_key( $this->prefix . 'title' );
        $title->set_name( 'title' );
        $this->fields[] = $title;

        // description
        $description = new Field\TextArea( $strings['description']['label'] );
        $description->set_key( $this->prefix . 'description' );
        $description->set_name( 'description' );
        $this->fields[] = $description;

        // video
        $video = new Field\URL( $strings['video']['label'] );
        $video->set_key( $this->prefix . 'video' );
        $video->set_name( 'video' );
        $video->set_required();
        $this->fields[] = $video;

        // link
        $link = new Field\Link( $strings['link']['label'] );
        $link->set_key( $this->prefix . 'link' );
        $link->set_name( 'link' );
        $this->fields[] = $link;

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
        if ( empty( $data['video'] ) ) {
            return [];
        }

        if ( strpos( $data['video'], 'helsinkikanava' ) !== false ) {
            $video_url     = str_replace( 'player', 'player/embed', $data['video'] );
            $data['video'] = $video_url;
        }
        else {
            $oembed = \wp_oembed_get( $data['video'] );
            if ( $oembed === false ) {
                return $data;
            }

            preg_match( '/src="(.+?)"/', $oembed, $matches );
            $data['video'] = $matches[1];
        }

        return $data;
    }
}
