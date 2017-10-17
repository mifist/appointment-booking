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
	 * @var Service[]
	 */
	private $services;
	
	/**
	 * Get translated title (if empty returns "Untitled").
	 *
	 * @param string $locale
	 * @return string
	 */
	public function getTitle( $locale = null )
	{
		return Lib\Utils\Common::getTranslatedString(
			'rank_' . $this->get( 'id' ),
			$this->get( 'name' ) != '' ? $this->get( 'name' ) : __( 'Untitled', 'bookly' ),
			$locale
		);
	}

    /**
     * @param Category $category
     */
    public function addCategory( Category $category )
    {
        $this->categories[] = $category;
    }
	
	/**
	 * @param Service $service
	 */
	public function addService( Service $service )
	{
		$this->services[] = $service;
	}
	
	/**
	 * @return Service[]
	 */
	public function getServices()
	{
		return $this->services;
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
    
	/**
	 * Delete rank
	 *
	 * @return bool|int
	 */
	public function delete()
	{
		Lib\Proxy\Shared::categoryDeleted( $this->get( 'id' ) );
		
		return parent::delete();
	}

}
