<?php
include "../src/mysql.php";
$params=json_decode(file_get_contents("php://input"), true);
$conn=connect();
$type=$params['type'];

$selectSql="SELECT * from ".($type=="S"?"merchant":"member")." WHERE email='".$params['email']."';";

$result1=mysqli_query($conn,$selectSql);
$dt=mysqli_fetch_assoc($result);

if($dt){
    echo json_encode(array("status"=>"EmailAlreadyExist"));
}
else{
$insertSql="INSERT INTO ".($type=="S"?"merchant":"member").
    " (password,name,email,province,identity,username,phone,addr".
    ($params['gender']?",gender":"").
    ($params['signature']?",signature":"").
    ") VALUES ('".
    md5($params['password'])."'".
    ",'".$params['name']."'".
    ",'".$params['email']."'".
    ",'".$params['province']."'".
    ",'".$params['identity']."'".
    ",'".($params['username']?$params['username']:($type=="S"?"大灰狼":"小白兔"))."'".
    ($params['phone']?(",'".$params['phone']."'"):",'0000'").
    ($params['addr']?(",'".$params['addr']."'"):",'none'").
    ($params['gender']?(",'".$params['gender']."'"):"").
    ($params['signature']?(",'".$params['signature']."'"):"").");";

$result2=mysqli_query($conn,$insertSql);
if($result2)
echo json_encode(array("status"=>"success"));
else
echo json_encode(array("status"=>$insertSql));
}