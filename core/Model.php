<?php

Loader::loadSystem('DB');

interface ModelInterface
{
//	public static function instance($model);
	public function insert($data, $ignoreDubplicate = false);
	public function update($data, $where = array());
	public function getItem($pkey, $fields = array());
	public function getList($where = array(), $bind = array(), $order = array(), $limit = null);
	public function deleteItem($pkey);
}

abstract class Model extends DB implements ModelInterface
{
	protected static $instances = array();
	protected $pkey = 'id';
	protected $table;
	protected $dbKey = null;
	
	/**
	 * @param string $peer
	 * @return Model
	 */
	public static function instance($peer)
	{
		if( ! isset(self::$instances[$peer]))
		{
			self::$instances[$peer] = new $peer;
		}
		
		return self::$instances[$peer];
	}
	
	public function insert($data, $ignoreDubplicate = false)
	{
		$insertData = array();
		$insertColumns = array();
		foreach($data as $column => $value)
		{
			$insertData[] = ":{$column}";
			$insertColumns[] = '`'.$column.'`';
		}
		
		$sql = 'INSERT '.($ignoreDubplicate ? ' IGNORE ' : '').' INTO '.$this->table
				.' ('.implode(', ', $insertColumns).') VALUES ('
				.implode(', ', $insertData).')';
		
		parent::exec($sql, $data, $this->dbKey);
		
		return parent::getLastId($this->dbKey);
	}
	
	public function update($data, $where = array())
	{
		$_where = '';
		if(count($where) > 0)
			$_where = 'WHERE '.$this->convCondition($where).' ';
		else
			$_where = 'WHERE `'.$this->pkey.'` = :'.$this->pkey;

		$_data = array();
		foreach($data as $column => $value)
		{
			$_data[] = "`{$column}` = :{$column}";
		}
		
		$sql = 'UPDATE '.$this->table.' SET '.implode(', ', $_data).' '.$_where;
		
		return parent::exec($sql, $data, $this->dbKey);
	}
	
	public function getItem($pkey, $fields = array())
	{
		$_fields = '*';
		if(count($fields) > 0)
			$_fields = implode(', ', $fields);
		
		$sql = 'SELECT '.$_fields.' FROM '.$this->table.' WHERE `'.$this->pkey.'` = :pkey;';
		$bind = array('pkey' => $pkey);
		
		return parent::getRow($sql, $bind, $this->dbKey);
	}
	
	public function getList($where = array(), $bind = array(), $order = array(), $limit = null)
	{
		$_where = '';
		if(count($where) > 0)
			$_where = 'WHERE '.$this->convCondition($where).' ';
		
		$_order = '';
		if(count($order) > 0)
			$_order = 'ORDER BY '.implode (', ', $order).' ';
		
		$_limit = '';
		if( ! is_null($limit))
			$_limit = 'LIMIT '.(int) $limit;
		
		$sql = 'SELECT '.$this->pkey.' FROM '.$this->table.' '.$_where.$_order.$_limit.';';

		return parent::getCols($sql, $bind, $this->dbKey);
	}
	
	public function deleteItem($pkey)
	{
		$sql = 'DELETE FROM '.$this->table.' WHERE '.$this->pkey.' = :pkey LIMIT 1;';
		$bind = array('pkey' => $pkey);
		
		return parent::exec($sql, $bind, $this->dbKey);
	}
	
	/* PRIVATE METHODS */
	private function convCondition($condArr, $key = "AND")
	{
		if( ! is_array($condArr))
			return '';
		
		if( ! in_array($key, array("AND", "OR")))
			$key = "AND";
		
		$cond = array();
		foreach($condArr as $condOper => $condItem)
		{
			if(is_array($condItem))
			{
				$condItem = '('.$this->convCondition($condItem, $condOper).')';
			}
			
			$cond[] = $condItem;
		}
		
		$cond = implode(' '.$key.' ', $cond);
		
		return $cond;
	}
}

?>
