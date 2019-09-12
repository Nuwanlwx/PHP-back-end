<html>
<head>
<title>Customer Test Page</title>
</head>
<body>
<form action="module/officer.php" method="get">
<table>
<tr><td>Officer Name: </td><td><input name="name"></td></tr>
<tr><td>Employee ID: </td><td><input name="emp_id"></td></tr>
<tr><td>Email: </td><td><input name="email"></td></tr>
<tr><td>Telephone: </td><td><input name="telephone"></td></tr>
<tr><td>User ID: </td><td><input name="user_id"></td></tr>
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
<form action="module/officer.php" method="get">
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