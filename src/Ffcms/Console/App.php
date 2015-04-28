<?php 

namespace Ffcms\Console;

use \Core\Property;

class App
{
	public static $Property;
	
	public static function build()
	{
		self::$Property = new Property();
	}
	
	public static function test()
	{
		echo "Good test";
	}
	
}