<?php
/******* *///post
include "../../src/mysql.php";
include "../../src/jwtTools.php";
$conn=connect();
$uid=getUidFromHttp("V");
$params=json_decode(file_get_contents("php://input"),true);
/******* */
$sql="INSERT into comment (user_id,item_id,content,score,addTime) VALUE (".$uid.
    ",".$params['item_id'].",'".$params['content']."',".$params['score'].",'".date('Y-m-d H:i:s')."');";
if(!mysqli_query($conn,$sql)){
    echo json_encode(array("status"=>"fail"));
    exit;
}
echo json_encode(array("status"=>"success"));