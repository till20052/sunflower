<?php

class FileManagerClass
{
	protected $path = 'static/upload';
	
	public function setPath($path = '')
	{
		if($path != '')
			$_path = $path;
		
		$this->path = Router::getAppFolder().DS.$_path;
	}
	
	public function getPath()
	{
		if($this->path == '')
			$this->setPath();
		
		return $this->path;
	}
	
	public function getList()
	{
		$path = $this->getPath();
		
		if( ! $handle = opendir($path))
			return false;
		
		$foldersList = array();
		$filesList = array();
		
		while($fileName = readdir($handle))
		{
			$filePath = $path.DS.$fileName;
			
			if(in_array($fileName, array('.', '..')))
				continue;
			
			if(is_file($filePath))
			{
				$filesList[] = array(
					'name' => $fileName,
					'size' => filesize($filePath),
					'type' => 'file',
					'mime' => mimeContentType($filePath)
				);
			}
			else
			{
				$foldersList[] = array(
					'name' => $fileName,
					'type' => 'folder'
				);
			}
		}
		
		closedir($handle);
		
		usort($foldersList, array('FileManagerClass', 'compare'));
		usort($filesList, array('FileManagerClass', 'compare'));
		
		return array_merge($foldersList, $filesList);
	}
	
	public function makedir()
	{
		$path = $this->getPath();
		
		$folderName = $_folderName = 'new';
		
		$cnt = 1;
		while(is_dir($path.DS.$_folderName))
		{
			$_folderName = $folderName.'_'.$cnt;
			$cnt++;
		}
		$folderName = $_folderName;
		
		mkdir($path.DS.$folderName, 0777);
	}
	
	public function write($fileName, $fileContent)
	{
		$path = $this->getPath();
		
		while(file_exists($path.DS.$fileName))
		{
			$tokens = explode('.', $fileName);
			$fileName .= implode('.', array_splice($tokens, 0, count($tokens)-1)).'.copy.'.$tokens[count($tokens)-1];
		}
		
		if( ! $handle = fopen($path.DS.$fileName, 'w'))
			return false;
		
		fwrite($handle, $fileContent);
		fclose($handle);
		
		return true;
	}
	
	public function remove($fname, $fpath = '')
	{
		if($fpath != '')
			$fpath = $fpath.DS.$fname;
		else
			$fpath = $this->getPath().DS.$fname;
		
		if( ! is_dir($fpath) && ! is_file($fpath))
			return false;
		
		if(is_dir($fpath))
		{
			if( ! $handle = opendir($fpath))
				return false;
			
			while($fname = readdir($handle))
			{
				if(in_array($fname, array('.', '..')))
					continue;
				
				$this->remove($fname, $fpath);
			}
			
			closedir($handle);
			
			rmdir($fpath);
		}
		
		if(is_file($fpath))
		{
			unlink($fpath);
		}
		
		return true;
	}
	
	public function rename($foldname, $fnewname)
	{
		$path = $this->getPath();
		return rename($path.DS.$foldname, $path.DS.$fnewname);
	}
	
	public static function compare($a, $b)
	{
		return strcmp($a['name'], $b['name']);
	}
}
