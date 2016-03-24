<?php

class DateClass
{
	private static $months = array(
		'Января',
		'Февраля',
		'Марта',
		'Апреля',
		'Мая',
		'Июня',
		'Июля',
		'Августа',
		'Сентября',
		'Октября',
		'Ноября',
		'Декабря'
	);
	
	private static $qtime = 62135337601;
	
	public static function getMonth($id)
	{
		return t(self::$months[$id - 1]);
	}
	
	public static function getMonthByTime($time = false)
	{
		if( ! $time)
			$time = time();
		
		$monthIndex = (int) date('m', $time);
		
		return self::getMonth($monthIndex);
	}
	
	public static function QTimeDecode($time)
	{
		return ($time - self::$qtime);
	}
	
	public static function QTimeEncode($time)
	{
		return ($time + self::$qtime);
	}
}

