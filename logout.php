<?php
	session_start();
	$_SESSION["user_id"]=NULL;
	$_SESSION["username"]=NULL;
	header("Location:./");
?>