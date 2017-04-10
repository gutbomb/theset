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
		$where_clause=$where_clause." album_id = ".$_GET['album_id'];
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
		    		
	$sql = "SELECT album_id, album_blurb, jason_years.year as year, jason_artists.artist_id, source_name, album_name, artist_name, album_genre, album_source, album_genre FROM jason_albums JOIN jason_artists ON jason_artists.artist_id = jason_albums.artist_id JOIN jason_years ON jason_years.year_id = jason_albums.album_year JOIN jason_sources ON jason_sources.source_id = jason_albums.album_source".$where_clause." ORDER BY jason_years.year";
	//echo $sql;
	$conn = new mysqli($servername, $username, $password, $dbname);
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
	    while($row = $result->fetch_assoc()) {
	    	$reviews=null;
	    	$reviewssql="SELECT review_text, source_name AS review_source_name, source_id AS review_source_id FROM jason_reviews JOIN jason_sources ON jason_sources.source_id = jason_reviews.review_source WHERE review_album=".$row["album_id"];
	    	$reviewsconn = new mysqli($servername, $username, $password, $dbname);
	    	$reviewsresult = $reviewsconn->query($reviewssql) or die(mysqli_error());
	    	if ($reviewsresult->num_rows > 0) {
		    	while($reviewsrow = $reviewsresult->fetch_assoc()) {
		    		$finalscore=0;
		    		$ratingcount=0;
			    	$finalscore_sql="SELECT rating_score FROM jason_ratings JOIN track ON track.row_id = jason_ratings.rating_track WHERE jason_ratings.album_id=".$row["album_id"]." AND rating_source=".$reviewsrow["review_source_id"];
			    	$finalscore_conn = new mysqli($servername, $username, $password, $dbname);
			    	$finalscore_result = $finalscore_conn->query($finalscore_sql);
					if ($finalscore_result->num_rows > 0) {
					    while($finalscore_row = $finalscore_result->fetch_assoc()) {
					    	$finalscore=$finalscore+$finalscore_row["rating_score"];
					    	$ratingcount++;
							
					    }
					    $reviewsrow['score']=$finalscore/$ratingcount;
					    //var_dump($reviewsrow['score']);
					}
					$finalscore_conn->close();
					$reviews[] = $reviewsrow;
		    	}
		    }
		    $reviewsconn->close();
		    if($reviews!=null) {
	    		$row["reviews"] = $reviews;
	    	}
	    	$rows[] = $row;
		}				    	
    }
    //var_dump($rows);
    $conn->close();
	header('Content-type: application/json');
	header('Access-Control-Allow-Origin: *');
	echo(json_encode($rows));
?>