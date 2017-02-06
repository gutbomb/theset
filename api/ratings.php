<?php
	include("../includes/dbconnect.php");
	$where_clause=null;
	$where_active=null;
	if(isset($_GET['track_id'])){
		if($where_active==null){
			$where_clause=" WHERE ";
			$where_active=1;
		}
		else
		{
			$where_clause=$where_clause." AND";
		}
		$where_clause=$where_clause." rating_track = ".$_GET['track_id'];
	}
	if(isset($_GET['rating_source'])){
		if($where_active==null){
			$where_clause=" WHERE ";
			$where_active=1;
		}
		else
		{
			$where_clause=$where_clause." AND";
		}
		$where_clause=$where_clause." rating_source = ".$_GET['rating_source'];
	}
	if(isset($_GET['album_id'])){
		if($where_active==null){
			$where_clause=" WHERE ";
			$where_active=1;
		}
		else
		{
			$where_clause=$where_clause." AND";
		}
		$where_clause=$where_clause." track.album_id = ".$_GET['album_id'];
	}
	$sql = "SELECT track.album_id AS album_id, rating_score, rating_source, jason_sources.source_name, rating_track FROM jason_ratings JOIN track ON rating_track = row_id JOIN jason_sources ON jason_sources.source_id = jason_ratings.rating_source".$where_clause." ORDER BY rating_track";
	// echo $sql;
	$conn = new mysqli($servername, $username, $password, $dbname);
	$result = $conn->query($sql) or die(mysqli_error());
	if ($result->num_rows > 0) {
	    while($row = $result->fetch_assoc()) {
	    	$rows[] = $row;
		}				    	
    }
    $conn->close();
	header('Content-type: application/json');
	header('Access-Control-Allow-Origin: *');
	echo(json_encode($rows));
?>