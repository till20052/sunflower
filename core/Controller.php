<?php

Loader::loadSystem('Keeper');

class Controller extends Keeper
{
	private $layout;
	private $viewer = 'html';
	private $view;
	
	protected $fileName;

	protected $json = array();
	
	public function getJson()
	{
		return $this->json;
	}
	
	public function getFileName()
	{
		return $this->fileName;
	}
	
	public function assign($key, $value)
	{
		parent::$variables[$key] = $value;
	}
	
	public function __set($key, $value)
	{
		$this->assign($key, $value);
	}
	
	public function &__get($key)
	{
		if( ! isset(parent::$variables[$key]))
			return $default;
		
		return parent::$variables[$key];
	}
	
	public function setViewer($value)
	{
		if(in_array($value, array(null, 'html', 'json', 'file')))
			$this->viewer = $value;
	}
	public function getViewer()
	{
		return $this->viewer;
	}
	
	public function setView($value)
	{
		$this->view = $value;
	}
	public function getView()
	{
		return $this->view;
	}
	
	public function setLayout($value)
	{
		$this->layout = $value;
	}
	public function getLayout()
	{
		return $this->layout;
	}
	
	public function redirect($url)
	{
		header('Location: '.$url);
		exit(0);
	}
	
	public function execute()
	{
		$this->view = strtolower(Router::getController());
	}
}

?>
