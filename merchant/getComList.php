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
$sql="SELECT * from commodity where suid=".$uid.";";//奶酪
$result=mysqli_query($conn,$sql);
if(!$result){
    echo json_encode(array("status"=>"fail"));
    exit;
}
$comNum=0;
$comList=array();
while(($aoc=mysqli_fetch_assoc($result))!=false){
    $comNum+=1;
    $domSql="SELECT * from standard where commodityId='".$aoc['commodityId']."';";
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
    $aoc['domains']=$dom;
    $comList[$comNum]=$aoc;
}
$comList['comNum']=$comNum;
echo json_encode(Array("status"=>"success","data"=>$comList));//是不是应该写上status