<?php
namespace Bookly\Lib\Entities;

use Bookly\Lib;

/**
 * Class StaffCategory
 * @package Bookly\Lib\Entities
 */
class StaffCategory extends Lib\Base\Entity
{
    protected static $table = 'ab_staff_category';

    protected static $schema = array(
        'id'            => array( 'format' => '%d' ),
        'staff_id'      => array( 'format' => '%d', 'reference' => array( 'entity' => 'Staff' ) ),
        'category_id'    => array( 'format' => '%d', 'reference' => array( 'entity' => 'Service' ) ),
        'deposit'       => array( 'format' => '%s', 'default' => '100%' ),
    );

    protected static $cache = array();

    /** @var Category */
    public $category = null;

}