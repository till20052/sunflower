<?php

Loader::loadSystem('Keeper');

interface DBInterface
{
	public static function exec($sql, $bind = array(), $dbKey = null);
	public static function getRow($sql, $bind = array(), $dbKey = null);
	public static function getRows($sql, $bind = array(), $dbKey = null);
	public static function getCols($sql, $bind = array(), $dbKey = null);
	public static function getLastId($dbKey = null);
}

abstract class DB extends Keeper implements DBInterface
{
	private static $connections = array();
	
	protected function __construct() {}
	
	public static function create($dbKey = null, $reconnect = false)
	{
		try
		{
			if(is_null($dbKey))
				$dbKey = Config::get('AppDBKey');
			
			if(isset(self::$connections[$dbKey]) && self::$connections[$dbKey] instanceof PDO && ! $reconnect)
				return self::$connections[$dbKey];

			$dbConfig = ($dbConfig = Config::get('AppDataBases')) && isset($dbConfig[$dbKey]) ? $dbConfig[$dbKey] : array();

			if(count($dbConfig) < 1)
				throw new CoreException('DB connection params for "' . $dbKey . '" not found in configuration');
			
			if ( ! isset($dbConfig['driver']))
				$dbConfig['driver'] = 'mysql';
			
			$dsn = $dbConfig['driver'].':host='.$dbConfig['hostname'];
			
			if(isset($dbConfig['port']))
				$dsn .= ';port='.$dbConfig['port'];

			if(isset($dbConfig['database']))
				$dsn .= ';dbname='.$dbConfig['database'];
			
			self::$connections[$dbKey] = new PDO($dsn, $dbConfig['username'], $dbConfig['password']);
			
			if($dbConfig['driver'] == 'mysql')
				self::$connections[$dbKey]->prepare('SET NAMES utf8')->execute();
			
			return self::$connections[$dbKey];
		}
		catch(CoreException $exception)
		{
			$exception->display();
		}
	}
	
	public static function exec($sql, $bind = array(), $dbKey = null)
	{
		try
		{
			$statement = self::create($dbKey)->prepare($sql);
			
//			$handle = fopen("/var/www/framework/logs/1.log", "a+");
//			if($handle)
//			{
//				fwrite($handle, $sql."\n");
//				fclose($handle);
//			}
			
			foreach ($bind as $key => $value)
				$statement->bindValue(':'.$key, $value, self::getBindType($value));

			$statement->execute();

			if($statement->errorCode() != '0000')
			{
				$error = $statement->errorInfo();
				throw new CoreException($error[2], $error[1]);
			}

			return $statement;
		}
		catch(CoreException $exception)
		{
			$exception->display();
		}
	}
	
	public static function getRow($sql, $bind = array(), $dbKey = null)
	{
		return self::exec($sql, $bind, $dbKey)->fetch(PDO::FETCH_ASSOC);
	}
	
	public static function getRows($sql, $bind = array(), $dbKey = null)
	{
		return self::exec($sql, $bind, $dbKey)->fetchAll(PDO::FETCH_ASSOC);
	}
	
	public static function getCols($sql, $bind = array(), $dbKey = null)
	{
		return self::exec($sql, $bind, $dbKey)->fetchAll(PDO::FETCH_COLUMN);
	}
	
	public static function getLastId($dbKey = null)
	{
		return self::create($dbKey)->lastInsertId();
	}
	
	/* PRIVATE METHODS */
	private static function getBindType($value)
	{
		if(is_int($value))
			return PDO::PARAM_INT;

		if(is_bool($value))
			return PDO::PARAM_BOOL;

		return PDO::PARAM_STR;
	}
}

?>
