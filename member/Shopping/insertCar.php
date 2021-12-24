<?php
/******* *///post
include "../../src/mysql.php";
include "../../src/jwtTools.php";
$conn=connect();
$uid=getUidFromHttp("V");
$params=json_decode(file_get_contents("php://input"),true);
/******* */

$sql="INSERT INTO car (num,user_id,item_id) VALUE (".$params['num'].",".$uid.",".$params['item_id'].");";
if(!mysqli_query($conn,$sql)){
    echo json_encode(array("status"=>"fail"));
}
else{
    echo json_encode(array("status"=>"success"));
}