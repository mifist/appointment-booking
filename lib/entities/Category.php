<?php
namespace Bookly\Lib\Entities;

use Bookly\Lib;

/**
 * Class Category
 * @package Bookly\Lib\Entities
 */
class Category extends Lib\Base\Entity
{
	
	const TYPE_SIMPLE   = 'simple';
	const TYPE_COMPOUND = 'compound';
	
    protected static $table = 'ab_categories';

    protected static $schema = array(
        'id'        => array( 'format' => '%d' ),
        'name'      => array( 'format' => '%s' ),
        'rank_id'   => array( 'format' => '%d', 'reference' => array( 'entity' => 'Rank' ) ),
        'position'  => array( 'format' => '%d', 'default' => 9999 ),
    );

    protected static $cache = array();

    /**
     * @var Service[]
     */
    private $services;
	
	/**
	 * @var Rank[]
	 */
	private $ranks;
	
	
	/**
	 * Get translated title (if empty returns "Untitled").
	 *
	 * @param string $locale
	 * @return string
	 */
	public function getTitle( $locale = null )
	{
		return Lib\Utils\Common::getTranslatedString(
			'category_' . $this->get( 'id' ),
			$this->get( 'name' ) != '' ? $this->get( 'name' ) : __( 'Untitled', 'bookly' ),
			$locale
		);
	}
	
    /**
     * @param Service $service
     */
    public function addService( Service $service )
    {
        $this->services[] = $service;
    }
	
	/**
	 * @param Service $service
	 */
	public function addRanks( Rank $rank )
	{
		$this->ranks[] = $rank;
	}
	
	/**
	 * Get rank name.
	 *
	 * @param string $locale
	 * @return string
	 */
	public function getRankName( $locale = null )
	{
		if ( $this->get( 'rank_id' ) ) {
			return Category::find( $this->get( 'rank_id' ) )->getName( $locale );
		}
		
		return __( 'Unranks', 'bookly' );
	}
	/**
	 * @return Rank[]
	 */
	public function getRank()
	{
		return $this->ranks;
	}
	
    
    /**
     * @return Service[]
     */
    public function getServices()
    {
        return $this->services;
    }

    public function getName( $locale = null )
    {
        return Lib\Utils\Common::getTranslatedString( 'category_' . $this->get( 'id' ), $this->get( 'name' ), $locale );
    }
	
	/**
	 * Get sub services.
	 *
	 * @return Service[]
	 */
	public function getSubCategories()
	{
		$result = array();
		$sub_category_ids = json_decode( $this->get( 'sub_category' ), true );
		$categories = self::query()
		                ->whereIn( 'id', $sub_category_ids )
		                ->where( 'type', self::TYPE_SIMPLE )
		                ->indexBy( 'id' )
		                ->find();
		// Order services like in sub_services array.
		foreach ( $sub_category_ids as $category_id ) {
			$result[] = $categories[ $category_id ];
		}
		
		return $result;
	}
    
    public function save()
    {
        $return = parent::save();
        if ( $this->isLoaded() ) {
            // Register string for translate in WPML.
            do_action( 'wpml_register_single_string', 'bookly', 'category_' . $this->get( 'id' ), $this->get( 'name' ) );
        }
        return $return;
    }
    
	/**
	 * Delete category
	 *
	 * @return bool|int
	 */
	public function delete()
	{
		Lib\Proxy\Shared::categoryDeleted( $this->get( 'id' ) );
		
		return parent::delete();
	}
	
}
