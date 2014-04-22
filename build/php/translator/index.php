<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<meta http-equiv="Pragma" content="no-cache">
	<meta http-equiv="no-cache">
	<meta http-equiv="Expires" content="-1">
	<meta http-equiv="Cache-Control" content="no-cache">
	<title>神奇翻译家</title>
	<script type="text/javascript" src="jquery.min.js"></script>
	<script type="text/javascript">
	</script>
</head>
<body>
<?php
require_once "init.php";
$dic=new Dic(DIC);
$plan = new DicPlan();
$plan->setPrimaryDic(array($dic));
if(@$_REQUEST["delete_word"]){
	$dic->remove(@$_REQUEST["delete_word"]);
	$dic->save();
}
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
	$files[]=array($file[0],$file[1],$ret);
}
$items=$dic->item_map;
;?>
<div id="filediv">
<table>
<?php foreach($files as $file){ ;?>
<tr><td><a href="scan.php?file=<?php echo rawurlencode($file[0]);?>" target="_blank"><?php echo $file[0];?></a></td><td><?php echo $file[2]["code"]==1?"PASS":($file[2]["code"]==2?"FAIL":"DIC ERROR");?></td></tr>	
<?php } ;?>
</table>
</div>

<div id="dicdiv">
<table>
<?php foreach($items as $dic_item){ if($dic_item->access>=1){continue;};?>


<tr><td><?php echo htmlentities($dic_item->dev,ENT_QUOTES,"utf-8");?></td><td><?php echo $dic_item->access;?></td><td><a href="?tab=2&delete_word=<?php echo $dic_item->key;?>">删除</a></td></tr>
<?php } ;?>
</table>
</div>
</body>
</html>
