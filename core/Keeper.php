<?php

abstract class Keeper
{
	protected static $instances = array();
	public static function setInstance($objName)
	{
		if( ! isset(self::$instances[$objName]))
			self::$instances[$objName] = new $objName();
		
		return self::$instances[$objName];
	}
	public static function getInstance($objName)
	{
		return self::setInstance($objName);
	}
	
	protected static $registry = array();
	public static function setRegistry($key, $value)
	{
		self::$registry[$key] = $value;
	}
	public static function getRegistry($key, $default = null)
	{
		if( ! isset(self::$registry[$key]))
			return $default;
		
		return self::$registry[$key];
	}
	public static function delRegistry($key)
	{
		if(isset(self::$registry[$key]))
			unset(self::$registry[$key]);
	}
	
	protected static $variables = array();
	public static function setVariable($key, $value)
	{
		self::$variables[$key] = $value;
	}
	public static function getVariable($key, $default = null)
	{
		if( ! isset(self::$variables[$key]))
			return $default;
		
		return self::$variables[$key];
	}
}

?>
