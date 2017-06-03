<?php
$sql = "SELECT year, year_id FROM years WHERE year_status='previous'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $previousyear=$row["year"];
        $previousyearid=$row["year_id"];
    }
}

$sql = "SELECT year, year_id FROM years WHERE year_status='active'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        $activeyear=$row["year"];
        $activeyearid=$row["year_id"];
    }
}
else
{
    $newyearsql = "SELECT year, year_id FROM years WHERE year_status='incomplete' ORDER BY RAND() LIMIT 1";
    $newyearresult = $conn->query($newyearsql);
    if ($newyearresult->num_rows > 0) {
        // output data of each row
        while($newyearrow = $newyearresult->fetch_assoc()) {
            $activeyear=$newyearrow["year"];
            $activeyearid=$newyearrow["year_id"];
            $updatesql = "UPDATE years SET year_status = 'active' WHERE year_id = '".$activeyearid."'";
            $conn->query($updatesql);
        }
    }
}
$ratingcount=0;
$trackcount=0;
$reviewcount=0;
$expectedreviews=0;
$count1sql = "SELECT album_id FROM albums WHERE album_year='".$activeyearid."'";
$count1result = $conn->query($count1sql);
if ($count1result->num_rows > 0) {
    while($count1row = $count1result->fetch_assoc()) {
        $count2sql="SELECT COUNT(track_id) AS tracks FROM tracks WHERE album_id='".$count1row["album_id"]."'";
        $count2result = $conn->query($count2sql);
        if ($count2result->num_rows > 0) {
            while($count2row = $count2result->fetch_assoc()) {
                $trackcount=$trackcount+$count2row["tracks"];
            }
        }
        $count3sql="SELECT COUNT(rating_id) AS ratings FROM ratings WHERE album_id='".$count1row["album_id"]."'";
        $count3result = $conn->query($count3sql);
        if ($count3result->num_rows > 0) {
            while($count3row = $count3result->fetch_assoc()) {
                $ratingcount=$ratingcount+$count3row["ratings"];
            }
        }
        $count4sql="SELECT COUNT(review_id) AS reviews FROM reviews WHERE album_id='".$count1row["album_id"]."'";
        $count4result = $conn->query($count4sql);
        if ($count4result->num_rows > 0) {
            while($count4row = $count4result->fetch_assoc()) {
                $reviewcount=$reviewcount+$count4row["reviews"];
            }
        }
        $count5sql="SELECT COUNT(album_id) AS albums FROM albums WHERE album_year='".$activeyearid."'";
        $count5result = $conn->query($count5sql);
        if ($count5result->num_rows > 0) {
            while($count5row = $count5result->fetch_assoc()) {
                $albumscount=$count5row["albums"];
            }
        }
    }
}
$targetcount=($trackcount*2)+($albumscount*2);
$count=$reviewcount+$ratingcount;
if($count==$targetcount){
    $newyearsql = "SELECT year, year_id FROM years WHERE year_status='incomplete' ORDER BY RAND() LIMIT 1";
    $newyearresult = $conn->query($newyearsql);
    if ($newyearresult->num_rows > 0) {
        while($newyearrow = $newyearresult->fetch_assoc()) {
            $updatesql = "UPDATE years SET year_status = 'complete' WHERE year_id = '".$previousyearid."'";
            $conn->query($updatesql);
            $updatesql = "UPDATE years SET year_status = 'previous' WHERE year_id = '".$activeyearid."'";
            $conn->query($updatesql);
            $activeyear=$newyearrow["year"];
            $activeyearid=$newyearrow["year_id"];
            $updatesql = "UPDATE years SET year_status = 'active' WHERE year_id = '".$activeyearid."'";
            $conn->query($updatesql);
        }
    }
    $to      = 'gutbomb@gmail.com,davidklink@hotmail.com';
    $subject = 'The Set - Ratings and Reviews for '.$activeyear.' are in';
    $message = 'Hello,\n\rAll ratings reviews for '.$activeyear.' are in.  Visit http://gutbomb.net/theset/ to check them out and see what year we\'ll be doing this week!';
    $headers = 'From: gutbomb@gmail.com' . "\r\n" .
        'Reply-To: gutbomb@gmail.com' . "\r\n" .
        'X-Mailer: PHP/' . phpversion();

    mail($to, $subject, $message, $headers);
    header("Location:./");
}

$where_clause=null;
$where_active=null;
if(isset($request_year)) {
    if ($where_active == null) {
        $where_clause = " WHERE ";
        $where_active = 1;
    } else {
        $where_clause .= " AND";
    }
    switch ($request_year) {
        case 'active':
            $where_clause .= " year_status = 'active'";
            break;

        case 'previous':
            $where_clause .= " year_status = 'previous'";
            break;

        default:
            $where_clause .= " year = " . $request_year;
    }
}
$sql = "SELECT year_id, year, year_status FROM years".$where_clause." ORDER BY year";
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
