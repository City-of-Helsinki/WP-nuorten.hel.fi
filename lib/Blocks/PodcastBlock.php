<?php
/**
 * Create and register the PodcastBlock
 */

namespace Geniem\Theme\Blocks;

use \Geniem\ACF\Field;
use Geniem\Theme\Localization;

/**
 * PodcastBlock class.
 */
class PodcastBlock extends BaseBlock {
    /**
     * The block name (slug, not shown in admin).
     *
     * @var string
     */
    const NAME = 'podcast';

    /**
     * Create the block and register it.
     */
    public function __construct() {
        // Define block title.
        $this->title             = 'Podcast';
        $this->prefix            = static::NAME . '_';
        $this->icon              = 'format-audio';
        $this->description       = 'Voit lisätä podcastin Helsinki Kanavasta.';
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
            'podcast' => [
                'label' => 'Podcastin osoite',
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

        // podcast
        $podcast = new Field\URL( $strings['podcast']['label'] );
        $podcast->set_key( $this->prefix . 'podcast' );
        $podcast->set_name( 'podcast' );
        $podcast->set_required();
        $this->fields[] = $podcast;

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
        if ( empty( $data['podcast'] ) ) {
            return [];
        }

        if ( strpos( $data['podcast'], 'helsinkikanava' ) !== false ) {
            $podcast_url     = str_replace( 'player/podcast', 'player/embed/vod', $data['podcast'] );
            $data['podcast'] = $podcast_url;
        }

        return $data;
    }
}
