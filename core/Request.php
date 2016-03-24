<?php

class Request
{
	
	public static function get($key, $default = null)
	{
		if(isset($_REQUEST[$key]))
			return $_REQUEST[$key];
		
		return $default;
	}
	
	public static function getBool($key, $default = false)
	{
		return (boolean) self::get($key, $default);
	}
	
	public static function getInt($key, $default = 0)
	{
		return (int) self::get($key, $default);
	}
	
	public static function getFloat($key, $default = false)
	{
		return (float) self::get($key, $default);
	}
	
	public static function getString($key, $default = '')
	{
		return (string) self::get($key, $default);
	}
	
	public static function getArray($key, $default = array())
	{
		return (array) self::get($key, $default);
	}
	
	public static function getJson($key, $default = null)
	{
		return json_decode(self::get($key, $default), true);
	}
	
	public static function getAll()
	{
		return $_REQUEST;
	}
	
}

?>
