<?php
require_once "init.php";
$dic=new Dic(DIC);
$plan = new DicPlan();
$plan->setPrimaryDic(array($dic));
$files=array();
$succ=true;
foreach($file_list as $file){
    if(file_exists($file[0].".dic")){
        $ret=$plan->scan_file($file[0],array(new Dic($file[0].".dic")));
    }else{
        $ret=$plan->scan_file($file[0],array());
    }   
    if($ret["code"]!=1){
        $succ=false;
    }
    // $content=@file_get_contents($file[0]);
    // $ret=array("code"=>1,"zh_cn"=>$content,"ja"=>$content,"zh_tw"=>$content,"en"=>$content);
    $files[]=array($file[0],$file[1],$ret);
}
if(!$succ){
    echo "some file fail";
    exit;
}
foreach($files as $file){
    foreach($lang_list as $l){
        $dd = array();
        foreach(xexplode('/',dirname($file[0])."/".str_replace("{lang}",$l,$file[1])) as $d){
            $dd[] = $d;
            if(!in_array(trim($d),array('.','..',''))){
                is_dir(($dir=implode('/',$dd))) || mkdir($dir);
            }
        }
        if(preg_match('{\.js$}', $basename = basename($file[0]))){
            $new_filename=dirname($file[0])."/".str_replace("{lang}",$l,$file[1])."/".basename($file[0],'.js').'.min.js';
            $content = JSMin::minify($file[2][$l]);
        }else{
            $new_filename=dirname($file[0])."/".str_replace("{lang}",$l,$file[1])."/".$basename;
            $content = $file[2][$l];
        }
        file_put_contents($new_filename,$content);
    }
}
echo "translate success\n";


function xexplode($delimiter, $string){
    if(!$string){
        return array();
    }
    return explode($delimiter, $string);
}