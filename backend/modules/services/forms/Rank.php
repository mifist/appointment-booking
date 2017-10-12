<?php
namespace Bookly\Backend\Modules\Services\Forms;

use Bookly\Lib;

/**
 * Class Category
 * @package Bookly\Backend\Modules\Services\Forms
 */
class Rank extends Lib\Base\Form
{
    protected static $entity_class = 'Rank';
	
	/**
	 * Configure the form.
	 */
	public function configure()
	{
		$fields = array(
			'id',
			'name'
		);
		
		$this->setFields( $fields  );
	}
	

}
