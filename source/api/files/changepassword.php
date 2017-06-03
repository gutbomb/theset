<?php
$old_password=$post_data['oldPassword'];
$new_password=$post_data['newPassword'];
$sql="SELECT source_password FROM sources WHERE source_id=".$token_data->id;
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if (password_verify($old_password, $row['source_password'])) {
        $sql="UPDATE sources SET password_mustchange=NULL, source_password='".addslashes(password_hash($new_password, PASSWORD_DEFAULT))."' WHERE source_id=".$token_data->id;
        if ($conn->query($sql) === TRUE) {
            $output['status'] = "password changed successfully";
        } else {
            header($_SERVER['SERVER_PROTOCOL'].' 500 Internal Server Error', true, 500);
            $output['status'] = "password not changed";
            $output['errors'] = $conn->error;
        }
    } else {
        header($_SERVER['SERVER_PROTOCOL'].' 401 Not Authorized', true, 401);
        $output['status'] = "password not changed";
        $output['errors'] = "current password invalid";
    }
}
$conn->close();
echo(json_encode($output));
