<?php

Loader::loadSystem('Keeper');

class Config extends Keeper
{
	public static function set($key, $value)
	{
		parent::setRegistry($key, $value);
	}
	public static function get($key, $default = null)
	{
		return parent::getRegistry($key, $default);
	}
	public static function delete($key)
	{
		parent::delRegistry($key);
	}
}

?>
