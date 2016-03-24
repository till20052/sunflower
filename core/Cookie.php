<?php

class Cookie
{
	public static function set($name, $value, $expire = null, $path = null, $domain = null)
	{
		setcookie(md5($name), $value, $expire, $path, $domain);
	}
	
	public static function delete($name)
	{
		self::set($name, null);
	}
	
	public static function get($name, $md5 = true)
	{
		if($md5)
			$name = md5($name);
		
		return isset($_COOKIE[$name]) ? $_COOKIE[$name] : null;
	}
}

?>
