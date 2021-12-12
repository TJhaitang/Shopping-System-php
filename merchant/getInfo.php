<?php
include "../src/jwtTools.php";
include "../src/mysql.php";
$uid=getUidFromHttp("S");
// $uid="1";
$conn=connect();
//sql语句需要修改奶酪
$sql="SELECT suid uid,username,name,email,phone,addr,gender,province,signature,avatar,identity FROM merchant WHERE suid=".$uid.";";
$result=mysqli_query($conn,$sql);
if(!$result){
    echo json_encode(array("status"=>"fail"));
    exit;
}
$info=mysqli_fetch_assoc($result);
echo json_encode($info);