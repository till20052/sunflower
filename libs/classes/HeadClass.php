<?php

Loader::loadSystem('Keeper');

class HeadClass extends Keeper
{
	protected static $js = array();
	public static function addJs($src)
	{
		if(is_array($src))
			self::$js = array_merge(self::$js, $src);
		else
			self::$js[] = $src;
	}
	public static function getJs()
	{
		$__list = array();
		
		foreach(self::$js as $__js)
		{
			if(in_array($__js, $__list))
				continue;
			
			$__list[] = $__js;
		}
		
		return $__list;
	}
	
	protected static $css = array();
	public static function addCss($href)
	{
		if(is_array($href))
			self::$css = array_merge(self::$css, $href);
		else
			self::$css[] = $href;
	}
	public static function getCss()
	{
		$__list = array();
		
		foreach(self::$css as $__css)
		{
			if(in_array($__css, $__list))
				continue;
			
			$__list[] = $__css;
		}
		
		return $__list;
	}
	
	protected static $less = array();
	public static function addLess($href)
	{
		if(is_array($href))
			self::$less = array_merge(self::$less, $href);
		else
			self::$less[] = $href;
	}
	public static function getLess()
	{
		$__list = array();
		
		foreach(self::$less as $__less)
		{
			if(in_array($__less, $__list))
				continue;
			
			$__list[] = $__less;
		}
		
		return $__list;
	}
}
