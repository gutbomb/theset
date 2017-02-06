<?php
	include("../includes/dbconnect.php");
	$where_clause=null;
	$where_active=null;
	if(isset($_GET['year'])){
		if($where_active==null){
			$where_clause=" WHERE";
			$where_active=1;
		}
		else
		{
			$where_clause=$where_clause." AND";
		}
		$where_clause=$where_clause." year = '".$_GET['year']."'";
	}
	if(isset($_GET['artist_id'])){
		if($where_active==null){
			$where_clause=" WHERE ";
			$where_active=1;
		}
		else
		{
			$where_clause=$where_clause." AND";
		}
		$where_clause=$where_clause." jason_albums.artist_id = ".$_GET['artist_id'];
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
	if(isset($_GET['album_source'])){
		if($where_active==null){
			$where_clause=" WHERE ";
			$where_active=1;
		}
		else
		{
			$where_clause=$where_clause." AND";
		}
		$where_clause=$where_clause." album_source = ".$_GET['album_source'];
	}
	if(isset($_GET['track_id'])){
		if($where_active==null){
			$where_clause=" WHERE ";
			$where_active=1;
		}
		else
		{
			$where_clause=$where_clause." AND";
		}
		$where_clause=$where_clause." row_id = ".$_GET['track_id'];
	}
	$sql = "SELECT jason_years.year, row_id as track_id, track_name, track.album_id, jason_albums.album_name, jason_albums.artist_id, jason_artists.artist_name, jason_sources.source_id, jason_sources.source_name FROM `track` JOIN jason_albums ON jason_albums.album_id = track.album_id JOIN jason_artists ON jason_artists.artist_id = jason_albums.artist_id JOIN jason_sources ON jason_sources.source_id = jason_albums.album_source JOIN jason_years ON jason_years.year_id = jason_albums.album_year".$where_clause;
	// echo $sql;
	$conn = new mysqli($servername, $username, $password, $dbname);
	$result = $conn->query($sql) or die(mysqli_error());
	if ($result->num_rows > 0) {
	    while($row = $result->fetch_assoc()) {
	    	$ratings=null;
	    	$ratingssql="SELECT rating_score, rating_source, jason_sources.source_name FROM jason_ratings JOIN jason_sources ON jason_sources.source_id = jason_ratings.rating_source WHERE rating_track=".$row["track_id"]." ORDER BY rating_source";
	    	$ratingsconn = new mysqli($servername, $username, $password, $dbname);
	    	$ratingsresult = $ratingsconn->query($ratingssql) or die(mysqli_error());
	    	if ($ratingsresult->num_rows > 0) {
		    	while($ratingsrow = $ratingsresult->fetch_assoc()) {
		    		if ($ratingsrow['rating_source']=="2") {
		    			$jasonratings = $ratingsrow['rating_score'];
		    		}
		    		if ($ratingsrow['rating_source']=="4") {
		    			$davidratings = $ratingsrow['rating_score'];
		    		}
		    	}
		    }
		    $ratingsconn->close();
	    	//var_dump($row);
	    	$row["jason_rating"] = $jasonratings;
	    	$row["david_rating"] = $davidratings;
	    	$davidratings=null;
	    	$jasonratings=null;
	    	$rows[] = $row;
	    	
		}				    	
    }
    $conn->close();
	header('Content-type: application/json');
	header('Access-Control-Allow-Origin: *');
	echo(json_encode($rows));
?>