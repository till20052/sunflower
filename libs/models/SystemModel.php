<?php

Loader::loadSystem('Model');
Loader::loadSystem('Router');

abstract class SystemModel extends Model
{
	protected static $application = null;
	
	public static function refreshDBConfiguration()
	{
		Config::set('AppDBKey', Router::getAppParam('db_key'));
		Config::set('AppDataBases', Router::getAppParam('databases'));
		
		foreach(Router::getAppParam('databases') as $dbKey => $database)
		{
			DB::create($dbKey, true);
		}
	}
	
	public static function setSystemConfiguration()
	{
		Router::setAppKey('GLOBAL');
		self::refreshDBConfiguration();
	}
	
	public static function setAppConfiguration()
	{
		if(is_null(self::$application))
			self::$application = Router::getAppKey();
		
		Router::setAppKey(self::$application);
		self::refreshDBConfiguration();
	}
}
