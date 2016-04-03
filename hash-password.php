<?php

	/* Init constants */

	define('EOL', "\n");
	define('INDEX', true);
	define('MODEL', 'models/');	// Path to models
	define('VIEW', 'views/');	// Path to views
	require_once('init.php');

	/* Load utils */

	loadClass('functions');
	loadClass('db');

	/* Load models */

	loadClass('crypto');

	/* Load SQL Views */

	/* <controller> */

	if($argc<2)
	{
		echo 'Usage: '.$argv[0].' <password>'.EOL;
		exit(1);
	}

	$initIv = ($argc>=3) ? $argv[2] : NULL;

	list($storeIv, $hash) = Crypto::hashPassword($argv[1], $initIv);
	echo 'HASH =     '.$hash.EOL;
	echo 'STORE_IV = '.$storeIv.EOL;
?>
