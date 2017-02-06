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
		$sql="INSERT INTO jason_ratings (rating_track, rating_source, rating_score, album_id) VALUES ('".$_POST["track_id"]."', '".$user_id."', '".$_POST["ratingscore"]."', '".$_POST["album_id"]."');";
	}
	else
	{
		$sql="UPDATE jason_ratings SET rating_score = '".$_POST["ratingscore"]."' WHERE rating_id = ".$_POST["rating_id"];
	}
	//echo $sql;
	$result = $conn->query($sql);
	if($_POST["method"]=="insert")
	{
		echo($conn->insert_id);
	}
	//header("Location:submitratings.php?album_id=".$_POST["album_id"]."#tracklist");
?>