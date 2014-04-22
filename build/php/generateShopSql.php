<?php
require '../tank/protected/includes/functions.inc.php';
//货币格式验证
function checkCoins($val,&$errors){
    if($val==''){
        $errors[] = 'coins can`t be empty.';
    }
    $a = SSerialize::newDecode($val,2);
    if(!$a){
        $errors[] = 'coins illegal';
    }
    return $val;
}
//价格调整列表验证
function checkPriceFixs($val,&$errors){
    if($val){
        $a = SSerialize::newDecode($val,4);
        if(!$a || in_array($a[0][0][0],array('byBuyCount'))){
            $errors[] = 'priceFixs format error.';
        }
    }
    return $val;
}
//购买限制列表验证
function checkRequires($val,&$errors){
    if($val){
        $a = SSerialize::newDecode($val,3);
        if(!$a){
            $errors[] = 'require format is error.';
        }
        foreach($a as $row){
            if(!in_array($row[0],array('limitTimesOfSomeTime','limitTimesForever'))){
                $errors[] = 'require params type is error.';
            }
        }
    }
    return $val;
}
//noEmpty
function checkNoEmpty($val,&$errors){
    if($val==''){
        $code = 2;
        $errors[] = 'value can`t be enpty.';
    }
    return $val;
}

//通过data/xls/monster.xls生成data/sql/season2_seven2_init_monster.sql文件
require 'filters/KExcelReader.php';

$reader = KExcelReader::load("../tank/protected/data/xls/shop.xls");

$list = array();
list($heads, $data)=$reader->getTable('cargo');
$map = array_flip($heads);
$insert = 'insert into cargo(cargoId,type,serviceParams,name,description,channel,coins) values';
$ss = 'truncate cargo;'.$insert;
$l = array();
$i = 1;
foreach($data as $k=>$v){
    $errors = array();
    $cargoId = checkNoEmpty($v[$map['商品id']],$errors);
    $type = checkNoEmpty($v[$map['商品类型']],$errors);
    $serviceParams = $v[$map['服务参数']];
    $name = checkNoEmpty($v[$map['商品名']],$errors);
    $description = checkNoEmpty($v[$map['商品描述']],$errors);
    $channel = checkNoEmpty($v[$map['销售渠道']],$errors);
    $coins = checkCoins($v[$map['货币及价格']],$errors);
    $priceFixs = checkPriceFixs($v[$map['价格调整']],$errors);
    $requires = checkRequires($v[$map['购买限制']],$errors);
    $l[] = "($cargoId,'$type','$serviceParams','$name','$description','$channel','$coins')";
    if((++$i)%1000==0){
        $ss .= implode(',',$l).";\r\n";
        $ss = $insert;
        $l = array();
    }
    if(count($errors)){
        echo "\ncargo line ".($k+1)." has errors:";
        print_r($errors);
    }
}
$ss .= implode(',',$l).';';

$file = $ss;

$list = array();
list($heads, $data)=$reader->getTable('cargoChange');
$map = array_flip($heads);
$insert = 'insert into cargoChange(cargoId,openFlag,type,val,startTime,endTime) values';
$ss = 'truncate cargoChange;'.$insert;
$l = array();
$i = 1;
foreach($data as $k => $v){
    $errors = array();
    $cargoId = checkNoEmpty($v[$map['商品id']],$errors);
    $openFlag = checkNoEmpty($v[$map['开启']],$errors);
    $type = checkNoEmpty($v[$map['动态类型']],$errors);
    if($type=='sellTime'){
        $val = '';
    }elseif($type=='discount'){
        $val = checkNoEmpty($v[$map['值']],$errors);
    }elseif($type=='coinRequire'){
        $val = checkCoins($v[$map['值']],$errors);
    }else{
        $errors[] = 'cargoChange type is illegal.';
    }
    $startTime = strtotime(checkNoEmpty($v[$map['开始时间']],$errors));
    $endTime = strtotime(checkNoEmpty($v[$map['结束时间']],$errors));
    $l[] = "($cargoId,$openFlag,'$type','$val',$startTime,$endTime)";
    if((++$i)%1000==0){
        $ss .= implode(',',$l).";\r\n";
        $ss = $insert;
        $l = array();
    }
    if(count($errors)){
        echo "\ncargoChange line ".($k+1)." has errors:";
        print_r($errors);
    }
}
$ss .= implode(',',$l).';';

$file .= "\r\n".$ss;

$list = array();
list($heads, $data)=$reader->getTable('cargoReward');
$map = array_flip($heads);
$insert = 'insert into cargoReward(cargoId,internalId,rewardType,rewardId) values';
$ss = 'truncate cargoReward;'.$insert;
$l = array();
$i = 1;
foreach($data as $k => $v){
    $errors = array();
    $cargoId = checkNoEmpty($v[$map['商品id']],$errors);
    $internalId = checkNoEmpty($v[$map['排序id']],$errors);
    $rewardType = checkNoEmpty($v[$map['掉落类型']],$errors);
    $rewardId = checkNoEmpty($v[$map['掉落id或数量']],$errors);
    $l[] = "($cargoId,$internalId,'$rewardType',$rewardId)";
    if((++$i)%1000==0){
        $ss .= implode(',',$l).";\r\n";
        $ss = $insert;
        $l = array();
    }
    if(count($errors)){
        echo "\cargoReward line ".($k+1)." has errors:";
        print_r($errors);
    }
}
$ss .= implode(',',$l).';';

$file .= "\r\n".$ss;
file_put_contents('../tank/protected/data/sql/season2_tank_init_shop'.date('Ymdhis').'.sql', $file);

echo 'success!';
