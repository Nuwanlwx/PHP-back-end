<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>Insert title here</title>
</head>
<body>
<form action="module/doctor.php" method="get">
<table>
<tr><td>Doctor Name: </td><td><input name="name"></td></tr>
<tr><td>Speciality: </td><td><input name="speciality"></td></tr>
<tr><td>Charge: </td><td><input name="charge"></td></tr>
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
<form action="module/doctor.php" method="get">
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