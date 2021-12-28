<?php
/******* *///post
include "../../src/mysql.php";
$conn=connect();
$params=json_decode(file_get_contents("php://input"),true);
/******* */

$sql1="SELECT suid,username,province FROM merchant WHERE suid=".$params['suid'].";";
$result=mysqli_query($conn,$sql1);
if(!$result){
    echo json_encode(array("status"=>"fail"));
    exit;
}
$aoc=mysqli_fetch_assoc($result);
$avgSql="SELECT * from merchantAvgScore WHERE suid=".$aoc['suid'].";";
$avgRes=mysqli_query($conn,$avgSql);
if(!$avgRes){
    echo json_encode(array("status"=>"fail"));
    exit;
}
$score=mysqli_fetch_assoc($avgRes);
$score=$score['avgScore']?$score['avgScore']:0;
$aoc['avgScore']=$score;
echo json_encode($aoc);