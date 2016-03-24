<?php

Loader::loadSystem('viewer/Viewer');
Loader::loadLib('classes.HeadClass');

class HtmlViewer extends Viewer
{
	protected $layout = 'frontend';
	protected $view;
	protected $withoutLayout = false;
	
	public function __construct($options)
	{
		parent::__construct();
		
		if(isset($options['layout']) && $options['layout'] != '')
			$this->layout = $options['layout'];
		
		if(isset($options['view']) && $options['view'] != '')
			$this->view = $options['view'];
		else
			$this->view = strtolower(Router::getController());
		
		if(isset($options['without-layout']) && $options['without-layout'] != false)
		{
			$this->withoutLayout = $options['without-layout'];
		}
	}
	
	public function display()
	{
		try
		{
			$path = Router::getAppFolder().DS.'templates'.DS.$this->layout.'.php';
			if( ! is_file($path))
				throw new CoreException('Template file doesn\'t exists: '.$path);
			
			$view = Router::getAppFolder().DS.'modules'.DS.Router::getModule().DS.'views'.DS.$this->view.'.php';
			if( ! is_file($view))
				throw new CoreException('View file doesn\'t exists: '.$view);
			
			if($this->withoutLayout)
			{
				$path = $view;
			}
			
			extract(parent::$variables);
			
			include $path;
		}
		catch(CoreException $exception)
		{
			$exception->display();
		}
	}
}

?>
