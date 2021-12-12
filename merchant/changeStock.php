<?php
/******** */
include "../src/mysql.php";
include "../src/jwtTools.php";
//这个方法现在没用了
$conn=connect();
$params=json_decode(file_get_contents("php://input"),true);
$uid=getUidFromHttp("S");
/******** */
//安全
$sql="UPDATE standard SET stock=".$params['stock']." WHERE id=".$params['id'].";";
if(!mysqli_query($conn,$sql)){
    echo json_encode(array("status"=>"fail"));
}
else{
    echo json_encode(array("status"=>"success"));
}