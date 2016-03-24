<?php

class CoreException extends Exception
{
	private $template = 'templates/default.php';
	
	public function display()
	{
		$code = $this->code;
		$error_msg = $this->getMessage();
		$_trace = $this->getTrace();
		
		$cnt = 0;
		$trace = array();
		foreach($_trace as $item)
		{
			$class = '';
			if(isset($item['class']))
				$class = $item['class'];
			
			$type = '';
			if(isset($item['type']))
				$type = $item['type'];
			
			$trace[] = array(
				'num' => count($_trace) - $cnt,
				'file' => $item['file'],
				'call' => $class.$type.$item['function'],
				'line' => $item['line']
			);
			
			$trace[count($trace)-1]['args'] = array();
			for($i = 0; $i < count($item['args']); $i++)
			{
				$arg = '';
				if(is_string($item['args'][$i]))
					$arg = '\''.$item['args'][$i].'\'';
				else
					$arg = $item['args'][$i];
				
				$trace[count($trace)-1]['args'][] = $arg;
			}
			
			$trace[count($trace)-1]['args'] = implode(', ', $trace[count($trace)-1]['args']);
			
			$cnt++;
		}
		
		usort($trace, array('CoreException', 'compare'));
		
		include $this->template;
		exit(1);
	}
	
	public static function compare($i, $j)
	{
		return $i['num'] > $j['num'];
	}
}

?>
