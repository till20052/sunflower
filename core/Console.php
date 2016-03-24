<?php

/**
 * Description of Console
 *
 * @author Morozov Artem
 */
class Console
{
	
	private static function _log ($args)
	{
		foreach($args as $arg)
		{
			if( !is_array($arg) && !is_object($arg))
				echo '<pre>'.$arg.'</pre>';
			else 
			{
				echo '<pre>';
				print_r($arg);
				echo '</pre>';
			}
		}
	}
	
	public static function log ()
	{
		self::_log(func_get_args());
	}
	
}

?>
