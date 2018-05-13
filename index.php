<?php 

$con = mysqli_connect('localhost','root','mysql','social'); //connection variable

if(mysqli_connect_errno($con))
{
	echo "Failed to connect".mysqli_connect_errno();
}

$query = mysqli_query($con,"INSERT INTO test(name) VALUES('Calvin')");





 ?>





<!DOCTYPE html>
<html>
<head>
	<title>Swirlfeed</title>
</head>
<body>
Hello AJ!
</body>
</html>