<?php

Loader::loadSystem('Router');

class Uri
{
	public static function getUri($options = array())
	{
		if( ! isset($options['protocol']))
			$options['protocol'] = 'http';
		
		$uri = $options['protocol'].'://'.$_SERVER['SERVER_NAME'];
		
		if(isset($options['port']))
			$uri .= ':'.$options['port'];
		
		$request = self::getUrn($options);
		
		return $uri .= $request;
	}
	
	public static function getUrl()
	{
		return $_SERVER["SERVER_NAME"];
	}
	
	public static function getUrn($options = array())
	{
		$request = $_SERVER['REQUEST_URI'];
		if(isset($options['no-params']) && $options['no-params'] == true)
			$request = ($request = explode('?', $_SERVER['REQUEST_URI'])) ? $request[0] : '/';
		
		if(isset($options['no-language']) && $options['no-language'] == true)
		{
			$request = explode('/', $_SERVER['REQUEST_URI']);
			if(in_array($request[1], Router::getLangs()))
				unset($request[1]);
			
			$request = implode('/', $request);
		}
		
		return $request;
	}
}

?>
