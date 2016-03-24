<?php

Loader::loadModel('SystemModel', Loader::SYSTEM);

class ConfigModel extends SystemModel
{
	protected $pkey = 'id';
	protected $table = 'apps';
	
	public static function instance()
	{
		return parent::instance('ConfigModel');
	}
	
	public function getItem($pkey, $fields = array())
	{
		$app = parent::getItem($pkey);
		$app['path'] = APPS.DS.$app['folder'];
		
		$this->table = 'apps_db';
		$appsDbList = parent::getList(array('app_id = '.$pkey));
		$appsDb = array('databases' => array());
		foreach($appsDbList as $id)
		{
			$item = parent::getItem($id);
			$appsDb['databases'][$item['db_key']] = $item;
		}
		
		$this->table = 'apps_db';
		
		$this->table = 'apps_langs';
		$appsLangsList = parent::getList(array('app_id = '.$pkey));
		$appsLangs = array('languages' => array());
		foreach($appsLangsList as $id)
		{
			$item = parent::getItem($id);
			$appsLangs['languages'][] = $item['language'];
		}
		
		$this->table = 'apps_options';
		$appsOptionsList = parent::getList(array('app_id = '.$pkey));
		$appsOptions = array('options' => array());
		foreach($appsOptionsList as $id)
		{
			$item = parent::getItem($id);
			$appsOptions['options'][$item['key']] = $item['value'];
		}
		
		$this->table = 'apps';
		
		return array_merge($app, $appsDb, $appsLangs, $appsOptions);
	}
	
	public function getItems($where = array(), $bind = array(), $order = array(), $limit = null)
	{
		$list = parent::getList($where, $bind, $order, $limit);
		
		$result = array();
		foreach($list as $id)
		{
			$item = $this->getItem($id);
			$result[$item['key']] = $item;
		}
		
		return $result;
	}
}
