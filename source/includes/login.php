<?php
	session_start();
	if(isset($_SESSION["user_id"]))
	{
		$user_id=$_SESSION["user_id"];
		$sourcename=$_SESSION["username"];
	}
	else
	{
		if(isset($loginrequired))
		{
			header("Location:login.php?prevurl=".$_SERVER['REQUEST_URI']);
		}
	}
?>