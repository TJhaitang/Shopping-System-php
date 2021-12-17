<?php
/******* *///post
include "../../src/mysql.php";
include "../../src/jwtTools.php";
$conn=connect();
$uid=getUidFromHttp("V");
/******* */

$sql1="SELECT * FROM car_view WHERE useId=".$uid.";";
$result=mysqli_query($conn,$sql1);
if(!$result){
    echo json_encode(array("status"=>"fail"));
    exit;
}
$carList=array();
$carNum=0;
while(($aoc=mysqli_fetch_assoc($result))!=NULL){
    $carNum+=1;
    $imgSql="SELECT photo FROM image where commodityId='".$aoc['commodityId']."';";
    $imgRes=mysqli_query($conn,$imgSql);
    if(!$imgRes){
        echo json_encode(array("status"=>"fail"));
    }
    $imgPath=mysqli_fetch_assoc($imgRes);
    if((!$imgPath['photo'])|strlen($imgPath['photo'])==0){
        $imgPath['photo']="http://60.205.226.34/php/repo/default.png";
    }
    $aoc['image']=$imgPath['photo'];
    $aoc['num']=number_format($aoc['num']);
    $aoc['price']=number_format($aoc['price'],2);
    $carList[$carNum]=$aoc;
    echo json_encode($aoc);
}
$carList['carNum']=$carNum;
echo json_encode($carList);

