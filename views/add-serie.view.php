<html>
	<head>
		<title>Add a serie</title>
		<link rel="shortcut icon" href="/static/img/favicon.ico"/>
		<link rel="stylesheet" type="text/css" href="<?php echo $_G['SERVER_ROOT']; ?>static/css/style.css" />
		<link rel="stylesheet" type="text/css" href="<?php echo $_G['SERVER_ROOT']; ?>static/css/navbar.css" />
		<script type="text/javascript">
			function update(v) {
				var epg = document.getElementById('epg');
				var bs = document.getElementById('bs');
				var ad7 = document.getElementById('addic7edid');

				epg.value = v.toLowerCase().replace(/ /g,'').replace(/^the/ig,'');
				ad7.value = v.toLowerCase().replace(/ /g,'_');
				bs.value = v.toLowerCase()+' s{}e[]';
			}
		</script>
	</head>
	<body>
		<table id="navbar">
			<tr>
				<td style="width:25%">New series</td>
				<td style="width:auto"></td>
				<td style="width:15%" class="border"><a href="index.php">Home</a></td>
				<td style="width:10%" class="border"><a href="logout.php">Logout</a></td>
			</tr>
		</table>
		<?php

			if(!is_null($args['error']))
				echo ($args['error']);

		?>
		<table cols=2 style="width:80%; margin-left:auto; margin-right:auto">
			<tr>
				<td style="width:50%">
					<h3 class="notSeen">Existing series:</h3>
					<form action="" method="GET">
						<select name="serie">
						<?php

							foreach($args['allSeries'] as $serie)
							{
								$sid = $serie->get('id');

								if(in_array($sid, $args['userSeries']))
									continue;

								echo '<option value='.$sid.'>';
								Functions::echos($serie->get('name'));
								echo '</option>'."\n";
							}

						?>
						</select>
					<br /><br />
					<input type="submit" name="go1" value="Add this serie" />
					</form>
				</td>
				<td>
					<h3 class="notSeen">Or add a new one:</h3>
					<form action="" method="POST">
						<table>
							<tr>
								<td>Name:</td>
								<td><input type="text" name="name" onKeyUp='update(this.value)'/></td>
							</tr>
							<tr>
								<td>Epguides name:</td>
								<td><input id="epg" type="text" name="epguides" /></td>
							</tr>
							<tr>
								<td>Binsearch name:</td>
								<td><input id="bs" type="text" name="binsearch" /></td>
							</tr>
							<tr>
								<td>DPStream Id<small>*</small>:</td>
								<td><input id="dpid" type="text" name="dpid" /></td>
							</tr>
							<tr>
								<td>Addic7ed Id:</td>
								<td><input id="addic7edid" type="text" name="addic7edid" /></td>
							</tr>
							<tr>
								<td></td>
								<td><input type="submit" name="go2" value="Add" /></td>
							</tr>
						</table>
					</form>
					<br />
					<small>
						* Search for "[serie] dpstream" on Google and grab the ID in the URL.<br />
						Example for <i>The Walking Dead</i> : <a href="https://www.google.fr/#q=the+walkign+dead+dpstream" target="_blank">Google</a> then "2506-the-walking-dead".
					</small>
				</td>
			</tr>
		</table>
	</body>
</html>
