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

	$serieId = Functions::get('id');
	if(is_null($serieId))
		Functions::redirect($_G['SERVER_ROOT']);

	try { $s = new Serie($serieId); }
	catch(RuntimeException $e) { Functions::redirect($_G['SERVER_ROOT']); }

	$whereClause = 'serie_id = :s';
	$params = array(
					array('id'=>':s', 'type'=>PDO::PARAM_INT, 'value'=>$s->get('id')),
				);

	$eps = Episode::search($whereClause, $params);

	loadView('serie', array('serie'=>$s, 'eps'=>$eps));

?>