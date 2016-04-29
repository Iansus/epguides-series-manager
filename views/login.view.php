<html>
	<head>
		<title>Login page</title>
		<link rel="stylesheet" type="text/css" href="<?php echo $_G['SERVER_ROOT']; ?>static/css/style.css" />
		<link rel="stylesheet" type="text/css" href="<?php echo $_G['SERVER_ROOT']; ?>static/css/navbar.css" />
	</head>
	<body>
		<table id="navbar">
			<tr>
				<td style="width:25%">Login page</td>
				<td style="width:auto"></td>
			</tr>
		</table>
		<div style="float:left; width:20%; margin-left:1%;">
			<div style="border-radius:8px; background:#AAA; margin-left:1%; padding: 4%;">
				<?php echo $args['userCount'].' users have registered so far'; ?>
			</div>
			<br />
			<div style="border-radius:8px; background:#AAA; margin-left:1%; padding: 4%;">
				<?php echo $args['sCount'].' series have been added'; ?>
			</div>
			<br />
			<div style="border-radius:8px; background:#AAA; margin-left:1%; padding: 4%;">
				<?php echo $args['epCount'].' episodes linked'; ?>
			</div>
			<br />
		</div>
		<form action="" method="POST">
			<table cols=2 style="margin-left:25%; margin-top:5%">
				<tr>
					<td>Username:</td>
					<td><input name="username" type="text"/></td>
				</tr>
				<tr>
					<td>Password:</td>
					<td><input name="password" type="password" /></td>
				</tr>
				<tr>
					<td></td>
					<td class="center"><br /><input type="submit" value="Log in !" name="submit" />
				</tr>
			</table>
		</form>
	</body>
</html>
