<?php
/******* *///post
include "../../src/mysql.php";
$conn=connect();
/******* */

$sql1="SELECT suid,username,province FROM merchant;";
$result=mysqli_query($conn,$sql1);
if(!$result){
    echo json_encode(array("status"=>"fail"));
    exit;
}
$merList=array();
$merchantNum=0;
while(($aoc=mysqli_fetch_assoc($result))!=NULL){
    $merchantNum+=1;
    $avgSql="SELECT * from merchantAvgScore WHERE suid=".$aoc['suid'].";";
    $avgRes=mysqli_query($conn,$avgSql);
    if(!$avgRes){
        echo json_encode(array("status"=>"fail"));
        exit;
    }
    $score=mysqli_fetch_assoc($avgRes);
    $score=$score['avgScore']?$score['avgScore']:0;
    $aoc['avgScore']=$score;
    $merList[$merchantNum]=$aoc;
}
$merList['merNum']=$merchantNum;
echo json_encode($merList);