<?php

	if(!defined('MODEL'))
		die('Fatal error: MODEL constant not defined');

    define('DEBUG', true);

    require_once(MODEL.'basic.php');

	$_G['SERVER_ROOT'] = dirname($_SERVER['PHP_SELF']);

?>
