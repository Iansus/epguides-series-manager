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
	loadClass('userserie');
	loadClass('user');

	/* Load SQL Views */

	/* <controller> */

        // Check if user is logged in

        User::populateSessionFromCookie();

        if(!User::isLoggedIn()) Functions::redirect($_G['SERVER_ROOT'].'login.php');

        try { $user = new User($_SESSION['user_id']); }
        catch(RuntimeException $e) { Functions::redirect($_G['SERVER_ROOT'].'login.php'); }


        // MAIN

	$error = NULL;

	if(isset($_POST['go1']))
	{
		$errors = array();

		$sid = Functions::post('serie');

		if(is_null($sid) || empty($sid)) $errors[] = 'Serie must not be empty';

		if(count($errors))
			$error = '<ul class="error"><li>'.implode('</li><li>', $errors).'</li></ul>';
		else
		{
			$userSerie = new UserSerie();
			$userSerie->set('userId', $user->get('id'));
			$userSerie->set('serieId', $sid);
			$userSerie->set('lastSeenSeason', 0);
			$userSerie->set('lastSeenEpisode', 0);

			$userSerie->save();

			Functions::redirect($_G['SERVER_ROOT']);
		}

	}
	elseif(isset($_POST['go2']))
	{
		$errors = array();

		$name = Functions::post('name');
		$epguides = Functions::post('epguides');
		$binsearch = Functions::post('binsearch');
		$dpid = Functions::post('dpid');

		if(is_null($name) || empty($name)) $errors[] = 'Name must not be empty';
		if(is_null($epguides) || empty($epguides)) $errors[] = 'Epguides name must not be empty';
		if(is_null($binsearch) || empty($binsearch)) $errors[] = 'Binsearch name must not be empty';
		if(is_null($dpid) || empty($dpid)) $errors[] = 'DPStream id must not be empty';

		if(count($errors))
			$error = '<ul class="error"><li>'.implode('</li><li>', $errors).'</li></ul>';
		else
		{
			$serie = new Serie();
			$serie->set('name', $name);
			$serie->set('epguidesUrl', 'http://epguides.com/'.$epguides.'/');
			$serie->set('binsearchUrl', $binsearch);
			$serie->set('dpstreamId', $dpid);
			$serie->save();

			$userSerie = new UserSerie();
			$userSerie->set('userId', $user->get('id'));
			$userSerie->set('serieId', $serie->get('id'));
			$userSerie->set('lastSeenSeason', 0);
			$userSerie->set('lastSeenEpisode', 0);
			$userSerie->save();

			Functions::redirect($_G['SERVER_ROOT'].'sync-series.php?id='.$serie->get('id'));
		}
	}

	// Display all series
	$allSeries = Serie::searchForAll();

	// Search for user's series
	$whereClause = 'user_id = :uid';
	$params = array(
		array('id'=>'uid', 'type'=>PDO::PARAM_INT, 'value'=>$user->get('id'))
	);

	$oUserSeries = UserSerie::search($whereClause, $params);
	$userSeries = array();

	foreach($oUserSeries as $oSerie)
		$userSeries[] = $oSerie->get('serieId');

	loadView('add-serie', array('allSeries'=>$allSeries, 'userSeries'=> $userSeries, 'error'=>$error));

?>
