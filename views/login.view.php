<html>
	<head>
		<title>Login page</title>
		<link rel="stylesheet" type="text/css" href="<?php echo $_G['SERVER_ROOT']; ?>static/css/style.css" />
	</head>
	<body>
		<h1>
			[<a href="index.php">Home</a>]
		</h1>
		<form action="" method="POST">
			<table cols=2>
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
