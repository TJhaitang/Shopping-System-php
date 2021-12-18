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
$sql="UPDATE orders SET status=status+1 WHERE status<5 AND suid=".$uid." AND code='".$params['id']."';";//不要乱加了亲爱的
if(!mysqli_query($conn,$sql)){
    echo json_encode(array("status"=>"fail"));
    exit;
}
echo json_encode(array("status"=>"success"));

