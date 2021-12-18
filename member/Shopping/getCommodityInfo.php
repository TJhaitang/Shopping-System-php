<?php
include "../../src/mysql.php";
include "../../src/jwtTools.php";
$conn=connect();
//不要这个以允许商家token访问
// $uid=getUidFromHttp("V");
$params=json_decode(file_get_contents("php://input"),true);
/**************/
/**************/
// echo json_encode($params);
// exit;
$sql="SELECT * from commodity where commodityId=".$params['comId'].";";//奶酪
$result=mysqli_query($conn,$sql);
if(!$result){
    echo json_encode(array("status"=>"fail"));
    exit;
}
if(($commodityInfo=mysqli_fetch_assoc($result))!=false){
    $domSql="SELECT * from standard where commodityId='".$commodityInfo['commodityId']."';";
    $domRes=mysqli_query($conn,$domSql);
    if(!$domRes){
        echo json_encode(array("status"=>"fail"));
        exit;
    }
    $stdNum=0;
    $dom=array();
    while(($daoc=mysqli_fetch_assoc($domRes))!=false){
        $stdNum+=1;
        $dom[$stdNum]=$daoc;
    }
    $dom['stdNum']=$stdNum;
    $commodityInfo['domains']=$dom;
    $picSql="SELECT * from image where commodityId='".$commodityInfo['commodityId']."';";
    $imgRes=mysqli_query($conn,$picSql);
    if(!$imgRes){
        echo json_encode(array("status"=>"fail"));
        exit;
    }
    $picNum=0;
    $pic=array();
    while(($paoc=mysqli_fetch_assoc($imgRes))!=false){
        $picNum+=1;
        $pic[$picNum]=$paoc;
    }
    $pic['picNum']=$picNum;
    $commodityInfo['pictures']=$pic;
    echo json_encode($commodityInfo);//是不是应该写上status
}
else{
    echo json_encode(array("status"=>"fail"));
}