<html>
<head>
<title>Product Category Test Page</title>
</head>
<body>
<form action="module/category.php" method="get">
<table>
<tr><td>ID: </td><td><input name="id"></td></tr>
<tr><td>Name: </td><td><input name="name"></td></tr>
<tr><td>Description: </td><td><input name="description"></td></tr>
<tr><td>Parent: </td><td><input name="parent"></td></tr>
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
<form action="module/category.php" method="get">
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