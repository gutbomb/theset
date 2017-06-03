<?php
include("../includes/dbconnect.php");

header("Access-Control-Allow-Orgin: *");
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json");

$requestParts = explode('/',$_GET['request']);
$category = $requestParts[0];
$get_data = $requestParts[1];

$post_data=json_decode(file_get_contents('php://input'), true);

if ($category!=='reset-password') {
    include("../includes/jwt_helper.php");
}

switch($category) {
    case 'album':
        $request_album_id=$get_data;
        $request_year=$_GET['year'];
        $request_artist_id=$_GET['artist_id'];
        $request_album_source=$_GET['album_source'];
        switch($_SERVER['REQUEST_METHOD']) {
            case 'GET':
                include('./files/album.php');
                break;
//            case 'POST':
//                include("../includes/authcheck.php");
//                include("../includes/admincheck.php");
//                break;
//            case 'PUT':
//                include("../includes/authcheck.php");
//                include("../includes/admincheck.php");
//                break;
//            case 'DELETE':
//                include("../includes/authcheck.php");
//                include("../includes/admincheck.php");
//                break;
        }
        break;

    case 'artist':
        $request_artist_id=$get_data;
        switch($_SERVER['REQUEST_METHOD']) {
            case 'GET':
                include('./files/artist.php');
                break;
//            case 'POST':
//                include("../includes/authcheck.php");
//                include("../includes/admincheck.php");
//                break;
//            case 'PUT':
//                include("../includes/authcheck.php");
//                include("../includes/admincheck.php");
//                break;
//            case 'DELETE':
//                include("../includes/authcheck.php");
//                include("../includes/admincheck.php");
//                break;
        }
        break;

    case 'auth':
        include('./files/auth.php');
        break;

    case 'change-password':
        include("../includes/authcheck.php");
        include('./files/changepassword.php');
        break;

    case 'comment':
        $request_comment_id=$get_data;
        $request_album_id=$_GET['album_id'];
        $request_source_id=$_GET['source_id'];
        switch($_SERVER['REQUEST_METHOD']) {
            case 'GET':
                include('./files/comments.php');
                break;
            case 'POST':
                include("../includes/authcheck.php");
                include('./files/submitcomment.php');
                break;
//            case 'PUT':
//                include("../includes/authcheck.php");
//                include('./files/updatecomment.php');
//                break;
//            case 'DELETE':
//                include("../includes/authcheck.php");
//                include('./files/deletecomment.php');
//                break;
        }
        break;

    case 'rating':
        $request_rating_id=$get_data;
        $request_album_id=$_GET['album_id'];
        $request_track_id=$_GET['track_id'];
        $request_source_id=$_GET['rating_source'];
        switch($_SERVER['REQUEST_METHOD']) {
            case 'GET':
                include('./files/ratings.php');
                break;
            case 'POST':
                include("../includes/authcheck.php");
                include('./files/submitrating.php');
                break;
            case 'PUT':
                include("../includes/authcheck.php");
                include('./files/updaterating.php');
                break;
//            case 'DELETE':
//                include("../includes/authcheck.php");
//                include('./files/deleterating.php');
//                break;
        }
        break;

    case 'reset-password':
        $request_email=$get_data;
        include('./files/reset-password.php');
        break;

    case 'review':
        $request_review_id=$get_data;
        switch($_SERVER['REQUEST_METHOD']) {
            case 'GET':
                break;
            case 'POST':
                include("../includes/authcheck.php");
                include('./files/submitreview.php');
                break;
            case 'PUT':
                include("../includes/authcheck.php");
                include('./files/updatereview.php');
                break;
//            case 'DELETE':
//                include("../includes/authcheck.php");
//                include('./files/deletereview.php');
//                break;
        }
        break;

    case 'status':
        include('./files/status.php');
        break;

    case 'track':
        $request_track_id=$get_data;
        $request_year=$_GET['year'];
        $request_album_id=$_GET['album_id'];
        $request_artist_id=$_GET['artist_id'];
        $request_album_source=$_GET['album_source'];
        switch($_SERVER['REQUEST_METHOD']) {
            case 'GET':
                include('./files/track.php');
                break;
//            case 'POST':
//                include("../includes/authcheck.php");
//                include("../includes/admincheck.php");
//                break;
//            case 'PUT':
//                include("../includes/authcheck.php");
//                include("../includes/admincheck.php");
//                break;
//            case 'DELETE':
//                include("../includes/authcheck.php");
//                include("../includes/admincheck.php");
//                break;
        }
        break;

    case 'user':
        include("../includes/authcheck.php");
        $request_source_id=$get_data;
        switch($_SERVER['REQUEST_METHOD']) {
            case 'GET':
                include("../includes/authcheck.php");
                include('./files/users.php');
                break;
            case 'POST':
                include("../includes/authcheck.php");
                include("../includes/admincheck.php");
                include('./files/add-user.php');
                break;
            case 'PUT':
                include("../includes/authcheck.php");
                include("../includes/admincheck.php");
                include('./files/update-user.php');
                break;
            case 'DELETE':
                include("../includes/authcheck.php");
                include("../includes/admincheck.php");
                include('./files/delete-user.php');
                break;
        }
        break;

    case 'year':
        $request_year=$get_data;
        switch($_SERVER['REQUEST_METHOD']) {
            case 'GET':
                include('./files/years.php');
                break;
//            case 'POST':
//                include("../includes/authcheck.php");
//                include("../includes/admincheck.php");
//                break;
//            case 'PUT':
//                include("../includes/authcheck.php");
//                include("../includes/admincheck.php");
//                break;
//            case 'DELETE':
//                include("../includes/authcheck.php");
//                include("../includes/admincheck.php");
//                break;
        }
        break;
}
