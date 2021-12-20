<?php
include "../src/jwtTools.php";
include "../src/mysql.php";

$uid=getUidFromHttp("S");
//奶酪,图片文件
$params=json_decode(file_get_contents("php://input"), true);
$conn=connect();

//没有进行测试奶酪
//无法进行规格划分--多加一个规格表
$commodityId="".$uid.time().md5($params['name']);
$insertComSql="INSERT INTO commodity (commodityId,description,name,slabel,suid,minus) VALUES ('".
            $commodityId."','".$params['description']."','".
            $params['name']."',".$params['label'].",".$uid.",".($params['ifActivity1']+2*$params['ifActivity2']).");";
// echo $insertComSql;
if(!mysqli_query($conn,$insertComSql)){
    echo json_encode(array("status"=>$insertComSql));
    exit;
}
//向规格表里面插入规格
$stdArray=$params['domains'];//奶酪，检查一下键对不对
// echo $stdArray[0]['name'];
foreach($stdArray as $key=>$value){
    $dm=$value;
    $stdInsertSql="INSERT INTO standard (price,name,standards,stock,commodityId) VALUES (".$dm['sort_price'].",'".$dm['name']."','".$dm['name']."',".$dm['sort_inventory'].",'".$commodityId."');";
    // echo $stdInsertSql."\n";
    if(!mysqli_query($conn,$stdInsertSql)){
        echo json_encode(array("status"=>"fail2"));
        exit;
    }
}
//向图片表里插入图片
$picArray=$params['domains_pic'];//奶酪，需要进一步的调试
$imgCount=0;
foreach($picArray as $key=>$value){
    $imgCount+=1;
    // $photo=savePic($commodityId,$value);
    // $photo=1;
    $sql="INSERT INTO image (photo,commodityId) VALUES ('".$value['photo']."','".$commodityId."');";
    // echo $value;
    if(!mysqli_query($conn,$sql)){
        echo json_encode(array("status"=>$sql));
        exit;
    }
}
if($imgCount==0){
    $photo="http://60.205.226.43/php/repo/default.png";
    $sql="INSERT INTO image (photo,commodityId) VALUES ('".$photo."','".$commodityId."');";
    //echo $value;    
    if(!mysqli_query($conn,$sql)){
        echo json_encode(array("status"=>"fail4"));
        exit;
   }
}

echo json_encode(array("status"=>"success"));

function savePic($commodityId,$imgBase64){
    //图片转存、return图片地址
    //奶酪
    $content="";
    $type="";
    if(preg_match('/^(data:\s*image\/(\w+);base64,)/',$$imgBase64,$result)){
        $type=$result[2];
        $content=$result[1];
    }
    else{
        echo json_encode(array("status"=>"fail4"));
        exit;
    }
    $repoPath="/var/www/html/php/repo/";
    if(!file_exists($repoPath)){
        mkdir($repoPath,0700);
    }
    $picName=md5($imgBase64).time().md5("".$commodityId).".{$type}";
    $savePath=$repoPath.$picName;
    if(!file_put_contents($savePath,base64_decode(str_replace($content,'',$imgBase64)))){
        echo json_encode(array("status"=>"fail5"));
        exit;
    }
    //奶酪
    $picPath="http://60.205.226.43/php/repo/".$picName;
    return $picPath;
}