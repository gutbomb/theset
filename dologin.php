<?php
	include("includes/dbconnect.php");

	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	// Check connection
	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	}
	$sql="SELECT source_id, password_mustchange, source_name FROM jason_sources WHERE source_email='".$_POST["email"]."' AND source_password='".md5($_POST["password"])."'";
	//echo $sql;
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
		$row = $result->fetch_assoc();
		session_start();
		$_SESSION["user_id"]=$row["source_id"];
		$_SESSION["username"]=$row["source_name"];
		if($row["password_mustchange"]==1)
		{
			header("Location:changepassword.php?prevurl=".$_POST["prevurl"]);
		}
		else
		{
			header("Location:".$_POST["prevurl"]);
		}
	}
	else
	{
		header("Location:login.php");
	}
?>