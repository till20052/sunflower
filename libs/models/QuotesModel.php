<?php

Loader::loadSystem('Model');

class QuotesModel extends Model
{
	protected $table = 'quotes';
	
	public static function instance()
	{
		return parent::instance('QuotesModel');
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
		foreach($item as $key => $value)
		{
			if(in_array($key, $fields))
			{
				$item[$key] = unserialize($value);
			}
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
		foreach($data as $key => $value)
		{
			if(in_array($key, $fields))
			{
				$data[$key] = serialize($value);
			}
		}
		
		if( ! isset($data['modified']))
			$data['modified'] = date('Y-m-d h:i:s');
		
		return parent::insert($data, $ignoreDubplicate);
	}
	
	public function update($data, $where = array())
	{
		$fields = array('title', 'description', 'text');
		foreach($data as $key => $value)
		{
			if(in_array($key, $fields))
			{
				$data[$key] = serialize($value);
			}
		}
		
		if( ! isset($data['modified']))
			$data['modified'] = date('Y-m-d h:i:s');
		
		parent::update($data, $where);
	}
}
