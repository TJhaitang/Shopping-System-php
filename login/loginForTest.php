<?php
include "../src/mysql.php";
include "../src/jwtTools.php";
$conn = connect();
$params = json_decode(file_get_contents("php://input"), true);
// $user="1319377413@qq.com";
$pswd = $params['password'];
// $pswd="123456";
$pswd = md5($pswd, false);
$type=$params['type'];
//查询数据库
$sql="";
$user="";
if($type=="S"){
    $nowtime=time()-300;
    $user = $params['email'];
    $sql="SELECT ".(($type=="S")?"suid":"vuid")." from ".(($type=="S")?"merchant":"member")." where email='".$params['email']."' AND password = '".$pswd."';";
}else if($type=="V"){
    $user=$params['username'];
    $sql="SELECT * from member WHERE username='".$params['username']."' AND password='".$pswd."';";
}
// echo $sql;
$result=mysqli_query($conn,$sql);
if(!$result){
    echo json_encode(array("status"=>"fail"));
    exit;
}
$dt=mysqli_fetch_assoc($result);

if($dt!=false/*判断是否有结果*/){
    $DeleteSql="DELETE FROM code WHERE email='".$params['email']."';";
    // $Dresult=mysqli_query($conn,$DeleteSql);
    // echo $user."   ".$type;
    $token=getToken($type,$user,(($type=="S")?$dt['suid']:$dt['vuid']));
    echo json_encode(array("status"=>"success","token"=>$token));
}
else{
    echo json_encode(array("status" => "fail"));
}
