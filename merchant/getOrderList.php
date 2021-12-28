<?php
//获取订单种类和订单数量奶酪
/**************/
/**************/
include "../src/mysql.php";
include "../src/jwtTools.php";
$uid=getUidFromHttp("S");
$params=json_decode(file_get_contents("php://input"), true);
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
// $sql="SELECT * FROM orders;";

$result=mysqli_query($conn,$sql);
if(!$result){
    echo json_encode(array("status"=>"fail"));
    exit;
}
$orderNum=0;
$orders=array();

//获取orders的详细信息
while(($aoc=mysqli_fetch_assoc($result))!=false){//危
    $orderNum+=1;
    $addNum=$aoc['addNum'];
    $addSql="SELECT * from address WHERE add_id=".$addNum.";";
    $addRes=mysqli_query($conn,$addSql);
    if(!$addRes){
        echo json_encode(array("status"=>"fail"));
        exit;
    }
    $addr=mysqli_fetch_assoc($addRes);
    $aoc['addr']=$addr;
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
        $nameSql="SELECT name FROM commodity WHERE commodityId='".$comId."';";
        $nameRes=mysqli_query($conn,$nameSql);
        if(!$nameRes){
            echo json_encode(array("status"=>"fail"));
            exit;
        }
        $nameAoc=mysqli_fetch_assoc($nameRes);
        $name=$nameAoc['name']."---".$comAoc['name'];
        $itemAoc['comName']=$name;
        $items[$itemNum]=$itemAoc;
    }
    $aoc['itemNum']=$itemNum;
    $aoc['items']=$items;
    // $aoc['num']=number_format($aoc['num']);//当初为什么要写这个？？？没睡醒吗？
    // $aoc['price']=number_format($aoc['price'],2);
    $orders[$orderNum]=$aoc;//危
}
$orders['orderNum']=$orderNum;
echo json_encode($orders);