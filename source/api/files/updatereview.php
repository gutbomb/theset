<?php
$sql="UPDATE reviews SET review_text='".addslashes($post_data['review_text'])."' WHERE review_id=".$request_review_id;
if ($result = $conn->query($sql)) {
    $output['status'] = "Successfully updated review";
} else {
    header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
    $output['status'] = "Database Error.";
    $output['errors'] = $conn->error;
}
$conn->close();
echo(json_encode($output));
