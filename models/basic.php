<?php

	define('BASIC_LOADED', true);

	function loadClass($cName)
	{
		global $_G;

		$fileName = $cName.'.class.php';

		if(!defined('MODEL'))
		{
			if(defined('DEBUG') && DEBUG) echo 'Fatal error : can\'t load class '.$cName.' : MODEL not defined';

			return;
		}

		$const = getConstantName($fileName);
		if(!defined($const)) require(MODEL.$fileName);
	}

	function getConstantName($filename)
	{
		$classname = basename($filename);
		$classname = substr($classname, 0, strripos($classname, '.php'));
		$classname = str_replace('.','_', $classname);

		return 'HEAD_'.strtoupper($classname);
	}

	function createConst($filename)
	{
		define(getConstantName($filename), $filename);
	}

	function loadView($vName, $args=array(), $ERROR = '', $MESSAGE='')
	{
		global $_G;

		if(!defined('VIEW'))
		{
			if(defined('DEBUG') && DEBUG) echo 'Fatal error : can\'t load view '.$vName.' : VIEW not defined';

			return;
		}

		$filename = VIEW.$vName.'.view.php';

		if(is_file($filename)) include($filename);
		elseif(defined('DEBUG') && DEBUG) echo 'Fatal error : cannot load view '.$vName;
	}

	class NotImplementedException extends RuntimeException
	{
	}

?>
