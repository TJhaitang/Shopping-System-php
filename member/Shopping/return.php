<?php
/******* *///get
include "../../src/mysql.php";
include "../../src/jwtTools.php";

$conn=connect();
$uid=getUidFromHttp("V");
$params=json_decode(file_get_contents("php://input"), true);
/******* */

/****进行商品的推荐 */

$sql="UPDATE orders SET status=5 WHERE status<5 AND addNum IN (SELECT add_id from address WHERE vuid=".$uid.") AND code='".$params['orderId']."';";
$result=mysqli_query($conn,$sql);
if(!$result){
    echo json_encode(array("status"=>"fail"));
    exit;
}
echo json_encode(array("status"=>"success"));

