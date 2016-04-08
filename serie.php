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

	loadClass('serie');
	loadClass('episode');
	loadClass('user');
	loadClass('userserie');

	/* Load SQL Views */

	/* <controller> */

        // Check if user is logged in

        User::populateSessionFromCookie();

        if(!User::isLoggedIn()) Functions::redirect($_G['SERVER_ROOT'].'login.php');

        try { $user = new User($_SESSION['user_id']); }
        catch(RuntimeException $e) { Functions::redirect($_G['SERVER_ROOT'].'login.php'); }


        // MAIN

	$serieId = Functions::get('id');
	if(is_null($serieId))
		Functions::redirect($_G['SERVER_ROOT']);

	try { $s = new Serie($serieId); }
	catch(RuntimeException $e) { Functions::redirect($_G['SERVER_ROOT']); }

	$whereClause = 'serie_id = :s AND user_id = :u';
	$params = array(
		array('id'=>':s', 'type'=>PDO::PARAM_INT, 'value'=>$s->get('id')),
		array('id'=>':u', 'type'=>PDO::PARAM_INT, 'value'=>$user->get('id')),
				);

	$userSerie = UserSerie::search($whereClause, $params);

	if(!count($userSerie))
		Functions::redirect($_G['SERVER_ROOT']);
	else
		$userSerie = $userSerie[0];

	$whereClause = 'serie_id = :s ORDER BY season DESC, episode DESC, air_date DESC';
	$params = array(
					array('id'=>':s', 'type'=>PDO::PARAM_INT, 'value'=>$s->get('id')),
				);

	$eps = Episode::search($whereClause, $params);

	loadView('serie', array('serie'=>$s, 'eps'=>$eps, 'userSerie'=>$userSerie));

?>
