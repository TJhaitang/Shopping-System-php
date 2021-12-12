<?php
/**************/
/**************/
include "../src/jwtTools.php";
include "../src/mysql.php";
$uid=getUidFromHttp("S");
$params=json_decode(file_get_contents("php://input"), true);
$conn=connect();
/**************/
/**************/
$sql="";

