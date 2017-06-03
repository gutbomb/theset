<?php
$sql="SELECT artists.artist_id, albums.album_id, artist_name, album_name, (SELECT COUNT(track_id) FROM tracks WHERE album_id=albums.album_id) AS track_count, (SELECT COUNT(rating_id) FROM ratings WHERE album_id=albums.album_id AND ratings.source_id=2) AS jason_rating_count, (SELECT COUNT(rating_id) FROM ratings WHERE album_id=albums.album_id AND ratings.source_id=4) AS david_rating_count, (SELECT COUNT(review_id) FROM reviews WHERE reviews.album_id=albums.album_id AND review_source=2) AS jason_review_count, (SELECT COUNT(review_id) FROM reviews WHERE reviews.album_id=albums.album_id AND review_source=4) AS david_review_count FROM albums JOIN artists ON artists.artist_id=albums.artist_id WHERE album_year=(SELECT year_id FROM years WHERE year_status='active')";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $rows[]=$row;
    }
}
$conn->close();
echo(json_encode($rows));