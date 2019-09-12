<html>
<head>
<title>Feedback Test Page</title>
</head>
<body>
<form action="module/feedback.php" method="get">
<table>
<!-- FIELD_ID, FIELD_CUST_ID, FIELD_NAME, FIELD_TEL, FIELD_COMMENT -->
<tr><td>ID: </td><td><input name="id"></td></tr>
<tr><td>Customer ID: </td><td><input name="customer_id"></td></tr>
<tr><td>Customer Name: </td><td><input name="name"></td></tr>
<tr><td>Customer Contact: </td><td><input name="telephone"></td></tr>
<tr><td>Feedback: </td><td><input name="comment"></td></tr>
<tr><td>Date/Time: </td><td><input name="timestamp"></td></tr>
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
<form action="module/feedback.php" method="get">
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