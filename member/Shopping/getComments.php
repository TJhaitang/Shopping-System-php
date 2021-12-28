<?php
//获取一个商品在商品页展示的评论奶酪
/******* *///post
include "../../src/mysql.php";
include "../../src/jwtTools.php";
$conn=connect();
$uid=getUidFromHttp("V");
$params=json_decode(file_get_contents("php://input"),true);
/******* */

$sql="SELECT * from comment WHERE item_id IN (SELECT id FROM standard WHERE commodityId='".$params['commodityId']."');";
$result=mysqli_query($conn,$sql);
if(!$result){
    echo json_encode(array("status"=>"fail"));
    exit;
}
else{
    $commentNum=0;
    $commentList=array();
    $score=0;
    while(($aoc=mysqli_fetch_assoc($result))!=NULL){
        $commentNum+=1;
        $comList[$commentNum]=$aoc;
        $score+=$aoc['score'];
    }
    $comList['commentNum']=$commentNum;
    if($commentNum!=0){
        $comList['avgScore']=$score/$commentNum;
    }
    echo json_encode($comList);
}