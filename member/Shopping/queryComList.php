<?php
/******* *///post
include "../../src/mysql.php";
include "../../src/jwtTools.php";
$conn=connect();
$uid=getUidFromHttp("V");
$params=json_decode(file_get_contents("php://input"),true);
/******* */

/*复杂查询需要什么？
*标签
*价格排序升序/降序
*销量排序升序/降序
*