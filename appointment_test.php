<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>Appointment Management</title>
</head>
<body>
<form action="module/appointment.php" method="get">
<table>
<tr><td>Schedule ID: </td><td><input name="schedule"></td></tr>
<tr><td>Appointment No: </td><td><input name="appointment"></td></tr>
<tr><td>Patient Name: </td><td><input name="patient"></td></tr>
<tr><td>Contact No: </td><td><input name="telephone"></td></tr>
<tr><td>Patient/Gardian ID No: </td><td><input name="nic"></td></tr>
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
<form action="module/appointment.php" method="get">
<table>
<tr><td>Start Date: </td><td><input name="start_date" value="0"></td></tr>
<tr><td>End Date: </td><td><input name="end_date" value="10"></td></tr>
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