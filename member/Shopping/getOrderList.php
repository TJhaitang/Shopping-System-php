<?php
/******* *///post
include "../../src/mysql.php";
include "../../src/jwtTools.php";
$conn=connect();
$uid=getUidFromHttp("V");
/******* */

$sql1="SELECT * FROM orders WHERE addNum IN (SELECT add_id FROM address WHERE vuid=".$uid.");";
$result=mysqli_query($conn,$sql1);
if(!$result){
    echo json_encode(array("status"=>"fail"));
    exit;
}
$orderList=array();
$orderNum=0;
while(($aoc=mysqli_fetch_assoc($result))!=NULL){
    $orderNum+=1;
    $itemSql="SELECT * FROM item_order WHERE orderNum='".$aoc['code']."';";
    $result1=mysqli_query($conn,$itemSql);
    if(!$result1){
        echo json_encode(array("status"=>"fail"));
        exit;
    }
    $items=array();
    $itemNum=0;
    while(($itemAoc=mysqli_fetch_assoc($result1))!=NULL){
        $itemNum+=1;
        $itemId=$itemAoc['commodityId'];//这里的commodityid是itemid！！！！！！！
        $comSql="SELECT * from standard WHERE id=".$itemId.";";
        $comRes=mysqli_query($conn,$comSql);
        if(!$comRes){
            echo json_encode(array("status"=>"fail"));
            exit;
        }
        $comAoc=mysqli_fetch_assoc($comRes);
        $comId=$comAoc['commodityId'];
        $picSql="SELECT photo from shop where commodityId='".$comId."';";
        $picRes=mysqli_query($conn,$picSql);
        if(!$picRes){
            echo json_encode(array("status"=>"fail"));
            exit;
        }
        $picAoc=mysqli_fetch_assoc($picRes);
        $picture=$picAoc['photo'];
        if(!$picture){
            $picture="http://60.205.226.43/php/repo/default.png";
        }
        $itemAoc['picture']=$picture;
        $items[$itemNum]=$itemAoc;
    }
    $items['itemNum']=$itemNum;
    $aoc['items']=$items;
    $aoc['num']=number_format($aoc['num']);
    $aoc['price']=number_format($aoc['price'],2);
    $orderList[$orderNum]=$aoc;
}
$orderList['orderNum']=$orderNum;
echo json_encode($orderList);

