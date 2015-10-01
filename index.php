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

	/* Load SQL Views */

	/* <controller> */

	$allSeries = Serie::searchForAll();
	$newEp = array();
	$toAir = array();

	$posters = array();
	foreach($allSeries as $serie)
	{
		$posters[$serie->get('id')] = 	Serie::getPoster($serie->get('epguidesUrl'));

		$whereClause = 'serie_id=:i AND (season >:s OR (season=:s AND episode>:e)) AND air_date + 86400 <= UNIX_TIMESTAMP()';
		$params = array(
						array('id'=>':s', 'type'=>PDO::PARAM_INT, 'value'=>$serie->get('lastSeenSeason')),
						array('id'=>':e', 'type'=>PDO::PARAM_INT, 'value'=>$serie->get('lastSeenEpisode')),
						array('id'=>':i', 'type'=>PDO::PARAM_INT, 'value'=>$serie->get('id')),
					);

		$newEpRes = Episode::search($whereClause, $params);
		$newEp[$serie->get('id')] = count($newEpRes);

		$whereClause = 'serie_id=:i AND (season >:s OR (season=:s AND episode>:e)) AND air_date + 86400 > UNIX_TIMESTAMP()';
		$params = array(
						array('id'=>':s', 'type'=>PDO::PARAM_INT, 'value'=>$serie->get('lastSeenSeason')),
						array('id'=>':e', 'type'=>PDO::PARAM_INT, 'value'=>$serie->get('lastSeenEpisode')),
						array('id'=>':i', 'type'=>PDO::PARAM_INT, 'value'=>$serie->get('id')),
					);

		$toAirRes = Episode::search($whereClause, $params);
		$toAir[$serie->get('id')] = count($toAirRes);

	}



	loadView('index', array('series'=>$allSeries, 'posters'=>$posters, 'newEp'=>$newEp, 'toAir'=>$toAir));

?>
