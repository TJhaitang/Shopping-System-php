<?php
/******* *///post
include "../../src/mysql.php";
include "../../src/jwtTools.php";
$conn=connect();
$uid=getUidFromHttp("V");
/******* */

$sql1="SELECT * FROM address WHERE vuid=".$uid.";";
$result=mysqli_query($conn,$sql1);
if(!$result){
    echo json_encode(array("status"=>"fail"));
    exit;
}
$carList=array();
$carNum=0;
while(($aoc=mysqli_fetch_assoc($result))!=NULL){
    $carNum+=1;
    $carList[$carNum]=$aoc;
}
$carList['AddressNum']=$carNum;
echo json_encode($carList);

