<?php

Loader::loadSystem('Uri');
Loader::loadLib('functions.mime');

class Router
{
	public static function setRule()
	{
		
	}
	public static function getRule()
	{
		
	}
	
	public static function setAppKey($key)
	{
		if( ! is_null($key))
			Config::set('AppKey', $key);
		else
			Config::delete('AppKey');
	}
	public static function getAppKey()
	{
		try 
		{
			if( ! $key = Config::get('AppKey'))
			{
				$domain = Uri::getUrl();
				
				$apps = Config::get('Configuration');
				
				$flag = false;
				foreach($apps as $key => $app)
				{
					if(isset($app['domain']) && substr($domain, strlen($domain) - strlen($app['domain'])) == $app['domain'])
					{
						self::setAppKey($key);
						$flag = true;
					}
				}

				if( ! $flag)
				{
					throw new CoreException('Application with domain name <strong>'.$domain.'</strong> doesn\'t exists.');
				}
			}
			
			return $key;
		}
		catch(CoreException $exception)
		{
			$exception->display();
		}
	}
	
	public static function getAppParam($param)
	{
		try
		{
			$apps = Config::get('Configuration');
			
			if( ! isset($apps[self::getAppKey()][$param]))
				throw new CoreException('In app configuration not set '.$param);
			
			return $apps[self::getAppKey()][$param];
		}
		catch(CoreException $exception)
		{
			$exception->display();
		}
	}
	
	public static function setAppFolder($path)
	{
		try
		{
			if( ! is_dir($path))
				throw new CoreException('Application folder <strong>'.$path.'</strong> not exists.');
			
			Config::set('AppFolder', $path);
			return $path;
		}
		catch(CoreException $exception)
		{
			$exception->display();
		}
	}
	public static function getAppFolder()
	{
		if( ! $path = Config::get('AppFolder'))
			$path = self::setAppFolder(self::getAppParam('path'));

		return $path;
	}
	
	public static function isStaticFile($filename)
	{
		$path = self::getAppFolder().DS.'static'.$filename;
		return (is_file($path) || $filename == '/favicon.ico') ? true : false;
	}
	
	public static function getStaticFile($filename)
	{
		if( ! self::isStaticFile($filename))
			return false;
		
		$path = self::getAppFolder().DS.'static'.$filename;
		
		$fileViewer = new FileViewer($path);
		$fileViewer->display();
	}
	
	public static function setLangKey()
	{
		$langKey = self::getAppParam('lang_key');
		
		Config::set('AppLanguageKey', $langKey);
		return $langKey;
	}
	public static function getLangKey()
	{
		if( ! $langKey = Config::get('AppLanguageKey'))
			$langKey = self::setLangKey(self::getAppParam('lang_key'));
		
		return $langKey;
	}
	
	public static function setLang($lang)
	{
		if( ! in_array($lang, self::getLangs()))
			$lang = self::getAppParam('language');
		
		Config::set('AppLanguage', $lang);
		return $lang;
	}
	public static function getLang()
	{
		if( ! $lang = Config::get('AppLanguage'))
			$lang = self::setLang(self::getAppParam('language'));
		
		return $lang;
	}
	public static function getLangs()
	{
		return self::getAppParam('languages');
	}
	
	public static function setModule($module, $showError = false)
	{
		try 
		{
			$path = self::getAppFolder().DS
				.'modules'.DS
				.$module;

			if( ! is_dir($path))
			{
				if($showError)
					throw new CoreException('Application has no default module');
				
				return self::setModule(self::getAppParam('module'), true);
			}

			Config::set('AppModule', $module);
			return $module;
		}
		catch(CoreException $exception)
		{
			$exception->display();
		}
	}
	public static function getModule()
	{
		if( ! $module = Config::get('AppModule'))
			$module = self::setModule(self::getAppParam('module'));
		
		return $module;
	}
	
	private static function checkController($controller)
	{
		$tokens = explode('_', $controller);
		if(count($tokens) > 1)
		{
			foreach($tokens as $key => $token)
				$tokens[$key] = ucfirst($token);

			$controller = implode('', $tokens);
		}
		
		return ucfirst($controller);
	}
	public static function setController($controller, $showError = false)
	{
		try
		{
			$controller = self::checkController($controller);
			
			$path = self::getAppFolder().DS
				.'modules'.DS
				.self::getModule().DS
				.'controllers';
			
			if( ! is_file($path.DS.ucfirst($controller).'.php'))
			{
				if($showError)
					throw new CoreException('Application has no default controller');
				
				return self::setController(self::getAppParam('controller'), true);
			}
			
			Config::set('AppController', $controller);
			return $controller;
		}
		catch(CoreException $exception)
		{
			$exception->display();
		}
	}
	public static function getController()
	{
		if( ! $controller = Config::get('AppController'))
			$controller = self::setController(self::getAppParam('controller'));
		
		return $controller;
	}
	
	private static function checkMethod($method)
	{
		$tokens = explode('_', $method);
		if(count($tokens) > 1)
		{
			$cnt = 0;
			foreach($tokens as $key => $token)
			{
				if($cnt > 0)
					$tokens[$key] = ucfirst($token);
				
				$cnt++;
			}

			$method = implode('', $tokens);
		}
		
		return $method;
	}
	public static function setMethod($method)
	{
		$method = self::checkMethod($method);
		
		Config::set('AppMethod', $method);
		return $method;
	}
	public static function getMethod()
	{
		if( ! $method = Config::get('AppMethod'))
			$method = self::setMethod(self::getAppParam('method'));
		
		return Config::get('AppMethod');
	}
	
	public static function getArgs()
	{
		@list($lang, $module, $controller, $method) = $tokens = array_splice(explode('/', Uri::getUrn(array('no-params' => true))), 1);
		
		$from = 0;
		
		if(self::getLang() == $lang)
			$from++;
		else
			@list($module, $controller, $method) = array($lang, $module, $controller);
		
		if(self::getModule() == $module)
			$from++;
		else
			@list($controller, $method) = array($module, $controller);
		
		if(self::getController() == self::checkController($controller))
			$from++;
		else
			$method = $controller;
		
		if(self::getMethod() == self::checkMethod($method))
			$from++;
		
		foreach($tokens as $tokenKey => $tokenValue)
		{
			if(empty($tokens[$tokenKey]))
				unset($tokens[$tokenKey]);
		}
		
		return array_splice($tokens, $from);
	}
	
	public static function route()
	{
		$__subdomains = [];
		if(is_file(self::getAppFolder().DS.'subdomains.php'))
			$__subdomains = include self::getAppFolder().DS.'subdomains.php';

		@list($lang, $module, $controller, $method) = array_splice(explode('/', Uri::getUrn(array('no-params' => true))), 1);

		if(self::setLang($lang) != $lang)
		{
			@list($module, $controller, $method) = array($lang, $module, $controller);
			if(Session::get('language', '') != '')
				self::setLang(Session::get('language'));
		}
		
		Session::set('language', $lang = self::getLang());

		foreach($__subdomains as $__subdomain => $__dependency)
		{
			if($__subdomain != Uri::getUrl())
				continue;

			foreach($__dependency as $__token => $__value)
			{
				if($__token == 'module')
					$controller = $module;
				elseif($__token == 'controller')
					$method = $controller;

				$$__token = $__value;
			}
		}

		if(self::setModule($module) != $module)
		{
			self::setModule('errors');
			@list($controller, $method) = array($module, $controller);
		}
		
		if(self::setController($controller) != self::checkController($controller))
			$method = $controller;

		self::setMethod($method);
	}
}

?>
