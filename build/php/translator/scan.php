<?php
header("content-type:text/html; charset=utf-8"); 
require_once "init.php";
$file=strval(@$_REQUEST["file"]);
$word=strval(@$_REQUEST["word"]);
$dic=new Dic(DIC);
if($word){
	$dic->add(DicItem::parse("|$word|"));	
	$dic->save();
}
$plan = new DicPlan();
$plan->setPrimaryDic(array($dic));
if(file_exists($file.".dic")){
	$ret=$plan->scan_file($file,array(new Dic($file.".dic")));
}else{
	$ret=$plan->scan_file($file,array());
}
;?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<meta http-equiv="Pragma" content="no-cache">
	<meta http-equiv="no-cache">
	<meta http-equiv="Expires" content="-1">
	<meta http-equiv="Cache-Control" content="no-cache">
	<title>神奇翻译家</title>
	<style>
.e{background-color:yellow;color:red}
	</style>
	<script type="text/javascript" src="jquery.min.js"></script>
	<script type="text/javascript">
	var ret=<?php echo json_encode($ret);?>;	
	
	function show(tab){
		if(ret.code!=1){
			return;
		}
		$("#showText").val(
			tab==1?ret.zh_cn:tab==2?ret.ja:tab==3?ret.zh_tw:ret.en
		)
	}	
	$(document).ready(function(e) {
		$("#input_file").val(<?php echo json_encode($file);?>);
		$("#input_file2").val(<?php echo json_encode($file);?>)	;	
		show(1);
	});		
	</script>
</head>
<body>

<div><a href="index.php">返回</a></div>
<div>
	<form action="?" method="get">
		翻译文件地址<input id="input_file" type="text" name="file" /><input type="submit" value="提交"/>
	</form>
</div>
<div>
	<form action="?" method="get">
		<input id="input_file2" type="hidden" name="file" />
		加入字典<input id="input_word" type="text" name="word" /><input type="submit" value="提交"/>
	</form>
</div>
<?php if($ret && $ret["code"]==2){ ;?>
<h1>翻译有错误</h1>
<div><?php echo $ret["html"];?></div>
<?php }elseif($ret && $ret["code"]==3) {;?>
<h1>字典文件错误</h1>
<div><?php echo $ret["html"];?></div>
<?php }elseif($ret && $ret["code"]==1) {;?>
<div>
<a href="javascript:void(0)" onClick="show(1)">中文</a> <a href="javascript:void(0)" onClick="show(2)">日文</a> <a href="javascript:void(0)" onClick="show(3)">繁体</a> <a href="javascript:void(0)" onClick="show(4)">英文</a><br/>
<textarea id="showText" style="width:100%;height:500px;">
</textarea>

<div>
<?php } ;?>
</body>
</html>
