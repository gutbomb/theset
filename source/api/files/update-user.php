<?php
include("../includes/admincheck.php");

if($admin_user==true) {
    $source_name=$post_data['source_name'];
    $source_email=$post_data['source_email'];
    $user_level=$post_data['user_level'];
    $source_id=$request_user_id;

    $sql="UPDATE sources SET source_name='".addslashes($source_name)."', source_email='".addslashes($source_email)."', user_level='".addslashes($user_level)."' WHERE source_id=".$source_id;
    if ($conn->query($sql) === TRUE) {
        $output['status'] = "user updated successfully";
    } else {
        header($_SERVER['SERVER_PROTOCOL'].' 500 Internal Server Error', true, 500);
        $output['status'] = "password not changed";
        $output['errors'] = $conn->error;
    }
} else {
    header($_SERVER['SERVER_PROTOCOL'].' 401 Not Authorized', true, 401);
    $output['status'] = "Not Authorized";
    $output['errors'] = $conn->error;
}
$conn->close();
echo(json_encode($output));