<?php
//给出20个商品推荐//在商品表做一个视图
/******* *///get
include "../../src/mysql.php";
include "../../src/jwtTools.php";

$conn=connect();
$uid=getUidFromHttp("V");
/******* */

/****进行商品的推荐 */

$sql="SELECT * from shop";
$result=mysqli_query($conn,$sql);
if(!$result){
    echo json_encode(array("status"=>"fail"));
}
$comList=array();
$comNum=0;
$i=0;
while($comNum<6&&($aoc=mysqli_fetch_assoc($result))!=NULL){
    $comNum+=1;
    $comList[$comNum]=$aoc;
}
echo json_encode($comList);
