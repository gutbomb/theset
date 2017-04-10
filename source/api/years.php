<?php
	include("../includes/dbconnect.php");
	$where_clause=null;
	$where_active=null; 
	if(isset($_GET['year'])){
		if($where_active==null){
			$where_clause=" WHERE ";
			$where_active=1;
		}
		else
		{
			$where_clause=$where_clause." AND";
		}
		$where_clause=$where_clause." year = ".$_GET['year'];
	}
	if(isset($_GET['year_status'])){
		if($where_active==null){
			$where_clause=" WHERE ";
			$where_active=1;
		}
		else
		{
			$where_clause=$where_clause." AND";
		}
		$where_clause=$where_clause." year_status = '".$_GET['year_status']."'";
	}
	$sql = "SELECT year_id, year, year_status FROM jason_years".$where_clause." ORDER BY year";
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