<?php
	include("includes/login.php");
	include("includes/dbconnect.php");

	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	// Check connection
	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	}
	if($_POST["method"]=="insert")
	{
		$sql="INSERT INTO jason_reviews (review_text, review_album, review_source) VALUES ('".addslashes($_POST["review_text"])."', '".$_POST["album_id"]."', '".$user_id."');";
	}
	else
	{
		$sql="UPDATE jason_reviews SET review_text = '".addslashes($_POST["review_text"])."' WHERE review_id = ".$_POST["review_id"];
	}
	//echo $sql;
	$result = $conn->query($sql);
	if($_POST["method"]=="insert")
	{
		echo($conn->insert_id);
	}
	//header("Location:submitratings.php?album_id=".$_POST["album_id"]."#tracklist");
?>