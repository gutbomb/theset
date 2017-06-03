<?php
include("../includes/dbconnect.php");
$where_clause=null;
$where_active=null;
if(isset($request_year)){
    if($where_active==null){
        $where_clause.=" WHERE";
        $where_active=1;
    }
    else
    {
        $where_clause.=" AND";
    }
    $where_clause.=" year = '".$request_year."'";
}
if(isset($request_artist_id)){
    if($where_active==null){
        $where_clause.=" WHERE ";
        $where_active=1;
    }
    else
    {
        $where_clause.=" AND";
    }
    $where_clause.=" albums.artist_id = ".$request_artist_id;
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
if(isset($request_album_source)){
    if($where_active==null){
        $where_clause.=" WHERE ";
        $where_active=1;
    }
    else
    {
        $where_clause.=" AND";
    }
    $where_clause.=" album_source = ".$request_album_source;
}
if(isset($request_track_id)){
    if($where_active==null){
        $where_clause.=" WHERE ";
        $where_active=1;
    }
    else
    {
        $where_clause.=" AND";
    }
    $where_clause.=" track_id = ".$request_track_id;
}
$sql = "SELECT years.year, track_id, track_name, tracks.album_id, albums.album_name, albums.artist_id, artists.artist_name, sources.source_id, sources.source_name FROM tracks JOIN albums ON albums.album_id = tracks.album_id JOIN artists ON artists.artist_id = albums.artist_id JOIN sources ON sources.source_id = albums.album_source JOIN years ON years.year_id = albums.album_year".$where_clause." ORDER BY track_number";
$jasonratings=null;
$davidratings=null;
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $ratings=null;
        $ratingssql="SELECT rating_score, ratings.source_id, sources.source_name FROM ratings JOIN sources ON sources.source_id = ratings.source_id WHERE ratings.track_id=".$row["track_id"]." ORDER BY ratings.source_id";
        $ratingsresult = $conn->query($ratingssql);
        if ($ratingsresult->num_rows > 0) {
            while($ratingsrow = $ratingsresult->fetch_assoc()) {
                if ($ratingsrow['source_id']=="2") {
                    $jasonratings = $ratingsrow['rating_score'];
                }
                if ($ratingsrow['source_id']=="4") {
                    $davidratings = $ratingsrow['rating_score'];
                }
            }
        }
        $row["jason_rating"] = $jasonratings;
        $row["david_rating"] = $davidratings;
        $davidratings=null;
        $jasonratings=null;
        $rows[] = $row;

    }
}
$conn->close();
echo(json_encode($rows));