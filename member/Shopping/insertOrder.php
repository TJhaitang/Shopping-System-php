<?php
/******* *///post
include "../../src/mysql.php";
include "../../src/jwtTools.php";
$conn=connect();
$uid=getUidFromHttp("V");
$params=json_decode(file_get_contents("php://input"),true);
/******* */

//一个商家一个订单
//并发性危，用陈诺的方法奶酪
//fail就rollback
//检查库存
//库存减一，销量加一
mysqli_begin_transaction($conn);
$items=$params['items'];
foreach($items as $key=>$value){
    $itemSql="SELECT * FROM standard WHERE id=".$value['itemId'].";";
    $res=mysqli_query($conn,$itemSql);
    if(!$res){
        echo json_encode(array("status"=>"fail"));
        mysqli_rollback($conn);
        exit;
    }
    $stock=mysqli_fetch_assoc($res);
    $stock=$stock['stock'];
    if($stock<$value['num']){
        echo json_encode(array("status"=>"fail"));
        mysqli_rollback($conn);
        exit;
    }
}
$orderCode="".$uid.time().$params['suid'].rand(0,100);
$sql="INSERT INTO orders (code,time,cost,status,payment,addNum,suid) VALUE ('".$orderCode."','".date('Y-m-d H:i:s')."',".$params['cost'].",1,".$params['payment'].",".$params['addNum'].",".$params['suid'].");";
if(!mysqli_query($conn,$sql)){
    echo json_encode(array("status"=>"fail"));
    mysqli_rollback($conn);
    exit;
}
foreach($items as $key=>$value){
    $itemSql="INSERT INTO item_order (quantity,orderNum,commodityId) VALUE (".$value['num'].",'".$orderCode."',".$value['itemId'].");";
    // {
    //     //删库存
    // }
    if(!mysqli_query($conn,$itemSql)){
        echo json_encode(array("status"=>"fail"));
        mysqli_rollback($conn);
        exit;
    }
}
mysqli_commit($conn);
echo json_encode(array("status"=>"success"));