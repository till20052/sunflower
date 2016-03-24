<?php

Loader::loadSystem('Model');

class UsersModel extends Model
{
	protected $table = 'users';
	
	public static function instance($peer = 'UsersModel')
	{
		return parent::instance($peer);
	}
	
	public static function getName($item, $tpl = "&fn &ln")
	{
		$data = array(
			'fn' => $item['first_name'],
			'mn' => $item['middle_name'],
			'ln' => $item['last_name']
		);
		
		foreach($data as $key => $val)
			$tpl = str_replace("&".$key, $val, $tpl);
		
		return $tpl;
	}
	
	public function insert($data, $ignoreDubplicate = false)
	{
		if(isset($data['password']))
			$data['password'] = md5($data['password']);
		
		return parent::insert($data, $ignoreDubplicate);
	}
	
	public function update($data, $where = array())
	{
		if(isset($data['password']))
			$data['password'] = md5($data['password']);
		
		return parent::update($data, $where);
	}
}
