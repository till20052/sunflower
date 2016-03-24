<?php

if( ! function_exists('t'))
{
	function t($key)
	{
		$translations = Config::get('Translations');
		
		$langKey = Router::getLangKey();
		$lang = Router::getLang();
		
		$exists = false;
		
		if(isset($translations[$key]) && is_array($translations[$key]))
		{
			$exists = true;
			if($translations[$key][$lang] != '')
				return $translations[$key][$lang];
		}
		
		if( ! $exists)
		{
			$langs = Router::getLangs();
			
			$data = array();
			foreach($langs as $item)
			{
				$data[$item] = '';
				if($item = $langKey)
					$data[$item] = $key;
			}
			
			TranslationModel::instance()->insert($data);
		}
		
		return $key;
	}
}

