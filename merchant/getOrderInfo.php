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

$sql="SELECT orderNum,state,cost,quantity";//奶酪!
$querySql=" WHERE ";
foreach($query as $key=>$value){
    if(strlen($value)<=1){//判断 空字符串？
        //按空字符串算
    }
    else{
        $querySql.=$key." IN ".$value." AND ";
    }
}
$sql.=$querySql."Suid=".$uid.";";//记得加token的id

$result=mysqli_query($conn,$sql);
if(!$result){
    echo json_encode(array("status"=>"fail"));
    exit;
}
$orderNum=array(1=>0,2=>0,3=>0,4=>0);
$orders=array();
$i=1;

while(($aoc=mysqli_fetch_assoc($result))!=false){//危
    $orders[$i]=json_encode($aoc);//危
    $i+=1;
    $state=$aoc['state'];
    $orderNum[$state]+=1;//危，复杂查询和字符串~int
}

echo json_encode(array("status"=>"success","orderNum"=>json_encode($orderNum),"orders"=>json_encode($orders)));