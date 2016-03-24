<?php

include CORE.DS.'exceptions'.DS.'CoreException.php';

class Loader
{
	private static function loadFile($path)
	{
		try
		{
			if( ! is_file($path))
				throw new CoreException('File doesn\'t exists: '.$path);
		
			include_once $path;
		}
		catch(CoreException $exception)
		{
			$exception->display();
		}
	}
	
	public static function load($path)
	{
		try
		{
			$tokens = explode('.', $path);

			if(strpos(str_replace('.', '/', $path), ROOT.DS) === false)
				$path = str_replace('/', '.', ROOT.DS).$path;

			if($tokens[count($tokens)-1] != '*')
				self::loadFile(str_replace('.', '/', $path).'.php');
			else
			{
				$path = implode('/', array_splice($tokens, 0, count($tokens)-1));
				
				if( ! is_dir($path))
					throw new CoreException('Directory not exists: '.$path);


				if( ! $handler = opendir($path))
					return false;

				while(($filename = readdir($handler)) !== false)
				{
					if(in_array($filename, array('.', '..')) || is_dir($path.DS.$filename))
						continue;

					self::loadFile($path.DS.$filename);
				}

				closedir($handler);
			}
		}
		catch(CoreException $exception)
		{
			$exception->display();
		}
	}
	
	public static function loadAppliaction($application)
	{
		$tokens = explode('.', $application);
		
		if(count($tokens) > 1)
			$path = str_replace('/', '.', APPS.DS).implode('.', $tokens);
		else
			$path = str_replace('/', '.', Router::getAppFolder().DS).$application;
		
		return self::load($path);
	}
	
	public static function loadModule($module)
	{
		$tokens = explode('.', $module);
		
		$folder = ($folder = explode('/', Router::getAppFolder())) ? $folder[count($folder)-1] : null;
		
		$path = $folder.'.modules.';
		
		if(count($tokens) > 1)
			$path .= implode('.', $tokens);
		else
			$path .= Router::getModule().'.'.implode('.', $tokens);
		
		return self::loadAppliaction($path);
	}
	
	public static function loadController($controller)
	{
		$tokens = explode('.', $controller);
		if(count($tokens) > 1)
			$path = $controller;
		else
			$path = Router::getModule().'.controllers.'.$controller;
		
		return self::loadModule($path);
	}
	
	const MODULE = 0;
	const APPLICATION = 10;
	const SYSTEM = 100;

	public static function loadModel($model, $from = self::APPLICATION)
	{
		if($from != self::SYSTEM)
		{
			$path = Router::getAppFolder();
			if($from != self::APPLICATION)
			{
				$path .= DS.'modules';
				$tokens = explode('.', $model);
				if(count($tokens) > 1)
					$path .= DS.$model;
				else
					$path .= DS.Router::getModule().'.models.'.$model;
			}
			else
				$path .= DS.'libs'.DS.'models'.DS.$model;
		}
		else
			$path = LIBS.DS.'models'.DS.$model;
		
		self::load(str_replace('/', '.', $path));
	}
	
	public static function loadClass($class, $from = self::APPLICATION)
	{
		if($from != self::SYSTEM)
		{
			$path = Router::getAppFolder();
			if($from != self::APPLICATION)
			{
				$path .= DS.'classes';
				$tokens = explode('.', $class);
				if(count($tokens) > 1)
					$path .= DS.$class;
				else
					$path .= DS.Router::getModule().'.classes.'.$class;
			}
			else
				$path .= DS.'libs'.DS.'classes'.DS.$class;
		}
		else
			$path = LIBS.DS.'classes'.DS.$class;
		
		self::load(str_replace('/', '.', $path));
	}

	public static function loadService($class, $from = self::APPLICATION)
	{
		if($from != self::SYSTEM)
		{
			$path = Router::getAppFolder();
			if($from != self::APPLICATION)
			{
				$path .= DS.'services';
				$tokens = explode('.', $class);
				if(count($tokens) > 1)
					$path .= DS.$class;
				else
					$path .= DS.Router::getModule().'.services.'.$class;
			}
			else
				$path .= DS.'libs'.DS.'services'.DS.$class;
		}
		else
			$path = LIBS.DS.'services'.DS.$class;

		self::load(str_replace('/', '.', $path));
	}
	
	public static function loadSystem($system)
	{
		self::load(str_replace('/', '.', CORE.DS).$system);
	}
	
//	public static function loadLib($library)
//	{
//		self::load(str_replace('/', '.', LIBS.DS).$library);
//	}
	
	public static function loadLib($library, $from = self::SYSTEM)
	{
		if($from != self::SYSTEM)
		{
			$path = Router::getAppFolder();
			if($from != self::APPLICATION)
			{
				$path .= DS;
				$tokens = explode('.', $library);
				if(count($tokens) > 1)
					$path .= DS.$library;
				else
					$path .= DS.Router::getModule().$library;
			}
			else
				$path .= DS.'libs'.DS.$library;
		}
		else
			$path = LIBS.DS.$library;
		
		self::load(str_replace('/', '.', $path));
	}
}

?>
