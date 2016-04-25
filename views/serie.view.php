<html>
	<head>
		<title><?php Functions::echos($args['serie']->get('name')); ?></title>
		<link rel="stylesheet" type="text/css" href="<?php echo $_G['SERVER_ROOT']; ?>static/css/style.css" />
	</head>
	<body>
		<h1>
			<?php Functions::echos($args['serie']->get('name')); ?>
			[<a href="delete-serie.php?id=<?php echo $args['serie']->get('id'); ?>">Delete</a>]
			[<a href="index.php">Home</a>]
			[<a href="#<?php echo 's'.$args['userSerie']->get('lastSeenSeason').'e'.$args['userSerie']->get('lastSeenEpisode'); ?>">Go to last seen episode</a>]
		</h1>
		<table class="allepisodes" cols=5 style="width:100%">
			<thead>
				<td style="width:5%">#</td>
				<td style="width:5%">Summary</td>
				<td style="width:5%">Binsearch</td>
				<td style="width:5%">DPStream</td>
				<td style="width:5%">English sub</td>
				<td style="width:5%">Last seen</td>
				<td style="width:10%">Air Date</td>
				<td style="text-align:left">Name</td>
			</thead>
			<?php

				$serie = $args['serie'];
				$userSerie = $args['userSerie'];
				$lastep = $userSerie->get('lastSeenEpisode');
				$lastse = $userSerie->get('lastSeenSeason');

				$prevse = 1;

				foreach($args['eps'] as $ep)
				{
					$epno = $ep->get('episode');
					$seno = $ep->get('season');
					$airdate = $ep->get('airDate');

					if($seno!=$prevse) echo '<tr><td>&nbsp;</td></tr>';
					$prevse = $seno;

					$epstr = str_pad($epno, 2, "0", STR_PAD_LEFT);
					$se = str_pad($seno, 2, "0", STR_PAD_LEFT);

					$seen = ($seno < $lastse) || ($seno==$lastse && $epno<=$lastep);
					$status = ($seen)? 'seen' : (($airdate+86400<=time()) ? 'notseen' : 'notaired');

					$bs = $serie->get('binsearchUrl');
					$bs = str_replace('{}', $se, $bs);
					$bs = str_replace('[]', $epstr, $bs);

					$airDate = date('d/m/Y', $airdate);
			?>
			<tr>
				<td>S<?php echo $se; ?>E<?php echo $epstr ?></td>
				<td><a href="<?php echo $ep->get('link'); ?>" target="_blank"><img src="<?php echo $_G['SERVER_ROOT']; ?>static/img/extern.svg" /></a></td>
				<td><a href="http://binsearch.info/index.php?&m=&max=25&adv_g=&adv_age=999&adv_sort=date&xminsize=200&maxsize=&font=&postdate=&q=<?php echo ($bs); ?>" target="_blank"><img src="<?php echo $_G['SERVER_ROOT']; ?>static/img/extern.svg" /></a></td>
				<td><a href="http://www.dpstream.net/serie-<?php echo $serie->get('dpstreamId'); ?>-saison-<?php echo $seno; ?>-episode-<?php echo $epstr; ?>-VOSTFR.html" target="_blank"><img src="<?php echo $_G['SERVER_ROOT']; ?>static/img/dpstream.png" /></a></td>
				<td><a href="http://www.addic7ed.com/serie/<?php echo $serie->get('addic7edId'); ?>/<?php echo $seno; ?>/<?php echo $epno; ?>/1" target="_blank"><img src="<?php echo $_G['SERVER_ROOT']; ?>static/img/subtitle.png" /></a></td>
<td><input type="radio" onClick="location.href='set-last-seen.php?id=<?php echo $ep->get('id'); ?>'" /></td>
				<td><a style="color:white" name="<?php echo 's'.$seno.'e'.$epno; ?>"><?php echo $airDate; ?></a></td>
				<td style="text-align:left"><span class="<?php echo $status; ?>"><?php Functions::echos($ep->get('name')); ?></span></td>
			</tr>
			<?php
				}

			?>
		</table>
	</body>
</html>
