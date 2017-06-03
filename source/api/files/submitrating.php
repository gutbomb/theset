<?php
$sql="INSERT INTO ratings (track_id, rating_score, source_id, album_id) VALUES (".$post_data['track_id'].", ".$post_data['track_rating'].", ".$token_data->id.", ".$post_data['track_album'].")";
if($result = $conn->query($sql)) {
    $output['status'] = "Successfully submitted rating";
} else {
    header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
    $output['status'] = "Database Error.";
    $output['errors'] = $conn->error;
}
$conn->close();
echo(json_encode($output));