<?php

Loader::loadSystem('viewer/Viewer');

class JsonViewer extends Viewer
{
	protected $json;
	
	public function __construct($json)
	{
		parent::__construct();
		$this->json = $json;
	}
	
	public function display()
	{
		header('Content-type: text/javascript');
		echo json_encode($this->json);
	}
}

?>
