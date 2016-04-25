<html>
	<head>
		<title>Add a serie</title>
		<link rel="stylesheet" type="text/css" href="<?php echo $_G['SERVER_ROOT']; ?>static/css/style.css" />
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
		<h1>Add a serie to your list</h1>
		<?php

			if(!is_null($args['error']))
				echo ($args['error']);

		?>
		<table cols=2 style="width:100%">
			<tr>
				<td style="width:20%">
					<h3>Existing serie:</h3>
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
					<h3>Or add a new one:</h3>
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
								<td>DPStream Id:</td>
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
				</td>
			</tr>
		</table>
	</body>
</html>
