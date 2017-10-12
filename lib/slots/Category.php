<?php
namespace Bookly\Lib\Slots;

/**
 * Class Category
 * @package Bookly\Lib\Slots
 */
class Category
{
	/** @var string */
	protected $title;
	
	/**
	 * Constructor.
	 *
	 * @param string $title
	 */
	public function __construct( $title )
	{
		$this->title        = (string) $title;
	}
	
	/**
	 * Get title.
	 *
	 * @return float
	 */
	public function title()
	{
		return $this->title;
	}
}