<?php

class Session extends Keeper
{
	public static function start()
	{
		session_start();
	}
	
	public static function set($key, $value)
	{
		$_SESSION[$key] = $value;
	}
	
	public static function get($key, $default = null)
	{
		if( ! isset($_SESSION[$key]))
			return $default;
		
		return $_SESSION[$key];
	}
	
	public static function delete($key)
	{
		unset($_SESSION[$key]);
	}
	
	public static function setUserId($value)
	{
		self::set('UID', $value);
	}
	
	public static function getUserId()
	{
		return self::get('UID');
	}


	public static function isAuthorized()
	{
		return (bool) self::getUserId();
	}
}

?>
