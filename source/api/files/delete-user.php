<?php
include("../includes/admincheck.php");

if($admin_user==true) {
    $sql = "DELETE FROM users WHERE user_id=".$request_user_id;
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    if ($conn->query($sql) === TRUE) {
        $output['status'] = "User deleted successfully.";
    } else {
        header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
        $output['status'] = "Database Error.";
        $output['errors'] = $conn->error;
    }
} else {
    header($_SERVER['SERVER_PROTOCOL'].' 401 Not Authorized', true, 401);
    $output['status'] = "Not Authorized";
    $output['errors'] = $conn->error;
}
$conn->close();
echo(json_encode($output));