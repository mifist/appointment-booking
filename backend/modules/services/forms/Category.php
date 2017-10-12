<?php
namespace Bookly\Backend\Modules\Services\Forms;

use Bookly\Lib;

/**
 * Class Category
 * @package Bookly\Backend\Modules\Services\Forms
 */
class Category extends Lib\Base\Form
{
    protected static $entity_class = 'Category';

    /**
     * Configure the form.
     */
    public function configure()
    {
	    $fields = array(
		    'id',
		    'rank_id',
		    'name'
	    );
	    
        $this->setFields( $fields  );
    }
	/**
	 * Bind values to form.
	 *
	 * @param array $_post
	 * @param array $files
	 */
	public function bind( array $_post, array $files = array() )
	{
		// Field with NULL
		if ( array_key_exists( 'rank_id', $_post ) && ! $_post['rank_id'] ) {
			$_post['rank_id'] = null;
		}
		
		parent::bind( $_post, $files );
	}
	
	/**
	 * @return \Bookly\Lib\Entities\Rank
	 */
	public function save()
	{
		
		
		
		return parent::save();
	}
	
}
