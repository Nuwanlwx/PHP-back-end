<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>Schedule Management</title>
</head>
<body>
<form action="module/schedule.php" method="get">
<table>
<tr><td>Doctor ID: </td><td><input name="doctor"></td></tr>
<tr><td>Timestamp: </td><td><input name="timestamp" value="<?php echo time(); ?>"></td></tr>
<tr><td>No of patients: </td><td><input name="patient"></td></tr>
<tr>
	<td></td>
	<td>
		<input type=submit name="action" value="add">
		<input type=submit name="action" value="remove">
	</td>
</tr>
<tr><td>Start Date: </td><td><input name="min_timestamp" value="0"></td></tr>
<tr><td>End Date: </td><td><input name="max_timestamp" value="<?php echo time(); ?>"></td></tr>
<tr>
	<td></td>
	<td>
		<input type=submit name="action" value="search">
	</td>
</tr>
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