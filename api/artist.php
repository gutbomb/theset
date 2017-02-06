<?php
	include("../includes/dbconnect.php");
	$where_clause=null;
	$where_active=null; 
	if(isset($_GET['artist_id'])){
		if($where_active==null){
			$where_clause=" WHERE ";
			$where_active=1;
		}
		else
		{
			$where_clause=$where_clause." AND";
		}
		$where_clause=$where_clause." artist_id = ".$_GET['artist_id'];
	}
	$sql = "SELECT artist_id, artist_name FROM jason_artists".$where_clause." ORDER BY artist_name";
	//echo $sql;
	$conn = new mysqli($servername, $username, $password, $dbname);
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
	    while($row = $result->fetch_assoc()) {
	    	$rows[] = $row;
		}				    	
    }
    //var_dump($rows);
    $conn->close();
	header('Content-type: application/json');
	header('Access-Control-Allow-Origin: *');
	echo(json_encode($rows));
?>