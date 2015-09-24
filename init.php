<?php

	if(!defined('MODEL'))
		die('Fatal error: MODEL constant not defined');

    define('DEBUG', true);

    require_once(MODEL.'basic.php');

	$tmp = dirname($_SERVER['PHP_SELF']).'/';
	$_G['SERVER_ROOT'] = str_replace('//','/', $tmp);
	unset($tmp);


?>
