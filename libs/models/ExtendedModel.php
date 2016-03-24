<?php

Loader::loadSystem("Model");

class ExtendedModel extends Model
{
	protected $_dateFormat = "d.m.Y";
	protected $_specificFields = array(
			"serialized" => array(),
			"date" => array(),
			"md5" => array()
	);
	
	private function __encodeItem($item, $force = false)
	{
		foreach($this->_specificFields as $__type => $__fields)
		{
			foreach($__fields as $__field)
			{
				$__flag = false;
				
				$__tokens = explode(":", $__field);
				
				if(isset($__tokens[1]) && $__tokens[1] == "force")
				{
					$__field = $__tokens[0];
					$__flag = true;
				}
				
				if( ! isset($item[$__field]) && ! $force && ! $__flag)
					continue;
				
				if($__type == "serialized")
					$item[$__field] = serialize(isset($item[$__field])
							? $item[$__field]
							: array());
				
				if($__type == "date")
					$item[$__field] = date("Y-m-d H:i:s", isset($item[$__field])
							? strtotime($item[$__field])
							: time());
				
				if($__type == "md5")
					$item[$__field] = md5(isset($item[$__field])
							? $item[$__field]
							: "");
			}
		}
		
		return $item;
	}
	
	private function __decodeItem($item)
	{
		foreach($this->_specificFields as $__type => $__fields)
		{
			foreach($__fields as $__field)
			{
				$__tokens = explode(":", $__field);
				
				if(count($__tokens) > 1)
					$__field = $__tokens[0];
				
				if( ! isset($item[$__field]))
					continue;
				
				if($__type == "serialized")
					$item[$__field] = @unserialize($item[$__field]);
				
				if($__type == "date")
					$item[$__field] = date($this->_dateFormat, strtotime($item[$__field]));
			}
		}
		
		return $item;
	}
	
	/**
	 * @param string $instance
	 * @return ExtendedModel
	 */
	public static function i($instance)
	{
		return parent::instance($instance);
	}

	public function getItem($pkey, $fields = array())
	{
		if(isset($fields["date_format"]))
		{
			$this->_dateFormat = $fields["date_format"];
			unset($fields["date_format"]);
		}
		
		if( ! ($__item = parent::getItem($pkey, $fields)))
			return $__item;
		
		return $this->__decodeItem($__item);
	}
	
	public function getItemByField($field, $value, $options = array())
	{
		$__pkey = $this->pkey;
		$this->pkey = $field;
		
		$__fields = array();
		if(isset($options["fields"]) && is_array($options["fields"]))
			$__fields = $options["fields"];
		
		$__item = $this->getItem($value, $__fields);
		
		$this->pkey = $__pkey;
		
		return $__item;
	}
	
	public function getListByField($field, $value, $options = array())
	{
		$__cond = array($field." = :".$field);
		$__bind = array(
			$field => $value
		);
		
		if(isset($options["cond"]) && is_array($options["cond"]))
			$__cond = array_merge($__cond, $options["cond"]);
		
		if(isset($options["bind"]) && is_array($options["bind"]))
			$__bind = array_merge($__bind, $options["bind"]);
		
		$__order = array();
		if(isset($options["order"]) && is_array($options["order"]))
			$__order = $options["order"];
		
		return $this->getList($__cond, $__bind, $__order, isset($options["limit"]) ? $options["limit"] : null);
	}
	
	public function getCompiledList($where = array(), $bind = array(), $order = array(), $limit = null)
	{
		$__list = array();
		
		foreach(parent::getList($where, $bind, $order, $limit) as $__id)
			$__list[] = $this->getItem($__id);
		
		return $__list;
	}
	
	public function getCompiledListByField($field, $value, $options = array())
	{
		$__list = array();
		
		foreach($this->getListByField($field, $value, $options) as $__id)
			$__list[] = $this->getItem($__id, isset($options["fields"]) ? $options["fields"] : array());
		
		return $__list;
	}

	public function insert($data, $ignoreDubplicate = false)
	{
		return parent::insert($this->__encodeItem($data, true), $ignoreDubplicate);
	}
	
	public function update($data, $where = array())
	{
		return parent::update($this->__encodeItem($data), $where)->rowCount();
	}
}
