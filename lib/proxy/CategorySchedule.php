<?php
namespace Bookly\Lib\Proxy;

use Bookly\Lib\Base;

/**
 * Class CategorySchedule
 * Invoke local methods from Category Schedule add-on.
 *
 * @package Bookly\Lib\Proxy
 *
 * @method static array getSchedule( int $category_id ) Get schedule for category
 * @see \BooklyCategorySchedule\Lib\ProxyProviders\Local::getSchedule()
 *
 */
class CategorySchedule extends Base\ProxyInvoker
{
	
}