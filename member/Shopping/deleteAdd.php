<?php
/******* *///post
include "../../src/mysql.php";
include "../../src/jwtTools.php";
$conn=connect();
$uid=getUidFromHttp("V");
$params=json_decode(file_get_contents("php://input"),true);
/******* */

$sql="DELETE FROM address WHERE add_id=".$params['add_id']." AND vuid=".$uid.";";
if(!mysqli_query($conn,$sql)){
    echo json_encode(array("status"=>"fail"));
}
else{
    echo json_encode(array("status"=>"success"));
}