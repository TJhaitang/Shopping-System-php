<?php
/**************/
/**************/
include "../src/jwtTools.php";
include "../src/mysql.php";
$uid=getUidFromHttp("S");
$params=json_decode(file_get_contents("php://input"), true);
$conn=connect();
/**************/
/**************/
if($params['operation']=='delete'){//奶酪
    $selectSql="SELECT * FROM commodity WHERE commodityId='".$params['comId']."' AND suid=".$uid.";";
    $deleteSql="DELETE FROM commodity WHERE commodityId='".$params['comId']."' AND suid=".$uid.";";
    $deleteSql2="DELETE FROM standard where commodityId='".$params['comId']."';";
    $deleteSql3="DELETE FROM image where commodityId='".$params['comId']."';";
    // echo $deleteSql;
    // exit;
    $selectRes=mysqli_query($conn,$selectSql);
    if((!$selectRes)|!($aoc=mysqli_fetch_assoc($selectRes))){
        echo json_encode(array("status"=>"fail"));
        exit;
    }
    $result2=mysqli_query($conn,$deleteSql2);
    $result3=mysqli_query($conn,$deleteSql3);
    $result=mysqli_query($conn,$deleteSql);
    if($result&&$result2&&$result3){
        echo json_encode(array("status"=>"success"));
    }
    else{
        echo json_encode(array("status"=>$deleteSql2));
    }
}//安全
else if($params['operation']=='update'){//还需要update库存内容
    $updateSql="UPDATE commodity set name='".$params['name']."',description='".$params['description']."',slabel=".$params['label'].",minus=".($params['ifActivity1']+2*$params['ifActivity2']).
                " WHERE suid=".$uid." AND commodityId='".$params['comId']."';";
    $result=mysqli_query($conn,$updateSql);
    // echo $updateSql;
    if($result){
        $stdArray=$params['domains'];
        foreach ($stdArray as $key => $value){
            $updateStdSql="UPDATE standard set name='".$value['name']."', stock=".$value['sort_inventory'].",price=".$value['sort_price']." WHERE id=".$value['stdId']." AND commodityId='".$params['comId']."';";
            // echo $updateStdSql;
            if(!mysqli_query($conn,$updateStdSql)){
                echo json_encode(array("status"=>"fail"));
            }
        }
        echo json_encode(array("status"=>"success"));
        exit;
    }
    else{
        echo json_encode(array("status"=>"fail"));
    }
}