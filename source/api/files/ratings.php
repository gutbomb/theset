<?php
$where_clause=null;
$where_active=null;
if(isset($request_track_id)){
    if($where_active==null){
        $where_clause.=" WHERE ";
        $where_active=1;
    }
    else
    {
        $where_clause.=" AND";
    }
    $where_clause.=" ratings.track_id = ".$request_track_id;
}
if(isset($request_rating_source)){
    if($where_active==null){
        $where_clause.=" WHERE ";
        $where_active=1;
    }
    else
    {
        $where_clause.=" AND";
    }
    $where_clause.=" ratings.source_id = ".$request_rating_source;
}
if(isset($request_album_id)){
    if($where_active==null){
        $where_clause.=" WHERE ";
        $where_active=1;
    }
    else
    {
        $where_clause.=" AND";
    }
    $where_clause.=" tracks.album_id = ".$request_album_id;
}
$sql = "SELECT tracks.album_id AS album_id, rating_score, ratings.source_id, sources.source_name, ratings.track_id, rating_id FROM ratings JOIN tracks ON ratings.track_id = tracks.track_id JOIN sources ON sources.source_id = ratings.source_id".$where_clause." ORDER BY track_number";
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