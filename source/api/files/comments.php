<?php
$where_clause=null;
$where_active=null;
if(isset($request_album_id)){
    if($where_active==null){
        $where_clause.=" WHERE ";
        $where_active=1;
    }
    else
    {
        $where_clause.=" AND";
    }
    $where_clause.=" comment_album = ".$request_album_id;
}
if(isset($request_source_id)){
    if($where_active==null){
        $where_clause.=" WHERE ";
        $where_active=1;
    }
    else
    {
        $where_clause.=" AND";
    }
    $where_clause.=" comment_source = '".$request_source_id."'";
}
if(isset($request_comment_id)){
    if($where_active==null){
        $where_clause.=" WHERE ";
        $where_active=1;
    }
    else
    {
        $where_clause.=" AND";
    }
    $where_clause.=" comment_id = '".$request_comment_id."'";
}
$sql = "SELECT comment_id, comment_source, sources.source_name, comment_album, albums.album_name, artists.artist_name, artists. artist_id, comment_text, DATE_FORMAT(comment_date,'%W %M %Y %l:%i %p') AS comment_date FROM comments JOIN albums ON comment_album = albums.album_id JOIN artists ON albums.artist_id = artists.artist_id JOIN sources ON sources.source_id = comment_source".$where_clause." ORDER BY comment_date";
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