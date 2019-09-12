<html>
<head>
<title>Agent Test Page</title>
</head>
<body>
<form action="module/agent.php" method="get">
<table>
<tr><td>Agent Name: </td><td><input name="name"></td></tr>
<tr><td>Address: </td><td><input name="address"></td></tr>
<tr><td>Latitude: </td><td><input name="lat"></td></tr>
<tr><td>Longitude: </td><td><input name="lon"></td></tr>
<tr><td>Email: </td><td><input name="email"></td></tr>
<tr><td>Telephone: </td><td><input name="telephone"></td></tr>
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
<form action="module/agent.php" method="get">
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