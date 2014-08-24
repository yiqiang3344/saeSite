<?php
require('helper.php');
setCacheCode();
echo "patch md5, generate url.js success";
//处理一个语言目录
function setCacheCode(){
    $arr=array();   
    //处理指定目录
    $dirs = array(
        'img',
        'images',
        'upload',
        'upload1',
    );
    foreach($dirs as $d){
        $adir = $d;
        $file_list=array();
        scan_dir($adir,$file_list);
        foreach($file_list as $file){
            if(preg_match("{thumb\.db}isu",$file)||preg_match("{thumbs\.db}isu",$file)||preg_match("{\\.DS_Store$}isu",$file)){
                continue;
            }
            pushToAr($arr,$d.'/'.substr($file,strlen($adir)+1),substr(md5_file($file),0,8));
        }
    }
    
    //保存到url.js
    file_put_contents("js/url.js","var URLCACHE=".json_encode($arr).";\r\n");
    
    //处理CSS文件给图片加MD5后缀
    $rep=new ReplaceCss('./css');
    $cssFiles  = array(
        'page.css',
    );
    foreach($cssFiles as $css){
        $file='css/'.$css;
        $content=@file_get_contents($file);
        if(!$content){
            die("$file not found");
        }
        file_put_contents($file,preg_replace_callback("{url\\(\\\"?\\'?([^\\?\\)\\\"\\']*)\\??.*?\\)}u",array($rep,"callback"),$content));  
    }
}

function pushToAr(&$arr,$file,$md5){
    if(in_array($file,array())){
        //这些文件是不会在js中调用的，所以去掉
        return;
    }else if(preg_match("{\\.dic$}su",$file)){//dic文件不做处理
        return;
    }else if(preg_match("{thumb\\.db$}isu",$file)||preg_match("{thumbs\\.db$}isu",$file)||preg_match("{\\.DS_Store$}isu",$file)){
        return;
    }
    $pieces=explode("/",$file);
    for($i=0;$i<count($pieces);$i++){
        if($i==(count($pieces)-1)){//最后一个
            $arr[$pieces[$i]]=$md5;
            break;
        }else if(!isset($arr[$pieces[$i]])){
            $arr[$pieces[$i]]=array();
        }
        $arr= &$arr[$pieces[$i]];
    }   
}

class ReplaceCss{
    private $dir;
    public function __construct($dir){
        $this->dir=$dir;
    }
    public function callback($m){
        $file=$m[1];
        return "url(".$m[1]."?v=".substr(md5_file($this->dir.'/'.$file),0,8).")";
    }   
}