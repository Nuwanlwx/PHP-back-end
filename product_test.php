<html>
<head>
<title>Product Test Page</title>
</head>
<body>
<form action="module/product.php" method="get">
<table>
<tr><td>Size: </td><td><input name="name"></td></tr>
<tr><td>Brand: </td><td><input name="brand"></td></tr>
<tr><td>PR: </td><td><input name="pr"></td></tr>
<tr><td>Pattern: </td><td><input name="pattern"></td></tr>
<tr><td>Made IN: </td><td><input name="made_in"></td></tr>
<tr><td>Category: </td><td><input name="category"></td></tr>
<tr><td>Radial: </td><td><input name="radial"></td></tr>
<tr><td>Tubeless: </td><td><input name="tubeless"></td></tr>
<tr><td>Width: </td><td><input name="width"></td></tr>
<tr><td>Profile: </td><td><input name="profile"></td></tr>
<tr><td>Diameter: </td><td><input name="diameter"></td></tr>
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
<form action="module/product.php" method="get">
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