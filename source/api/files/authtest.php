<?php
include("../includes/jwt_helper.php");
include("../includes/authcheck.php");
echo(json_encode($token_data));
