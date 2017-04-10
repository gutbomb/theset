<?php
	include("includes/login.php");
	include("includes/dbconnect.php");

	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	// Check connection
	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	}
	$sql="UPDATE jason_sources SET password_mustchange=NULL, source_password='".md5($_POST["password"])."' WHERE source_id=".$user_id;
	echo $sql;
	$result = $conn->query($sql);
	header("Location:".$_POST["prevurl"]);
?>