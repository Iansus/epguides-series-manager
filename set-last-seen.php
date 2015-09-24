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

	$epId = Functions::get('id');
	if(is_null($epId))
		Functions::redirect($_G['SERVER_ROOT']);

	try { $ep = new Episode($epId); }
	catch(RuntimeException $e) { Functions::redirect($_G['SERVER_ROOT']); }

	$serie = new Serie($ep->get('serieId'));

	$serie->set('lastSeenSeason', $ep->get('season'));
	$serie->set('lastSeenEpisode', $ep->get('episode'));

	$serie->save();

	Functions::redirect($_G['SERVER_ROOT'].'/serie.php?id='.$serie->get('id'));

?>
