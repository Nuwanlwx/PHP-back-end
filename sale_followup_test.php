<html>
<head>
<title>Sales Followup Test Page</title>
</head>
<body>
<form action="module/sale_followup.php" method="get">
<table>
<tr><td>ID: </td><td><input name="id"></td></tr>
<tr><td>Sale ID: </td><td><input name="sale_id"></td></tr>
<tr><td>Followup Timestamp: </td><td><input name="followup_ts"></td></tr>
<tr><td>Followup Parameter: </td><td><input name="para"></td></tr>
<tr><td>Value: </td><td><input name="val"></td></tr>
<tr>
	<td></td>
	<td>
		<input type=submit name="action" value="add">
		<input type=submit name="action" value="remove">
		<input type=submit name="action" value="update">
		<input type=submit name="action" value="search">
	</td>
</tr>
</table>
</form>
<form action="module/sale_followup.php" method="get">
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