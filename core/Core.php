<?php

Loader::loadSystem('Console');
Loader::loadSystem('Config');
Loader::load(CONFIG.'.configuration');
Loader::loadSystem('Session');
Loader::loadSystem('Router');
Loader::loadModel('ConfigModel', Loader::SYSTEM);
Loader::loadModel('TranslationModel', Loader::SYSTEM);
Loader::loadSystem('viewer.*');
Loader::loadSystem('Request');

class Core
{
	public function initialize()
	{
		Session::start();
		
		ConfigModel::setSystemConfiguration();
		
		Config::set('Configuration', array_merge(Config::get('Configuration'), ConfigModel::instance()->getItems()));
		Config::set('Translations', TranslationModel::instance()->getItems());

		Router::setAppKey(null);
		
		if(Router::isStaticFile(Uri::getUrn(array('no-params' => true))))
		{
			Router::getStaticFile(Uri::getUrn(array('no-params' => true)));
		}
		
		Router::route();
		
		ConfigModel::setAppConfiguration();
	}
	
	public function execute()
	{
		TranslationModel::instance()->convert();
		
		$_controller = Router::getController();
		
		Loader::loadController($_controller);
		
		$_module = Router::getModule();
		$_method = Router::getMethod();
		
		$_CLASS = $_controller.ucfirst($_module).'Controller';
		
		$controller = new $_CLASS();
		
		$methods = get_class_methods($controller);
		if(in_array($_method, $methods) && $_method != 'execute')
		{
			Router::setMethod($_method);
			$result = $controller->$_method(Router::getArgs());
		}
		else
		{
			Router::setMethod('execute');
			$args = array_merge(Router::getArgs());
			$result = $controller->execute($args);
		}
		
		if(is_null($controller->getViewer()))
			exit();
		
		switch($controller->getViewer())
		{
			case 'file':
				$viewer = new FileViewer($controller->getFileName());
				break;
			
			case 'json':
				$viewer = new JsonViewer(array_merge(array("success" => $result), $controller->getJson()));
				break;
			
			case 'html':
			default:
				$options = array(
					'layout' => $controller->getLayout(),
					'view' => $controller->getView(),
					'without-layout' => $controller->getLayout() !== false ? false : true
				);
				$viewer = new HtmlViewer($options);
				break;
		}
		
		$viewer->display();
	}
}

?>
