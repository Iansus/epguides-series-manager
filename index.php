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
		$s = new Serie($oUserSerie->get('serieId'));
		$idx = $s->get('name').'-'.$s->get('id');
		$userSeries[$idx] = array(
			'serie'=>$s,
			'userSerie'=>$oUserSerie,
		);
	}

	ksort($userSeries);

	$mySeries = array();

	$posters = array();
	foreach($userSeries as $serie)
	{
        $sid = $serie['serie']->get('id');
        $mySeries[$sid] = array('serie'=>$serie['serie']);
        
		$posters[$sid] = Serie::getPoster($serie['serie']->get('epguidesUrl'));

        $whereClause = 'serie_id=:s';
		$params = array(
			array('id'=>':s', 'type'=>PDO::PARAM_STR, 'value'=>$sid)
		);

		$howManyReq = UserSerie::search($whereClause, $params);
		$mySeries[$sid]['howMany'] = count($howManyReq);
        
        // To see
		$whereClause = 'serie_id=:i AND (season >:s OR (season=:s AND episode>:e)) AND air_date + 86400 <= UNIX_TIMESTAMP() ORDER BY air_date DESC';
		$params = array(
						array('id'=>':s', 'type'=>PDO::PARAM_INT, 'value'=>$serie['userSerie']->get('lastSeenSeason')),
						array('id'=>':e', 'type'=>PDO::PARAM_INT, 'value'=>$serie['userSerie']->get('lastSeenEpisode')),
						array('id'=>':i', 'type'=>PDO::PARAM_INT, 'value'=>$serie['serie']->get('id')),
					);

		$toSeeRes = Episode::search($whereClause, $params);
		$mySeries[$sid]['toSee'] = $toSeeRes;

        // Aired
		$whereClause = 'serie_id=:i AND air_date + 86400 <= UNIX_TIMESTAMP() ORDER BY air_date DESC';
		$params = array(
						array('id'=>':i', 'type'=>PDO::PARAM_INT, 'value'=>$serie['serie']->get('id')),
					);

		$toSeeRes = Episode::search($whereClause, $params);
		$mySeries[$sid]['aired'] = $toSeeRes;

        // To air
		$whereClause = 'serie_id=:i AND air_date + 86400 > UNIX_TIMESTAMP() ORDER BY air_date ASC';
		$params = array(
						array('id'=>':i', 'type'=>PDO::PARAM_INT, 'value'=>$serie['serie']->get('id')),
					);
                    
		$toAirRes = Episode::search($whereClause, $params);
		$mySeries[$sid]['toAir'] = $toAirRes;

	}

	// Series not added yet
	$allSeries = Serie::searchForAll('name ASC');
	$notMySeries = array();

	foreach($allSeries as $serie)
	{
        $sid = $serie->get('id');
		if(isset($mySeries[$sid]))
			continue;

        $notMySeries[$sid] = array();
        $notMySeries[$sid]['serie'] = $serie;
		$posters[$sid] = Serie::getPoster($serie->get('epguidesUrl'));
        
		$whereClause = 'serie_id=:s';
		$params = array(
			array('id'=>':s', 'type'=>PDO::PARAM_STR, 'value'=>$serie->get('id'))
		);

		$howManyReq = UserSerie::search($whereClause, $params);
		$notMySeries[$sid]['howMany'] = count($howManyReq);


		$whereClause = 'serie_id=:s AND air_date + 86400 <= UNIX_TIMESTAMP() ORDER BY air_date DESC';
		$aired = Episode::search($whereClause, $params);
        $notMySeries[$sid]['aired'] = $aired;

		$whereClause = 'serie_id=:s AND air_date + 86400 > UNIX_TIMESTAMP() ORDER BY air_date ASC';
		$toAir = Episode::search($whereClause, $params);
        $notMySeries[$sid]['toAir'] = $toAir;
	}


	loadView('index', array('series'=>$userSeries, 'mySeries' => $mySeries, 'notMySeries' => $notMySeries, 'posters'=>$posters));

?>
