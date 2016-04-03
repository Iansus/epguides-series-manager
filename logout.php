<?php

	/* Init constants */

	define('INDEX', true);
	define('MODEL', 'models/');	// Path to models
	define('VIEW', 'views/');	// Path to views
	require_once('init.php');

	/* Load utils */

	loadClass('functions');
	loadClass('db');

	/* Load models */

	loadClass('token');
	loadClass('user');
	loadClass('crypto');

	/* Load SQL Views */

	/* <controller> */

	// Check if user is logged in

	User::populateSessionFromCookie();

	if(!User::isLoggedIn()) Functions::redirect($_G['SERVER_ROOT'].'login.php');

	try { $user = new User($_SESSION['user_id']); }
	catch(RuntimeException $e) { Functions::redirect($_G['SERVER_ROOT'].'login.php'); }

	// MAIN
	User::logout();
	Functions::redirect($_G['SERVER_ROOT']);

?>
