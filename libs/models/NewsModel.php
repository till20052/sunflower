<?php

Loader::loadSystem('Model');

class NewsModel extends Model
{
	protected $table = 'news';
	
	public static function instance()
	{
		return parent::instance('NewsModel');
	}
	
	public function getItem($pkey, $options = array())
	{
		if( ! $item = parent::getItem($pkey))
		{
			return false;
		}
		
		if( ! isset($options['format']))
			$options['format'] = 'd.m.Y';
		
		$fields = array('title', 'description', 'text');
		foreach($fields as $field)
		{
			$item[$field] = unserialize($item[$field]);
			if( ! is_array($item[$field]))
				$item[$field] = array();
		}
		
		$fields = array('created', 'modified');
		foreach($item as $key => $value)
		{
			if(in_array($key, $fields))
			{
				$time = strtotime($value);
				$item[$key] = date($options['format'], $time);
			}
		}
		
		return $item;
	}
	
	public function insert($data, $ignoreDubplicate = false)
	{
		$fields = array('title', 'description', 'text');
		foreach($fields as $field)
		{
			if(isset($data[$field]) && is_array($data[$field]))
				$data[$field] = serialize($data[$field]);
			else
				$data[$field] = serialize(array());
		}
		
		if( ! isset($data['modified']))
			$data['modified'] = date('Y-m-d h:i:s');
		
		return parent::insert($data, $ignoreDubplicate);
	}
	
	public function update($data, $where = array())
	{
		$fields = array('title', 'description', 'text');
		foreach($fields as $field)
		{
			if(isset($data[$field]) && is_array($data[$field]))
				$data[$field] = serialize($data[$field]);
		}
		
		if( ! isset($data['modified']))
			$data['modified'] = date('Y-m-d H:i:s');
		
		parent::update($data, $where);
	}
}
