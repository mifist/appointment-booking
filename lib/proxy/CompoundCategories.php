<?php
namespace Bookly\Lib\Proxy;

use Bookly\Lib\Base;

/**
 * Class CompoundCategories
 * Invoke local methods from Compound Services add-on.
 *
 * @package Bookly\Lib\Proxy
 *
 * @method static void cancelAppointment( \Bookly\Lib\Entities\CustomerAppointment $customer_appointment ) Cancel compound appointment
 * @see \BooklyCompoundCategories\Lib\ProxyProviders\Local::cancelAppointment()
 *
 * @method static void renderSubCategories( array $category, array $category_collection, $sub_categories ) Render sub
 * categories for compound
 * @see \BooklyCompoundCategories\Lib\ProxyProviders\Local::renderSubCategories()
 */
class CompoundCategories extends Base\ProxyInvoker
{
	
}