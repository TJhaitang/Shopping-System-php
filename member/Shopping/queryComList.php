<?php
/******* *///post
include "../../src/mysql.php";
include "../../src/jwtTools.php";
$conn=connect();
// $uid=getUidFromHttp("V");
$params=json_decode(file_get_contents("php://input"),true);
/******* */

/*复杂查询需要什么？
*标签
*价格排序
*销量排序
*平均分排序
*名字
*价格区间
*商家id
*/

//价格匹配
$sql="SELECT * from shopPlus WHERE price<".$params['uprice']." AND price>".$params['lprice'];
//名字匹配
if(($params['name'])&&strlen($params['name'])>0){
    $sql.=" AND name LIKE '%".$params['name']."%'";
}
//标签匹配
if(($params['label'])&&strlen($params['label'])>0){
    $sql.=" AND label IN ".$params['label'];
}
//商家id匹配
if(($params['suid'])&&strlen($params['suid'])>0){
    $sql.=" AND suid=".$params['suid'];
}
//排序
if($params['sortfor']==1){//销量
    $sql.=" ORDER BY sales";
}
if($params['sortfor']==2){//商品平均分
    $sql.=" ORDER BY avgScore";
}
if($params['sortfor']==3){//价格
    $sql.=" ORDER BY price";
}
if($params['sortfor']!=0){
    if($params['isDecent']==1){
        $sql.=" DESC;";
    }
    else{
        $sql.=" ASC;";
    }
}

$result=mysqli_query($conn,$sql);
if(!$result){
    echo json_encode(array("status"=>"fail"));
    exit;
}
$comList=array();
$comNum=0;
$i=0;
while(($aoc=mysqli_fetch_assoc($result))!=NULL){
    $comNum+=1;
    if(!$aoc['avgScore']){
        $aoc['avgScore']=0;
    }
    $comList[$comNum]=$aoc;
}
$comList['comNum']=$comNum;
echo json_encode($comList);

