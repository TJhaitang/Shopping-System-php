<?php
include "../src/mysql.php";
$params=json_decode(file_get_contents("php://input"), true);
$conn=connect();

$selectSql="SELECT * from member WHERE username='".$params['username']."';";

$result1=mysqli_query($conn,$selectSql);
$dt=mysqli_fetch_assoc($result1);
// echo $selectSql;
if($dt){
    echo json_encode(array("status"=>"UserAlreadyExist"));
    exit;
}
else{
$insertSql="INSERT INTO member (username,password,phone,email,province) VALUE ('".$params['username']."','".md5($params['password'])."','".$params['phone']."','".$params['email']."','".$params['province']."');";

$result2=mysqli_query($conn,$insertSql);
if($result2)
echo json_encode(array("status"=>"success"));
else
echo json_encode(array("status"=>"fail"));
}