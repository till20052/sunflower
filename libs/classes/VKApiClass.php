<?php

Loader::loadClass("ExtendedClass", Loader::SYSTEM);

class VKApiClass extends ExtendedClass
{
	private $__url = "https://api.vk.com/method";
	private $__lang;
	private $__apiVersion = "5.5";
	
	private function __sendRequest($method, $data = array())
	{
		$__data = $data;
		
		if( ! isset($this->__lang))
			$this->__lang = Router::getLang();
		
		if( ! isset($__data["lang"]))
			$__data["lang"] = $this->__lang;
		
		if( ! isset($__data["v"]))
			$__data["v"] = $this->__apiVersion;
		
		$__url = $this->__url
				."/".$method
				."?".http_build_query($__data);
		
		$__rsp = json_decode(file_get_contents($__url), true);
		
		return $__rsp["response"];
	}
	
	/**
	 * 
	 * @param string $instance
	 * @return VKApiClass
	 */
	public static function i($instance = "VKApiClass")
	{
		return parent::i($instance);
	}
	
	public function apiVersion($apiVersion = null)
	{
		if( ! is_null($apiVersion))
			$this->__apiVersion = $apiVersion;
		
		return $this->__apiVersion;
	}
	
	public function lang($lang = null)
	{
		if( ! is_null($lang))
			$this->__lang = $lang;
		
		return $this->__lang;
	}
	
	public function getCountries($needAll = 1, $code = "")
	{
		return $this->__sendRequest("database.getCountries", array(
			"need_all" => $needAll,
			"code" => $code
		));
	}
	
	public function getCountriesById($countryIds)
	{
		return $this->__sendRequest("database.getCountriesById", array(
			"country_ids" => $countryIds
		));
	}
	
	public function getCountryById($countryId)
	{
		$rsp = $this->getCountriesById($countryId);
		return isset($rsp[0]) ? $rsp[0] : array();
	}
	
	public function getRegions($countryId, $q = "")
	{
		$__rsp = $this->__sendRequest("database.getRegions", array(
			"country_id" => $countryId,
			"q" => $q
		));
		
		return $__rsp["items"];
	}
	
	public function getCities($countryId, $q = "", $regionId = null, $needAll = 1)
	{
		$__data = array(
			"country_id" => $countryId,
			"need_all" => $needAll,
			"count" => 1000
		);
		
		if($q != "")
			$__data["q"] = $q;
		
		if( ! is_null($regionId))
			$__data["region_id"] = $regionId;
		
		$__rsp = $this->__sendRequest("database.getCities", $__data);
		
		return $__rsp["items"];
	}
	
	public function getCitiesById($cityIds)
	{
		return $this->__sendRequest("database.getCitiesById", array(
			"city_ids" => $cityIds
		));
	}
	
	public function getCityById($cityId)
	{
		$rsp = $this->getCitiesById($cityId);
		return isset($rsp[0]) ? $rsp[0] : array();
	}
	
	public function getUser($userId, $fields = array())
	{
		return $this->__sendRequest("users.get", array(
			"user_ids" => $userId,
			"fields" => implode(",", $fields)
		));
	}
}
