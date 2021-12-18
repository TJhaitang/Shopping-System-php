<?php
//获取订单种类和订单数量奶酪
/**************/
/**************/
include "../src/jwtTools.php";
include "../src/mysql.php";
$uid=getUidFromHttp("S");
$query=json_decode(file_get_contents("php://input"), true);
$conn=connect();
/**************/
/**************/

$sql="SELECT * from orders WHERE ";//奶酪!

if(($params['status'])&&strlen($params['status'])>0){
    $sql.="status=".$params['status'];
}
else{
    $sql.="status IN (1,2,3,4)";
}

if(($params['id'])&&strlen($params['id'])>0){
    $sql.=" AND code='".$params['id']."'";
}

if(($params['uid'])&&strlen($params['uid'])>0){
    $sql.=" AND addNum IN (SELECT add_id FROM address WHERE vuid=".$params['uid'].")";
}
$sql.=";";

$result=mysqli_query($conn,$sql);
if(!$result){
    echo json_encode(array("status"=>"fail"));
    exit;
}
$orderNum=0;
$orders=array();

while(($aoc=mysqli_fetch_assoc($result))!=false){//危
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
        $comId=$itemAoc['commodityId'];
        $picSql="SELECT photo from shop where commodityId='".$comId."';";
        $picRes=mysqli_query($conn,$picSql);
        if(!$picRes){
            echo json_encode(array("status"=>"fail"));
            exit;
        }
        $itemAoc['picture']=$picRes['photo'];
        $items[$itemNum]=$itemAoc;
    }
    $items['itemNum']=$itemNum;
    $aoc['items']=$items;
    $orders[$orderNum]=$aoc;//危
}
$orders['orderNum']=$orderNum;
echo json_encode($orders);