<html>
	<head>
		<title>All series</title>
		<link rel="stylesheet" type="text/css" href="<?php echo $_G['SERVER_ROOT']; ?>static/css/style.css" />
		<link rel="stylesheet" type="text/css" href="<?php echo $_G['SERVER_ROOT']; ?>static/css/navbar.css" />
	</head>
	<body>
		<table id="navbar">
			<tr>
				<td style="width:25%">All series</td>
				<td style="width:auto"></td>
				<td style="width:15%" class="border"><a href="add-serie.php">Add a series</a></td>
				<td style="width:10%" class="border"><a href="logout.php">Logout</a></td>
			</tr>
		</table>
		<table class="allseries" cols=4 style="margin-left:auto; margin-right:auto">
		<?php
			$i=0;
			foreach($args['series'] as $serie)
			{
				?>
				<td style="text-align:center">
					<a href="<?php echo $_G['SERVER_ROOT']; ?>serie.php?id=<?php echo $serie['serie']->get('id'); ?>">
						<img class='img-round' width=160 height=120 src="<?php echo $_G['SERVER_ROOT'].'static/img/cast/'.$serie['serie']->get('id').'.jpg'; ?>" />
						<br />
						<?php Functions::echos($serie['serie']->get('name')); ?>
					</a>
					<a href="<?php echo $serie['serie']->get('epguidesUrl'); ?>" target="_blank"><img src="<?php echo $_G['SERVER_ROOT']; ?>static/img/extern.svg" /></a>
					<a href="<?php echo $_G['SERVER_ROOT']; ?>serie.php?id=<?php echo $serie['serie']->get('id'); ?>">
					<br /><small>
						<?php
							if($args['newEp'][$serie['serie']->get('id')]) {
								echo '<span class="newEp">'; Functions::echos($args['newEp'][$serie['serie']->get('id')].' new episode(s)'); echo '</span>';
							 }else
								echo '<i>no new episode</i>';
						?>
						</small><br />
						<?php
							if($args['toAir'][$serie['serie']->get('id')]) {
								echo '<small><span class="tba">'; Functions::echos($args['toAir'][$serie['serie']->get('id')]); echo ' <i>TBA</i>, next on ';
								Functions::echos(date('d/m/Y', $args['nextAir'][$serie['serie']->get('id')])); echo '</span></small>';
							 }else
								echo '<small><i>no episode to be aired</i></small>';
						?>
					</a>
				</td>
				<?php
				if(++$i%6==0) echo '</tr><tr>';
			}
		?>
		<?php
			foreach($args['notMySeries'] as $serie)
			{
				?>
				<td style="text-align:center">
					<a href="<?php echo $_G['SERVER_ROOT']; ?>add-serie.php?go1&serie=<?php echo $serie['serie']->get('id'); ?>">
						<div class="gray-out">
							<img class='img-round' width=160 height=120 src="<?php echo $_G['SERVER_ROOT'].'static/img/cast/'.$serie['serie']->get('id').'.jpg'; ?>" />
						<br />
						<?php Functions::echos($serie['serie']->get('name')); ?>
					</a>
					<a href="<?php echo $serie['serie']->get('epguidesUrl'); ?>" target="_blank"><img src="<?php echo $_G['SERVER_ROOT']; ?>static/img/extern.svg" /></a>
					<a href="<?php echo $_G['SERVER_ROOT']; ?>add-serie.php?go1&serie=<?php echo $serie['serie']->get('id'); ?>">
					<br />
					<small>
					<?php

						if($serie['epOut'])
							echo $serie['epOut'].' out (last '.strftime('%d/%m/%Y', $serie['lastAired']->get('airDate')).')';

						if($serie['epOut'] && $serie['epToAir'])
							echo ' / ';

						if($serie['epToAir'])
							echo '<span class="newEp">'.$serie['epToAir'].' TBA</span>';

						if(!$serie['epOut'] && !$serie['epToAir'])
							echo '<i>no</i>';

					?>
					</small>
					<br />
					<small class="tba"><?php echo $serie['howMany']; ?></small></div>
				</td>
				<?php
				if(++$i%6==0) echo '</tr><tr>';
			}
		?>

		</table>
	</body>
</html>
