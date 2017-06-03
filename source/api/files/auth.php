<?php
$login_username=$post_data['username'];
$login_password=$post_data['password'];

$sql="SELECT source_id, source_password, source_email FROM sources WHERE source_email='".$login_username."'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if (password_verify($login_password, $row['source_password'])) {
        $token = array();
        $token['id'] = $row['source_id'];
        $output['token'] = JWT::encode($token, 'jwt_key');
    } else {
        header($_SERVER['SERVER_PROTOCOL'].' 401 Not Authorized', true, 401);
        $output['status'] = "not logged in";
        $output['errors'] = "invalid";
    }
}
$conn->close();
echo(json_encode($output));