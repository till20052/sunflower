<?php

Loader::loadSystem('Model');
Loader::loadLib('classes.LanguagesClass');
Loader::loadLib('functions.translate');

class TranslationModel extends Model implements ModelInterface
{
	protected $table = 'translations';
	
	public static function instance()
	{
		return parent::instance('TranslationModel');
	}
	
	public function getItems($where = array(), $bind = array(), $order = array(), $limit = null)
	{
		$list = parent::getList($where, $bind, $order, $limit);
		
		$arr = array();
		foreach($list as $id)
		{
			$arr[] = parent::getItem($id);
		}
		
		return $arr;
	}
	
	public function convert()
	{
		if( ! $translations = Config::get('Translations'))
			$translations = array();
		
		$translations = array_merge($translations, $this->getItems());
		
		$langKey = Router::getLangKey();
		
		$_translations = array();
		foreach($translations as $translation)
		{
			$_translations[$translation[$langKey]] = $translation;
		}
		
		Config::set('Translations', $_translations);
	}
}
