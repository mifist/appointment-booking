<?php
namespace Bookly\Lib\Proxy;

use Bookly\Lib\Base;

/**
 * Class CategoryExtras
 * Invoke local methods from Category Extras add-on.
 *
 * @package Bookly\Lib\Proxy
 *
 * @method static string getStepHtml( \Bookly\Lib\UserBookingData $userData, bool $show_cart_btn, string $info_text, string $progress_tracker ) Render step Repeat
 * @see \BooklyCategoryExtras\Lib\ProxyProviders\Local::getStepHtml()
 *
 * @method static void renderAppearance( string $progress_tracker ) Render extras in appearance.
 * @see \BooklyCategoryExtras\Lib\ProxyProviders\Local::renderAppearance()
 *
 * @method static \BooklyCategoryExtras\Lib\Entities\CategoryExtra[] findByIds( array $extras_ids ) Return extras entities.
 * @see \BooklyCategoryExtras\Lib\ProxyProviders\Local::findByIds()
 *
 * @method static \BooklyCategoryExtras\Lib\Entities\CategoryExtra[] findByCategoryId( int $category_id ) Return extras
 * entities.
 * @see \BooklyCategoryExtras\Lib\ProxyProviders\Local::findByCategoryId()
 *
 * @method static \BooklyCategoryExtras\Lib\Entities\CategoryExtra[] findAll() Return all extras entities.
 * @see \BooklyCategoryExtras\Lib\ProxyProviders\Local::findAll()
 *
 * @method static array getInfo( string $extras_json, bool $translate )
 * @see \BooklyCategoryExtras\Lib\ProxyProviders\Local::getInfo()
 *
 * @method static int getTotalDuration( array $extras )
 * @see \BooklyCategoryExtras\Lib\ProxyProviders\Local::getTotalDuration()
 *
 * @method static int reorder( array $order )
 * @see \BooklyCategoryExtras\Lib\ProxyProviders\Local::reorder()
 *
 */
class CategoryExtras extends Base\ProxyInvoker
{
	
}