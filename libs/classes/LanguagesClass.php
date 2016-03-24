<?php

class LanguagesClass
{
	public static $languages = array(
		'ru' => 'Русский',
		'ua' => 'Украинский',
		'en' => 'Английский'
	);
	
	public static function getLang($langKey)
	{
		return self::$languages[$langKey];
	}
}
