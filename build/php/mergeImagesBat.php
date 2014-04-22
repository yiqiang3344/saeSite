<?php
require('../tank/protected/includes/MergePng.php');
// fwrite(STDOUT, 'please input the path of text which save images path, format is [{"path":"images_dirs_path","name":"whats_name_you_want"},..]:');
// $path = trim(fgets(STDIN));
$path = '/Users/sidney/Downloads/573X373/path.txt';
$list = json_decode(file_get_contents($path),true);
if($list){
    is_file(dirname($path).'/anim.js') && unlink(dirname($path).'/anim.js');
    is_file(dirname($path).'/anim.css') && unlink(dirname($path).'/anim.css');
    foreach ($list as $k => $row) {
        $merge = new MergePngs($row['path'],$row['name'],array('sSavePath'=>dirname($path),'nMaxHeight'=>2048,'nMaxWidth'=>2048));
        $merge->go();
        $merge->outJsData();
        $merge->outCssData();
        // $merge->outFirstImage('t'.sprintf('%03d',substr($row['name'],strrpos($row['name'],'_')+1)).'a2');
        $merge->outMergeImages(array('quality'=>80,'bOutOrigin'=>false));
    }
    echo "\n".'finish! please to see the path of the text.';
}else{
    echo "\n".'empty path or illegal formation.';
}
die;