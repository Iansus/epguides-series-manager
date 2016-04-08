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

	$whereClause = 'user_id = :uid';
	$params = array(
		array('id'=>':uid', 'type'=>PDO::PARAM_INT, 'value'=>$user->get('id'))
	);

	$oUserSeries = UserSerie::search($whereClause, $params);
	$userSeries = array();

	foreach($oUserSeries as $oUserSerie)
	{
		$userSeries[] = array(
			'serie'=>(new Serie($oUserSerie->get('serieId'))),
			'userSerie'=>$oUserSerie,
		);
	}

	$newEp = array();
	$toAir = array();
	$nextAir = array();

	$posters = array();
	foreach($userSeries as $serie)
	{
		$posters[$serie['serie']->get('id')] = 	Serie::getPoster($serie['serie']->get('epguidesUrl'));

		$whereClause = 'serie_id=:i AND (season >:s OR (season=:s AND episode>:e)) AND air_date + 86400 <= UNIX_TIMESTAMP()';
		$params = array(
						array('id'=>':s', 'type'=>PDO::PARAM_INT, 'value'=>$serie['userSerie']->get('lastSeenSeason')),
						array('id'=>':e', 'type'=>PDO::PARAM_INT, 'value'=>$serie['userSerie']->get('lastSeenEpisode')),
						array('id'=>':i', 'type'=>PDO::PARAM_INT, 'value'=>$serie['serie']->get('id')),
					);

		$newEpRes = Episode::search($whereClause, $params);
		$newEp[$serie['serie']->get('id')] = count($newEpRes);

		$whereClause = 'serie_id=:i AND (season >:s OR (season=:s AND episode>:e)) AND air_date + 86400 > UNIX_TIMESTAMP() ORDER BY air_date';
		$params = array(
						array('id'=>':s', 'type'=>PDO::PARAM_INT, 'value'=>$serie['userSerie']->get('lastSeenSeason')),
						array('id'=>':e', 'type'=>PDO::PARAM_INT, 'value'=>$serie['userSerie']->get('lastSeenEpisode')),
						array('id'=>':i', 'type'=>PDO::PARAM_INT, 'value'=>$serie['userSerie']->get('id')),
					);

		$toAirRes = Episode::search($whereClause, $params);
		$toAir[$serie['serie']->get('id')] = count($toAirRes);
		$nextAir[$serie['serie']->get('id')] = ($toAir[$serie['serie']->get('id')]===0) ? '-' : $toAirRes[0]->get('airDate');

	}



	loadView('index', array('series'=>$userSeries, 'posters'=>$posters, 'newEp'=>$newEp, 'toAir'=>$toAir, 'nextAir' => $nextAir));

?>
