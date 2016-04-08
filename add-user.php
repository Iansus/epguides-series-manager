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
	loadClass('user');

	/* Load SQL Views */

	/* <controller> */

	$user = new User();
	$username = readline('Username: ');
	$password = readline('Password: ');

	list($storeIv, $hash) = Crypto::hashPassword($password, NULL);

	$user->set('username', $username);
	$user->set('password', $hash);
	$user->set('storeIv', $storeIv);

	$user->save();

?>
