<html>
<head>
<title>Product Category Mapping Test Page</title>
</head>
<body>
<form action="module/product_category.php" method="get">
<table>
<tr><td>Category ID: </td><td><input name="category_id"></td></tr>
<tr><td>Product ID: </td><td><input name="product_id"></td></tr>
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
<form action="module/product_category.php" method="get">
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