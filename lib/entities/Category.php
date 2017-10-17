<?php
namespace Bookly\Lib\Entities;

use Bookly\Lib;

/**
 * Class Category
 * @package Bookly\Lib\Entities
 */
class Category extends Lib\Base\Entity
{
	
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
	 * @param Rank $rank
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
			return Rank::find( $this->get( 'rank_id' ) )->getName( $locale );
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
