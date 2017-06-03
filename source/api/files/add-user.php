<?php
include("../includes/admincheck.php");

if($admin_user==true) {
    $first_name=$post_data['first_name'];
    $last_name=$post_data['last_name'];
    $email=$post_data['email'];
    $user_level=$post_data['user_level'];

    $sql="SELECT email FROM users WHERE email='".$email."'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        header($_SERVER['SERVER_PROTOCOL'] . ' 409 Conflict', true, 409);
        $output['status'] = "User already exists.";
    } else {
        $sql = "INSERT INTO users (first_name, last_name, email, user_level, password_must_change, password) VALUES ('" . addslashes($first_name) . "', '" . addslashes($last_name) . "', '" . addslashes($email) . "', '" . addslashes($user_level) . "', 1, '" . addslashes(password_hash('password', PASSWORD_DEFAULT)) . "')";
        if ($conn->query($sql) === TRUE) {
            $output['status'] = "User added successfully.";
        } else {
            header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
            $output['status'] = "Database Error.";
            $output['errors'] = $conn->error;
        }
    }
} else {
    header($_SERVER['SERVER_PROTOCOL'].' 403 Forbidden', true, 403);
    $output['status'] = "Not Authorized";
}
$conn->close();
echo(json_encode($output));