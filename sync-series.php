<?php

	ini_set('max_execution_time','0');

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

    // MAIN

	$allSeries = Serie::searchForAll();

	$sid = Functions::get('id');

	if(!is_null($sid))
	{
		try { $s = new Serie($sid); }
		catch(RuntimeException $e) { Functions::redirect($_G['SERVER_ROOT']); }

		$allSeries = array($s);
	}

	$allEpisodes = Episode::searchForAll();

	$episodes = array();

	foreach($allEpisodes as $ep)
	{
		$sid = $ep->get('serieId');
		if(!isset($episodes[$sid]))
			$episodes[$sid] = array();

		$se_id = $ep->get('season');
		if(!isset($episodes[$sid][$se_id]))
			$episodes[$sid][$se_id] = array();

		$episodes[$sid][$se_id][$ep->get('episode')] = array('id'=>$ep->get('id'), 'airdate'=>$ep->get('airDate'));
	}

	define('SEASON',1);
	define('EPISODE',2);
	define('AIR_DATE',3);
	define('LINK',6);
	define('NAME',7);
	define('ALL',0);

	$res = '';

	foreach($allSeries as $serie)
	{
		$pUrl = Serie::getPoster($serie->get('epguidesUrl'));
		$poster = file_get_contents($pUrl);

		if(!empty($poster))
			file_put_contents('static/img/cast/'.$serie->get('id').'.jpg', $poster);

		$page = file_get_contents($serie->get('epguidesUrl'));
		$sid = $serie->get('id');

		preg_match_all('/[0-9]+\.? +([0-9]+)-([0-9]+) +[0-9a-z-_]* +([0-9]+( |\/)[a-z]{3}( |\/)[0-9]+) +\<a([^>]+)\>([^<]+)\<\/a\>/isU', $page, $out);
		for($i=0; $i<count($out[ALL]); $i++)
		{
			$season = $out[SEASON][$i];
			$episode = $out[EPISODE][$i];
			$name = $out[NAME][$i];
			$airDate = strtotime(str_replace("/", " ", $out[AIR_DATE][$i]));
			$link = preg_replace('/^.*href="([^"]+)".*$/isU', '$1', $out[LINK][$i]);
			$link = preg_replace("/^.*href='([^']+)'.*$/isU", '$1', $link);

			if(!isset($episodes[$sid][$season][$episode]))
			{
				$e = new Episode();
				$e->set('serieId', $sid);
				$e->set('season', $season);
				$e->set('episode', $episode);
				$e->set('name', $name);
				$e->set('link', $link);
				$e->set('airDate', $airDate);

				$res .= '[+] New episode for '.$serie->get('name').' s'.$season.'e'.$episode."\n";

				$e->save();
			}
			elseif($episodes[$sid][$season][$episode]['airdate'] != $airDate)
			{
				$ep = new Episode($episodes[$sid][$season][$episode]['id']);
				$ep->set('airDate', $airDate);

				$res .= '[+] Updated episode for '.$serie->get('name').' s'.$season.'e'.$episode."\n";

				$ep->save();
			}
		}
	}

	Functions::redirect($_G['SERVER_ROOT']);
	echo (isset($_SERVER['SERVER_NAME'])) ? nl2br($res) : $res;
?>
