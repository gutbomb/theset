<?php
$where_clause=null;
$where_active=null;

if(isset($request_source_id)){
    if($where_active==null){
        $where_clause=" WHERE ";
        $where_active=1;
    }
    else
    {
        $where_clause=$where_clause." AND";
    }
    $where_clause=$where_clause." source_id = ".$request_source_id;
}

$sql = "SELECT source_id, source_email, source_name, password_mustchange, user_level FROM sources".$where_clause." ORDER BY source_email";

$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }
} else {
    header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found', true, 404);
}
$conn->close();
echo(json_encode($rows));