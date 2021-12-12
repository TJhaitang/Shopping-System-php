<?php
/******* *///post
include "../../src/mysql.php";
$conn=connect();
/******* */

$sql1="SELECT * FROM slabel;";
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
$carList['labelNum']=$carNum;
echo json_encode($carList);