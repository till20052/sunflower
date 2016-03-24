<?php

class ExtendedClass
{
	private static $__instances = array();

	public static function i($instance, $args = array())
	{
		foreach($args as $__i => $__arg)
		{
			if(is_string($__arg))
				$__arg = "\"".$__arg."\"";
			if(is_bool($__arg))
				$__arg = intval($__arg);

			$args[$__i] = $__arg;
		}

		$__key = $instance."(".implode(", ", array_values($args)).")";
		
		if( ! isset(self::$__instances[$__key]))
			eval("self::\$__instances[\$__key] = new $__key;");
		
		return self::$__instances[$__key];
	}
}

