<?php

class Lib
{
	private static $__instances = array();
	
	/**
	 * @return Lib
	 */
	public static function i($instance)
	{
		$__class = $instance;
		
		if( ! isset(self::$__instances[$__class]))
			self::$__instances[$__class] = new $__class();
		
		return self::$__instances[$__class];
	}
}
