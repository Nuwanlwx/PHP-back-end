<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>Insert title here</title>
</head>

<body>
<form action="module/promotion.php" method="get" enctype="multipart/form-data">
Select image to upload:
    <input type="file" name="fileToUpload" id="fileToUpload">
<table>
<tr><td>Title: </td><td><input placeholder="Enter promotion title" name="title"></td></tr>
<tr><td>Message: </td><td><textarea placeholder="Enter the promotion details" name="body"></textarea></td></tr>
<tr>
	<td></td>
	<td>
		<input type=submit name="action" value="add">
	</td>
</tr>
</table>
</form>

<!-- <form action="module/upload.php" method="post" enctype="multipart/form-data">
    Select image to upload:
    <input type="file" name="fileToUpload" id="fileToUpload">
    <input type="submit" value="Upload Image" name="submit">
</form> -->
</body>

</html>