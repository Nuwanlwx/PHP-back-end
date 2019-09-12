<html>
<head>
<title>Sale Test Page</title>
</head>
<body>
<form action="module/sale.php" method="get">
<table>
<tr><td>Customer ID: </td><td><input name="customer_id"></td></tr>
<tr><td>Product ID: </td><td><input name="product_id"></td></tr>
<tr><td>Quantity: </td><td><input name="qty"></td></tr>
<tr><td>Date: </td><td><input name="sale_date"></td></tr>
<tr><td>Agent ID: </td><td><input name="agent_id"></td></tr>
<tr><td>Vehicle Reg. Num: </td><td><input name="vehicle_no"></td></tr>
<tr><td>Unit Price @ Sale: </td><td><input name="unit_sale_price"></td></tr>
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
<form action="module/sale.php" method="get">
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