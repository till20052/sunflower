<?php

Loader::loadSystem('Model');

class MenuModel extends Model
{
	protected $table = 'menu';
	
	public static function instance()
	{
		return parent::instance('MenuModel');
	}
	
	public function getItem($pkey, $fields = array())
	{
		if( ! ($item = parent::getItem($pkey, $fields)))
			return false;
		
		$item['title'] = stripslashes($item['title']);
		
		return $item;
	}
}
