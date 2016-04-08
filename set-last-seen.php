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

	$epId = Functions::get('id');
	if(is_null($epId))
		Functions::redirect($_G['SERVER_ROOT']);

	try { $ep = new Episode($epId); }
	catch(RuntimeException $e) { Functions::redirect($_G['SERVER_ROOT']); }

	$serie = new Serie($ep->get('serieId'));

	$whereClause = 'user_id = :u AND serie_id = :s';
	$params = array(
		array('id'=>':u', 'type'=>PDO::PARAM_INT, 'value'=>$user->get('id')),
		array('id'=>':s', 'type'=>PDO::PARAM_INT, 'value'=>$serie->get('id'))
	);

	$userSerie = UserSerie::search($whereClause, $params);
	if(!count($userSerie))
		Functions::redirect($_G['SERVER_ROOT']);
	else
		$userSerie = $userSerie[0];

	$userSerie->set('lastSeenSeason', $ep->get('season'));
	$userSerie->set('lastSeenEpisode', $ep->get('episode'));

	$userSerie->save();

	Functions::redirect($_G['SERVER_ROOT'].'serie.php?id='.$serie->get('id').'#s'.$ep->get('season').'e'.$ep->get('episode'));

?>
