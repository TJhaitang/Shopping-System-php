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
//事务与锁
$selectSql="SELECT status FROM orders WHERE status<4 AND suid=".$uid." AND code='".$params['id']."';";
$result=mysqli_query($conn,$selectSql);
if(!$result){
    echo json_encode(array("status"=>"fail"));
    exit;
}
$aoc=mysqli_fetch_assoc($result);
if(!$aoc['status']){
    echo json_encode(array("status"=>"fail"));
    exit;
}
$sql="UPDATE orders SET status=".($aoc['status']+1)." WHERE status<4 AND suid=".$uid." AND code='".$params['id']."';";//不要乱加了亲爱的
if(!mysqli_query($conn,$sql)){
    echo json_encode(array("status"=>"fail"));
    exit;
}
echo json_encode(array("status"=>"success"));

