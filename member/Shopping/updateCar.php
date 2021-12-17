<?php
/**************/
/**************/
include "../../src/mysql.php";
include "../../src/jwtTools.php";
$uid=getUidFromHttp("V");
$params=json_decode(file_get_contents("php://input"), true);
$conn=connect();
/**************/
/**************/
if($params['operation']=='delete'){
    $deleteSql="DELETE FROM car WHERE id='".$params['carId']."' AND user_id=".$uid.";";
    // echo $deleteSql;
    // exit;
    $result=mysqli_query($conn,$deleteSql);
    if($result){
        echo json_encode(array("status"=>"success"));
    }
    else{
        echo json_encode(array("status"=>"fail"));
    }
}//安全
else if($params['operation']=='update'){//还需要update库存内容
    $updateSql="UPDATE car set num=".$params['num']." WHERE id='".$params['carId']."' AND user_id=".$uid.";";
    $result=mysqli_query($conn,$updateSql);
    // echo $updateSql;
    if($result){
        echo json_encode(array("status"=>"success"));
    }
    else{
        echo json_encode(array("status"=>"fail"));
    }
}