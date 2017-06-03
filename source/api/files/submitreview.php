<?php
$sql="INSERT INTO reviews (album_id, review_text, review_source) VALUES (".$post_data['review_album'].", '".addslashes($post_data['review_text'])."', ".$token_data->id.")";
if ($result = $conn->query($sql)) {
    $output['status'] = "Successfully submitted review";
} else {
    header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
    $output['status'] = "Database Error.";
    $output['errors'] = $conn->error;
}
$conn->close();
echo(json_encode($output));