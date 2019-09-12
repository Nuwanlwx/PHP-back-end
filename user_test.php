<html>
<head>
<title>User Test Page</title>
</head>
<body>
<form action="module/user.php" method="get">
<table>

<tr><td>User Name: </td><td><input name="user"></td></tr>
<tr><td>Pass: </td><td><input name="pass"></td></tr>
<tr><td>Type: </td><td><input name="type"></td></tr>
<tr><td>Session ID: </td><td><input name="session_id"></td></tr>
<tr><td>Comments: </td><td><input name="comment"></td></tr>
<!-- <tr><td>First Name: </td><td><input name="firstName"></td></tr>
<tr><td>Last Name: </td><td><input name="lastName"></td></tr>
<tr><td>Mobile: </td><td><input name="mobile"></td></tr>
<tr><td>Province: </td><td><input name="province"></td></tr>
<tr><td>Email: </td><td><input name="email"></td></tr> -->
<tr>
	<td></td>
	<td>
		<input type=submit name="action" value="add">
		<input type=submit name="action" value="remove">
		<input type=submit name="action" value="search">
	</td>
</tr>
</table>
</form>
<form action="module/user.php" method="get">
<table>
<tr><td>Start: </td><td><input name="start" value="0"></td></tr>
<tr><td>Limit: </td><td><input name="limit" value="10"></td></tr>
<tr>
	<td></td>
	<td>
		<input type=submit name="action" value="read">
	</td>
</tr>
</table>
</form>
</body>
</html>