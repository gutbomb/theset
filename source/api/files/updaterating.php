<?php
$sql="UPDATE ratings SET rating_score=".$post_data['track_rating']." WHERE rating_id=".$request_rating_id;
if($result = $conn->query($sql)) {
    $output['status'] = "Successfully updated rating";
} else {
    header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
    $output['status'] = "Database Error.";
    $output['errors'] = $conn->error;
}
$conn->close();
echo(json_encode($output));