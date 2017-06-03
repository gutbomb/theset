<?php
$sql="SELECT user_level FROM sources WHERE source_id=".$token_data->id;
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if ($row['user_level'] === 'admin') {
        $admin_user=true;
    }
}