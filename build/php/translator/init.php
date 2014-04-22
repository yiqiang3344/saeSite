<?php
chdir("../../../1");
define("DIC","protected/data/dictionary.txt");
$input=array(
);

$ignore=array(
);

$file_list=array();
foreach($input as $entry){
    list($src,$dst)=$entry;
    if(is_dir($src)){
        foreach(scandir($src) as $f){
            if($f=="." || $f==".." || preg_match("{\\.dic$}",$f) || $f==".DS_Store"){
                continue;
            }
            $f=$src."/".$f;
            if(in_array($f, $ignore)){
                continue;
            }
            if(is_file($f)){
                $file_list[]=array($f,$dst);
            }
        }
    }else if(is_file($src)){
        $f=$src;
        $file_list[]=array($f,$dst);
    }
}

require_once "SmartDictionary2.php";
