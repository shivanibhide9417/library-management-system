<?php
$db=mysqli_connect("localhost","root","","library");
if(!$db)
{
	
	die("connection failed" . mysqli_connect_error());

}
echo "connected successfully";
?>