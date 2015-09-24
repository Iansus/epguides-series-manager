<html>
	<head>
		<title>Add a serie</title>
		<link rel="stylesheet" type="text/css" href="<?php echo $_G['SERVER_ROOT']; ?>static/css/style.css" />
		<script type="text/javascript">
			function update(v) {
				var epg = document.getElementById('epg');
				var bs = document.getElementById('bs');

				epg.value = v.toLowerCase().replace(' ','').replace('the','');
				bs.value = v.toLowerCase()+' s{}e[]';
			}
		</script>
	</head>
	<body>
		<h1>Add a serie</h1>
		<?php

			if(!is_null($args['error']))
				echo ($args['error']);

		?>
		<form action="" method="POST">
			<table>
				<tr>
					<td>Name :</td>
					<td><input type="text" name="name" onKeyUp='update(this.value)'/></td>
				</tr>
				<tr>
					<td>Epguides name :</td>
					<td><input id="epg" type="text" name="epguides" /></td>
				</tr>
				<tr>
					<td>Binsearch name :</td>
					<td><input id="bs" type="text" name="binsearch" /></td>
				</tr>
				<tr>
					<td></td>
					<td><input type="submit" name="go" value="Add" /></td>
				</tr>
			</table>
		</form>
	</body>
</html>
