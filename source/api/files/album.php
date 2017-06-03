<?php
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
    $where_clause.=" album_id = ".$request_album_id;
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

$sql = "SELECT album_id, album_blurb, years.year as year, artists.artist_id, source_name, album_name, artist_name, album_genre, album_source, album_genre FROM albums JOIN artists ON artists.artist_id = albums.artist_id JOIN years ON years.year_id = albums.album_year JOIN sources ON sources.source_id = albums.album_source".$where_clause." ORDER BY years.year";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $reviews=null;
        $reviewssql="SELECT review_id, review_text, album_id, source_name AS review_source_name, source_id AS review_source_id FROM reviews JOIN sources ON sources.source_id = reviews.review_source WHERE album_id=".$row["album_id"];
        $reviewsresult = $conn->query($reviewssql);
        if ($reviewsresult->num_rows > 0) {
            while($reviewsrow = $reviewsresult->fetch_assoc()) {
                $finalscore=0;
                $ratingcount=0;
                $finalscore_sql="SELECT rating_score FROM ratings JOIN tracks ON tracks.track_id = ratings.track_id WHERE ratings.album_id=".$row["album_id"]." AND source_id=".$reviewsrow["review_source_id"];
                $finalscore_result = $conn->query($finalscore_sql);
                if ($finalscore_result->num_rows > 0) {
                    while($finalscore_row = $finalscore_result->fetch_assoc()) {
                        $finalscore=$finalscore+$finalscore_row["rating_score"];
                        $ratingcount++;

                    }
                    $reviewsrow['score']=$finalscore/$ratingcount;
                }
                $reviews[] = $reviewsrow;
            }
        }
        if($reviews!=null) {
            $row["reviews"] = $reviews;
        }
        $rows[] = $row;
    }
} else {
    header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found', true, 404);
}
$conn->close();
echo(json_encode($rows));
