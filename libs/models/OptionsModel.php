<?php

Loader::loadModel('SystemModel', Loader::SYSTEM);

class OptionsModel extends SystemModel
{
	protected $table = 'apps_options';
	protected $pkey = 'app_id';
	
	public static function instance()
	{
		return parent::instance('OptionsModel');
	}
}
