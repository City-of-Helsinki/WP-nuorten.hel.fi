<?php
/**
 * In this class we define the 'Unit link list' field.
 */

namespace Geniem\Theme\ACF\Fields;

use \Geniem\ACF\Field;

/**
 * Class UnitLinkList.
 */
class UnitLinkList extends Field\Group {

    /**
     * The original key prefix of the field.
     *
     * @var string
     */
    protected $key = '_unit_link_list';

    /**
     * The original name of the field.
     *
     * @var string
     */
    protected $name = 'unit_link_list';

    /**
     * The original label of the field.
     *
     * @var string
     */
    protected $label = 'Linkkilista';

    /**
     * The constructor for this field
     *
     * @throws \Geniem\ACF\Exception Throw error if given parameter is not valid.
     *
     * @param string|null $key   Field key.
     */
    public function __construct( $key ) {

        // Construct the actual PHP field with given parameters or class defaults.
        $label = $this->label;
        $key   = empty( $key ) ? $this->key : $key . $this->key;
        $name  = $this->name;

        // Call the parent's constructor.
        parent::__construct( $label, $key, $name );

        $this->add_link_list_fields();
    }

    /**
     * Add link list fields.
     *
     * @return void
     */
    private function add_link_list_fields() : void {
        $key = $this->key;

        $strings = [
            'layout'      => 'Linkkilista',
            'title'       => [
                'label' => 'Otsikko',
            ],
            'description' => [
                'label' => 'Kuvausteksti',
            ],
            'repeater'    => [
                'label' => 'Linkit',
            ],
            'link'        => [
                'label'        => 'Linkki',
                'instructions' => '',
            ],
        ];

        $title = new Field\Text( $strings['title']['label'] );
        $title->set_key( "${key}_title" );
        $title->set_name( 'title' );
        $title->redipress_include_search();

        $description = new Field\Textarea( $strings['description']['label'] );
        $description->set_key( "${key}_description" );
        $description->set_name( 'description' );
        $description->set_new_lines();
        $description->redipress_include_search();

        $links = new Field\Repeater( $strings['repeater']['label'] );
        $links->set_key( "${key}_links" );
        $links->set_name( 'links' );

        $link = new Field\Link( $strings['link']['label'] );
        $link->set_key( "${key}_link" );
        $link->set_name( 'link' );
        $link->redipress_include_search();
        $links->add_field( $link );

        $this->add_fields( [
            $title,
            $description,
            $links,
        ] );
    }
}
