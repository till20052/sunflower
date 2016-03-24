<?php

class VkLocationsClass
{
	private static $__instance;
	private $__hostname = "vk.com";
	private $__script = "/select_ajax.php";
	private $__port = 80;
	private $__timeout = 30;
	private $__referer;
	
	private function __sendRequestToVk($request = array(), $options = array())
	{
		$__handle = fsockopen($this->__hostname, $this->__port, $__errno, $__errstr, $this->__timeout);
		
		if( ! $__handle)
			return array("success" => false, "errno" => $__errno, "errstr" => $__errstr);
		
		$request["lang"] = Router::getLang();
		$request["basic"] = 0;
		
		$request = http_build_query($request);
		
		fputs($__handle, "POST ".$this->__script." HTTP/1.0\r\n");
		fputs($__handle, "Host: ".$this->__hostname."\r\n");
		
		if(isset($this->__referer) && is_string($this->__referer) && $this->__referer != "")
			fputs($__handle, "Referer: ".$this->__referer."\r\n");
		
		fputs($__handle, "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8");
		fputs($__handle, "Accept-Language: ru-RU,ru;q=0.8,en-US;q=0.6,en;q=0.4\r\n");
		fputs($__handle, "Accept-Charset: windows-1251,utf-8;q=0.7,*;q=0.3\r\n");
		fputs($__handle, "Content-type: application/x-www-form-urlencoded\r\n");
		fputs($__handle, "Content-length: ".strlen($request)."\r\n");
		fputs($__handle, "Connection: close\r\n\r\n");
		fputs($__handle, $request);
		
		$__response = "";
		
		while( ! feof($__handle))
			$__response .= fgets($__handle);
		
		fclose($__handle);
		
		$__response = explode("\r\n\r\n", $__response, 2);
		$__response = isset($__response[1]) ? $__response[1] : "";
		
		if(isset($options["iconv"]) && is_array($options["iconv"]))
			$__response = iconv($options["iconv"]["in"], $options["iconv"]["out"], $__response);
		
		if(isset($options["replace"]) && is_array($options["replace"]))
			$__response = str_replace($options["replace"]["search"], $options["replace"]["replace"], $__response);
		
		return array(
			"success" => true,
			"response" => json_decode($__response, true)
		);
	}
	
	public static function i()
	{
		if( ! isset(self::$__instance))
			self::$__instance = new VkLocationsClass();
		
		return self::$__instance;
	}

	public function getCountries()
	{
		$__list = array();
		
		$__data = $this->__sendRequestToVk(
				array("act" => "a_get_countries"),
				array("iconv" => array("in" => "windows-1251", "out" => "UTF-8"))
		);
		
		foreach($__data["response"]["countries"] as $__country)
			$__list[] = array(
				"id" => $__country[0],
				"name" => $__country[1]
			);
		
		return $__list;
	}

	public function getCitiesByCountryId($countryId)
	{
		$__list = array();
		
		$__data = $this->__sendRequestToVk(
				array("act" => "a_get_cities", "country" => $countryId),
				array("iconv" => array("in" => "windows-1251", "out" => "UTF-8"))
		);
		
		foreach($__data["response"]["cities"] as $__city)
			$__list[] = array(
				"id" => $__city[0],
				"name" => str_replace(array("<b>", "</b>"), "", $__city[1])
			);
		
		return $__list;
	}
	
	public function findSimilarCitiesByCountryId($string, $countryId)
	{
		$__list = array();
		
		$__data = $this->__sendRequestToVk(
				array(
					"act" => "a_get_cities",
					"country" => $countryId,
					"str" => $string != "" ? $string : "******"
				),
				array("replace" => array("search" => array("'", ",<br />"), "replace" => array("\"", ",")))
		);
		
		foreach($__data["response"] as $__item)
		{
			$__city = array(
				"id" => $__item[0],
				"name" => $__item[1]
			);
			
			if(isset($__item[2]))
				$__city["district"] = $__item[2];
			
			if(isset($__item[3]) &&  ! (intval($__item[3]) > 0))
				$__city["region"] = $__item[3];
				
			$__list[] = $__city;	
		}
		
		return $__list;
	}
}