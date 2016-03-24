<?php

Loader::loadSystem('viewer/Viewer');
Loader::loadLib('functions.mime', Loader::SYSTEM);

class FileViewer extends Viewer
{
	protected $fileName;
	
	private function __getRequestHeaders()
	{
		if(function_exists("apache_request_headers"))
		{
			if($__headers = apache_request_headers())
				return $__headers;
		}
		
		$__headers = array();
		
		if(isset($_SERVER["HTTP_IF_MODIFIED_SINCE"]))
			$__headers["If-Modified-Since"] = $_SERVER["HTTP_IF_MODIFIED_SINCE"];
		
		return $__headers;
	}
	
	public function __construct($fileName)
	{
		parent::__construct();
		$this->fileName = $fileName;
	}
	
	public function display()
	{
		$__fPath = $this->fileName;
		
		if( ! file_exists($__fPath))
			exit(0);
		
		$__headers = $this->__getRequestHeaders();
		$__fModTime = filemtime($__fPath);
		
		header("Cache-Control: public");
		
		if(
				isset($__headers["If-Modified-Since"])
				&& (strtotime($__headers["If-Modified-Since"]) == $__fModTime)
		){
			header("Last-Modified: ".gmdate("D, d M Y H:i:s", $__fModTime)." GMT", true, 304);
			exit(0);
		}
		
		header("Last-Modified: ".gmdate("D, d M Y H:i:s", $__fModTime)." GMT", true, 200);
		header("Content-type: ".mimeContentType($__fPath));
		header("Content-length: ".filesize($__fPath));
		readfile($__fPath);
		
		exit(0);
	}
}

?>
