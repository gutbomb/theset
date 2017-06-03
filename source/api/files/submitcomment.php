<?php
$sql="INSERT INTO comments (comment_text, comment_album, comment_source) VALUES ('".addslashes($post_data["comment_text"])."', ".$post_data["album_id"].", ".$token_data->id.")";
if($result = $conn->query($sql)) {
    $output['status'] = "Successfully submitted comment";
} else {
    header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
    $output['status'] = "Database Error.";
    $output['errors'] = $conn->error;
}
$conn->close();
echo(json_encode($output));