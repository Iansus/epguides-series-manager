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
		<form action="" method="POST">
			<table cols=2 style="margin-left:auto; margin-right:auto">
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
