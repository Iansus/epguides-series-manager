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
			foreach($args['mySeries'] as $o)
			{
				?>
				<td style="text-align:center">
					<a href="<?php echo $_G['SERVER_ROOT']; ?>serie.php?id=<?php echo $o['serie']->get('id'); ?>">
						<img class='img-round' width=160 height=120 src="<?php echo $_G['SERVER_ROOT'].'static/img/cast/'.$o['serie']->get('id').'.jpg'; ?>" />
						<br />
						<?php Functions::echos($o['serie']->get('name')); ?>
					</a>
					<a href="<?php echo $o['serie']->get('epguidesUrl'); ?>" target="_blank"><img src="<?php echo $_G['SERVER_ROOT']; ?>static/img/extern.svg" /></a>
					<a href="<?php echo $_G['SERVER_ROOT']; ?>serie.php?id=<?php echo $o['serie']->get('id'); ?>">
					<br />
                    <small>
                        <span class="tba">
                            <?php 
                            
                                $s = $o['howMany']>1 ? 's' : '';
                                echo $o['howMany'].' user'.$s.' watching'; 
                                
                            ?>
                        </span>
                        <br />
						<?php
                            
                            // Aired
                            $airedLine = count($o['aired']) ? 
                                'Last on '.date('Y-m-d', $o['aired'][0]->get('airDate')) :
                                'No episode out';
                            
                            $nToSee = count($o['toSee']);
                            $airedLine .= $nToSee ? ' ('.$nToSee.' to see)' : '';
                                
                            $bonus = $nToSee ? ' class="newep"' : '';
                            echo '<span'.$bonus.'>'.$airedLine.'</span>';
                        
						?>
						<br />
						<?php
							
                            // To air
                            $nTBA = count($o['toAir']);
                            $toAirLine = $nTBA ?
                                'Next on '.date('Y-m-d', $o['toAir'][0]->get('airDate')).' ('.$nTBA.' TBA)' :
                                'No episode to be added';
                                
                            $bonus = !$nTBA ? ' class="tba"' : '';
                            echo '<span'.$bonus.'>'.$toAirLine.'</span>';
						?>
                        <br />
                    </small>
					</a>
				</td>
				<?php
				if(++$i%6==0) echo '</tr><tr>';
			}
		?>
		<?php
			foreach($args['notMySeries'] as $o)
			{
				?>
				<td style="text-align:center">
					<a href="<?php echo $_G['SERVER_ROOT']; ?>add-serie.php?go1&serie=<?php echo $o['serie']->get('id'); ?>">
						<div class="gray-out">
							<img class='img-round' width=160 height=120 src="<?php echo $_G['SERVER_ROOT'].'static/img/cast/'.$o['serie']->get('id').'.jpg'; ?>" />
						<br />
						<?php Functions::echos($o['serie']->get('name')); ?>
					</a>
					<a href="<?php echo $o['serie']->get('epguidesUrl'); ?>" target="_blank"><img src="<?php echo $_G['SERVER_ROOT']; ?>static/img/extern.svg" /></a>
					<a href="<?php echo $_G['SERVER_ROOT']; ?>add-serie.php?go1&serie=<?php echo $o['serie']->get('id'); ?>">
					<br />
                    <small>
                        <span class="tba">
                            <?php 
                            
                                $s = $o['howMany']>1 ? 's' : '';
                                echo $o['howMany'].' user'.$s.' watching'; 
                                
                            ?>
                        </span>
                        <br />
                        <?php
                            
                            // Aired
                            $nOut = count($o['aired']);
                            $airedLine = $nOut ? 
                                'Last on '.date('Y-m-d', $o['aired'][0]->get('airDate')).' ('.$nOut.' out)' :
                                'No episode out';
                            
                            $bonus = '';
                            echo '<span'.$bonus.'>'.$airedLine.'</span>';
                        
                        ?>
                        <br />
                        <?php
                            
                            // To air
                            $nTBA = count($o['toAir']);
                            $toAirLine = $nTBA ?
                                'Next on '.date('Y-m-d', $o['toAir'][0]->get('airDate')).' ('.$nTBA.' TBA)' :
                                'No episode to be added';
                                
                            $bonus = !$nTBA ? ' class="tba"' : '';
                            echo '<span'.$bonus.'>'.$toAirLine.'</span>';
                        ?>
                    </small>
					<br />
				</td>
				<?php
				if(++$i%6==0) echo '</tr><tr>';
			}
		?>

		</table>
	</body>
</html>
