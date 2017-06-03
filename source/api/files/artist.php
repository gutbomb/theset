<?php
$where_clause=null;
$where_active=null;
if(isset($request_artist_id)){
    if($where_active==null){
        $where_clause.=" WHERE ";
        $where_active=1;
    }
    else
    {
        $where_clause.=" AND";
    }
    $where_clause.=" artist_id = ".$request_artist_id;
}
$sql = "SELECT artist_id, artist_name, artist_blurb FROM artists".$where_clause." ORDER BY artist_name";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }
} else {
    header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found', true, 404);
}
$conn->close();
echo(json_encode($rows));