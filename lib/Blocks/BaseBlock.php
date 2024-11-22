<?php
/**
 * Base for ACF blocks. Here we define useful method for all blocks.
 */

namespace Geniem\Theme\Blocks;

use \Geniem\ACF\Block;
use \Geniem\ACF\Renderer\PHP;

/**
 * Class BaseBlock.
 *
 * @property string title Block title.
 */
class BaseBlock {

    /**
     * The block name, or actually the slug that is used to
     * register the block.
     *
     * @var string
     */
    const NAME = '';

    /**
     * The block description. Used in WP block navigation.
     *
     * @var string
     */
    protected $description = '';

    /**
     * The block category. Used in WP block navigation.
     *
     * @var string
     */
    protected $category = 'common';

    /**
     * The block icon. Used in WP block navigation.
     *
     * @var string
     */
    protected $icon = 'menu';

    /**
     * The block mode. ACF has a few different options.
     * Edit opens the block always in edit mode for example.
     *
     * @var string
     */
    protected $mode = 'preview';

    /**
     * The block supports. You can add all ACF support attributes here.
     *
     * @var array
     */
    protected $supports = [
        'align'  => [
            'left',
            'center',
        ],
        'mode'   => false,
        'anchor' => true,
    ];

    /**
     * Block fields.
     *
     * @var array
     */
    protected $fields = [];

    /**
     * Class constructor.
     */
    public function __construct() {

        // Initialize block.
        $block = new Block( $this->title, static::NAME );
        $block->set_category( $this->category );
        $block->set_icon( $this->icon );
        $block->set_description( $this->description );
        $block->set_mode( $this->mode );
        $block->set_supports( $this->supports );
        $block->set_renderer( $this->get_renderer() );

        // Maybe add block fields.
        if ( method_exists( get_called_class(), 'fields' ) ) {
            $block->add_fields( call_user_func( [ $this, 'fields' ] ) );
        }

        // Maybe filter the block data.
        if ( method_exists( get_called_class(), 'filter_data' ) ) {
            $block->add_data_filter( [ $this, 'filter_data' ] );
        }

        $block->register();
    }

    /**
     * Get the renderer.
     *
     * @throws \Exception Thrown if template is not found.
     *
     * @return PHP
     */
    protected function get_renderer() {
        $partial_name = '/partials/blocks/' . static::NAME;
        $file         = get_template_directory() . $partial_name . '.php';

        if ( file_exists( $file ) ) {
            return new PHP( $file );
        }

        $file = get_stylesheet_directory() . $partial_name . '.php';

        if ( file_exists( $file ) ) {
            return new PHP( $file );
        }

        throw new \Exception( "{$file} was not found" );
    }

    /**
     * Getter for block name.
     *
     * @return string
     */
    public function get_name() {
        return static::NAME;
    }
}
