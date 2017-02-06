<?php
	include("../includes/dbconnect.php");
	$where_clause=null;
	$where_active=null; 
	if(isset($_GET['album_id'])){
		if($where_active==null){
			$where_clause=" WHERE ";
			$where_active=1;
		}
		else
		{
			$where_clause=$where_clause." AND";
		}
		$where_clause=$where_clause." comment_album = ".$_GET['album_id'];
	}
	if(isset($_GET['source_id'])){
		if($where_active==null){
			$where_clause=" WHERE ";
			$where_active=1;
		}
		else
		{
			$where_clause=$where_clause." AND";
		}
		$where_clause=$where_clause." comment_source = '".$_GET['source_id']."'";
	}
	if(isset($_GET['comment_id'])){
		if($where_active==null){
			$where_clause=" WHERE ";
			$where_active=1;
		}
		else
		{
			$where_clause=$where_clause." AND";
		}
		$where_clause=$where_clause." comment_id = '".$_GET['comment_id']."'";
	}
	$sql = "SELECT comment_id, comment_source, jason_sources.source_name, comment_album, jason_albums.album_name, jason_artists.artist_name, jason_artists. artist_id, comment_text, DATE_FORMAT(comment_date,'%W %M %Y %l:%i %p') AS comment_date FROM jason_comments JOIN jason_albums ON comment_album = jason_albums.album_id JOIN jason_artists ON jason_albums.artist_id = jason_artists.artist_id JOIN jason_sources ON jason_sources.source_id = comment_source".$where_clause." ORDER BY comment_date";
	//echo $sql;
	$conn = new mysqli($servername, $username, $password, $dbname);
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
	    while($row = $result->fetch_assoc()) {
	    	$rows[] = $row;
		}				    	
    }
    $conn->close();
    //var_dump($rows);
	header('Content-type: application/json');
	header('Access-Control-Allow-Origin: *');
	echo(json_encode($rows));
?>