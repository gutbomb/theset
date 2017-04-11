<?php
    include("../includes/dbconnect.php");
    include("../includes/jwt_helper.php");
    $_POST = json_decode(file_get_contents('php://input'), true);
    $login_username=$_POST['username'];
    $login_password=$_POST['password'];
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $sql="SELECT source_id, password_mustchange, source_name FROM jason_sources WHERE source_email='".$login_username."' AND source_password='".md5($login_password)."'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if($row["password_mustchange"]==1) {
            // must change password
        } else {
            // login successful
            $token = array();
            $token['id']=$row['source_id'];
            $output['status'] = "logged in";
            $output['token'] = JWT::encode($token, 'jwt_key');

        }
    } else {
        header('HTTP/1.1 401 Unauthorized', true, 401);
        $output['status'] = "not logged in";
        $output['errors'] = "invalid";
    }
    $conn->close();
    echo(json_encode($output));
?>