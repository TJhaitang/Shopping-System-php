<?php
//更新用户信息奶酪
/**************/
/**************/
include "../src/jwtTools.php";
include "../src/mysql.php";
$info=getInfoForUpdate();
$uid=$info['uid'];
$type=$info['type'];
$query=json_decode(file_get_contents("php://input"), true);
$conn=connect();
/**************/
/**************/
//tsql

if ($type == 'S')
{
    $updateSql="UPDATE merchant set province = ".($query['province']?:"").",username = ".($query['username']?:"").
    ",phone = ".($query['phone']?:"").",gender = ".($query['gender']?:"").",signature = ".($query['signature']?:"").
    ",password = ".($query['password']?:"").",addr = ".($query['addr']?:"")." where suid = ".$uid.";";
}
else
{
    $updateSql="UPDATE member set province = ".($query['province']?:"").",username = ".($query['username']?:"").
    ",phone = ".($query['phone']?:"").",gender = ".($query['gender']?:"").",signature = ".($query['signature']?:"").
    ",password = ".($query['password']?:"")." where vuid = ".$uid.";";
}

$result=mysqli_query($conn,$updateSql);
if(!$result){
    echo json_encode(array("status"=>"fail"));
}
else{
    echo json_encode(array("status"=>"success"));
}