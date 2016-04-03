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

	User::populateSessionFromCookie();
	if(User::isLoggedIn())
	{
		try {
			$user = new User($_SESSION['user_id']);
			Functions::redirect($_G['SERVER_ROOT']);
		}
		catch (RuntimeException $e) {}
	}

	if(isset($_POST['submit']))
	{
		$username = Functions::post('username');
		$password = Functions::post('password');

		$whereClause = 'username = :u';
		$params = array(
			array('id'=>':u', 'type'=>PDO::PARAM_STR, 'value'=>$username)
		);

		$results = User::search($whereClause, $params);
		if(count($results) > 0)
		{
			$user = $results[0];
			$storedIv = $user->get('storeIv');
			$storedHash = $user->get('password');

			list($storeIv, $hash) = Crypto::hashPassword($password, $storedIv);

			if($hash == $storedHash)
			{
				User::login($user->get('id'));
				Functions::redirect($_G['SERVER_ROOT']);
			}
		}
	}

		loadView('login');

?>
