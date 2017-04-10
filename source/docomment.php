<?php
	$loginrequired=1;
	include("includes/dbconnect.php");
	include("includes/login.php");
	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	// Check connection
	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	}
	$sql="INSERT INTO jason_comments (comment_text, comment_album, comment_source, comment_date) VALUES ('".addslashes($_POST["comment_text"])."', '".$_POST["album_id"]."', '".$user_id."', NOW());";
	
	$result = $conn->query($sql);
	echo "<h4>".$sourcename."</h4><p>".$_POST["comment_text"]."</p>";
	//header("Location:submitratings.php?album_id=".$_POST["album_id"]."#tracklist");
?>