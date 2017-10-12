<?php
namespace Bookly\Lib\Entities;

use Bookly\Lib;

/**
 * Class Rank
 * @package Bookly\Lib\Entities
 */
class Rank extends Lib\Base\Entity
{
    protected static $table = 'ab_rank';

    protected static $schema = array(
        'id'        => array( 'format' => '%d' ),
        'name'      => array( 'format' => '%s' ),
        'position'  => array( 'format' => '%d', 'default' => 9999 ),
    );

    protected static $cache = array();

    /**
     * @var Category[]
     */
    private $categories;

    /**
     * @param Category $category
     */
    public function addCategory( Category $category )
    {
        $this->categories[] = $category;
    }

    /**
     * @return Category[]
     */
    public function getCategories()
    {
        return $this->categories;
    }

    public function getName( $locale = null )
    {
        return Lib\Utils\Common::getTranslatedString( 'rank_' . $this->get( 'id' ), $this->get( 'name' ), $locale );
    }

    public function save()
    {
        $return = parent::save();
        if ( $this->isLoaded() ) {
            // Register string for translate in WPML.
            do_action( 'wpml_register_single_string', 'bookly', 'rank_' . $this->get( 'id' ), $this->get( 'name' ) );
        }
        return $return;
    }

}
