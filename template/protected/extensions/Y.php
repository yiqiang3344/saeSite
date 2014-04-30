<?php
/**
*  @param mixed $name 
*  @param string $type
*  @param array $option option
*               array(
*                   'default'=>$default,
*                   'length'=>array(
*                       'min'=>$min,
*                       'max'=>$max
*                   ),
*               )    
*  @return mixed $_REQUEST[$name] or $options['default']
*/
function getInput($name, $type, $options=array()){
    if(isset($_REQUEST[$name])){
        $ret = $_REQUEST[$name];
    }elseif(isset($options['default'])){
        $ret = $options['default'];
    }else{
        throwException('miss param '.$name, S::EXCEPTION_USER);
    }

    $checkLenth = false;
    if($type=='int'){
        $checkLenth = true;
        !is_numeric($ret) && throwException($name.'`s type must be '.$type, S::EXCEPTION_USER);
    }elseif($type=='str'){
        $checkLenth = true;
        !is_string($ret) && throwException($name.'`s type must be '.$type, S::EXCEPTION_USER);
    }elseif($type=='list'){
        !is_array($ret) && throwException($name.'`s type must be '.$type, S::EXCEPTION_USER);
    }elseif($type=='bool'){
        !is_bool($ret) && throwException($name.'`s type must be '.$type, S::EXCEPTION_USER);
    }else{
        throwException('illegal type:'.$type);
    }

    //长度检测 length = [min=>min,max=>max] min default 0;
    if(isset($options['length']) && $checkLenth){
        $length = $options['length'];
        $len = strlen(trim($ret));
        if(isset($length['min']) && isset($length['max']) && ($len < $length['min'] || $len > $length['max'])){
            throwException($name.'`length must between '.$length['min'].' and '.$length['max'], S::EXCEPTION_USER);
        }elseif(isset($length['min']) && $len < $length['min']){
            throwException($name.'`length must more than '.$length['min'], S::EXCEPTION_USER);
        }elseif(isset($length['max']) && $len > $length['max']){
            throwException($name.'`length must less than '.$length['max'], S::EXCEPTION_USER);
        }
    }

    return $ret;
}

function getUrl($c,$a=null,$p=array()){
    if($a){
        $ret = Yii::app()->getBaseUrl().'/'.$c.'/'.$a;
        $l = array();
        foreach($p as $k=>$v){
            $l[] = urlencode ( $k ) . "=" . urlencode ( $v );
        }
        $ret .= '.html';
        $p && ($ret .= '?'.implode('&', $l));
    }else{
        //非开发环境中的css和js都是压缩过的,开发环境中则不压缩
        if(Yii::app()->language=='dev'){
            if(!preg_match('{^(js/(jquery|tools|main|url|highlighter)|css|img|images)}',$c)){
                //开发语言中需要翻译的
                $c = Yii::app()->language.'/'.$c;
            }
        }else{
            $min_name = str_replace(array('.js','.css'),array('.min.js','.min.css'),$c);
            if(preg_match('{^(js/(jquery|all|highlighter)|css)}',$c)){
                //非开发语言中不需要翻译的
                $c = 'script/'.basename($min_name);
            }elseif(preg_match('{^js}',$c)){
                //非开发语言中需要翻译的
                $c = Yii::app()->language.'/'.$min_name;
            }
        }
        $md5 = @md5_file ($c);
        $ret = Yii::app()->getBaseUrl().'/'.$c.($md5 ? '?v=' . substr ( $md5, 0, 8 ) : '');
    }
    return $ret;
}

function mkMap($list,$key){
    if(!is_array($list)){
        throwException('it`s not list!');
    }
    $l = array();
    foreach($list as $row){
        if(!in_array($key,array_keys($row))){
            throwException('no such key!');
        }
        $l[$row[$key]] = $row;
    }
    return $l;
}

//负值对象或者数组的某些值
function cp($src, $columns, &$dest = array()) {
    foreach ($columns as $column) {
        if (is_array($src)) {
            $dest[$column] = $src[$column];
        } else if (is_object($src)) {
            $dest[$column] = $src->$column;
        } else {
            die("cp:src type error");
        }
    }
    return $dest;
}

function xexplode($delimiter, $string){
    if(!$string){
        return array();
    }
    return explode($delimiter, $string);
}

//针对yii的ar类，将其attributes和自定义的属性都转化为数组
function modelsToArray($models){
    $arr = array();
    if(is_object($models)){
        $attributes = get_object_vars($models);//获取public变量
        if(method_exists($models,'toArray')){
            $attributes = array_merge($attributes,$models->toArray());
        }elseif(isset($models->attributes)){
            $attributes = array_merge($attributes,$models->attributes);
        }
        foreach($attributes as $k=>$v){
            if($v!==null){
                $arr[$k] = modelsToArray($v);
            }
        }
    }elseif(is_array($models)){
        foreach ($models as $k => $m) {
            $arr[$k] = modelsToArray($m);
        }
    }
    return $arr ? $arr : $models;
}


//$time=getTime(true || null);得到当前时间,允许缓存
//$time=getTime(false);得到当前时间,不允许缓存
//$time=getTime('2010-1-1'); 等同于 strtotime
function getTime($refresh = false){
    $add_time = 24*60*60*0 + 60*60*0 + 60*0;
    if ($refresh !== true && $refresh !== false) {
        return strtotime($refresh);
    }
    if (!defined("CUR_TIME") || $refresh) {
        //sql 结果为字符串型，在js中做数学运算会出问题
        $t = intval(Yii::app()->db->createCommand('select unix_timestamp();')->queryScalar()) + $add_time;
        if (!defined("CUR_TIME")) {
            define("CUR_TIME", $t);
        }
        return $t;
    }
    return CUR_TIME;
}

function getDay($refresh = false) {
    if ($refresh === true || $refresh === false) {
        return strtotime(date("Y-m-d", getTime($refresh)));
    } else {
        return strtotime(date("Y-m-d", $refresh));
    }
}

function getMonth($refresh = false) {
    if ($refresh === true || $refresh === false) {
        return strtotime(date("Y-m-0", getTime($refresh)));
    } else {
        return strtotime(date("Y-m-0", $refresh));
    }
}

//格式化时间
function formatTime($t, $format) {
    $t = floor($t);
    $t < 0 && ($t = 0);
    $msg = array(
        0 => array("dev" => "刚才", "zh_cn" => "刚才", "ja" => "今", "zh_tw" => "刚才", "en" => "刚才"),
        1 => array("dev" => "天", "zh_cn" => "天", "ja" => "日", "zh_tw" => "天", "en" => "天"),
        2 => array("dev" => "小时", "zh_cn" => "小时", "ja" => "時間", "zh_tw" => "小时", "en" => "小时"),
        3 => array("dev" => "分钟", "zh_cn" => "分钟", "ja" => "分", "zh_tw" => "分钟", "en" => "分钟"),
        4 => array("dev" => "秒", "zh_cn" => "秒", "ja" => "秒", "zh_tw" => "秒", "en" => "秒"),
        5 => array("dev" => "分钟前", "zh_cn" => "分钟前", "ja" => "分前", "zh_tw" => "分钟前", "en" => "分钟前"),
        6 => array("dev" => "天前", "zh_cn" => "天前", "ja" => "日前", "zh_tw" => "天前", "en" => "天前"),
    );
    if ($format == 2) {//00:00
        return sprintf("%02d", $t / 60) . ":" . sprintf("%02d", $t % 60);
    } else if ($format == 3) {//00:00:00
        return sprintf("%02d", $t / 3600) . ":" . sprintf("%02d", $t / 60 % 60) . ":" . sprintf("%02d", $t % 60);
    } else if ($format == 4) {//多少时间前(超过1天后单位为天）
        return $t < 60 ? $msg[0][Yii::app()->language] : ($t < 3600 ? floor($t / 60) . $msg[5][Yii::app()->language] : ($t < 86400 ? floor($t / 3600) . $msg[2][Yii::app()->language] . floor($t / 60 % 60) . $msg[5][Yii::app()->language] : floor($t / 86400) . $msg[6][Yii::app()->language]));
    } else if ($format == 5) {//多少时间(超过1天后单位为天）
        return $t <= 0 ? "0" . $msg[3][Yii::app()->language] : ($t < 60 ? $t . $msg[4][Yii::app()->language] : ($t < 3600 ? floor($t / 60) . $msg[3][Yii::app()->language] : ($t < 86400 ? floor($t / 3600) . $msg[2][Yii::app()->language] . floor($t / 60 % 60) . $msg[3][Yii::app()->language] : floor($t / 86400) . $msg[1][Yii::app()->language])));
    } else if ($format == 6) {//多少时间，精确到分钟
        return $t <= 0 ? "0" . $msg[3][Yii::app()->language] : ($t < 60 ? $t . $msg[4][Yii::app()->language] : ($t < 3600 ? floor($t / 60) . $msg[3][Yii::app()->language] : ($t < 86400 ? floor($t / 3600) . $msg[2][Yii::app()->language] . floor($t / 60 % 60) . $msg[3][Yii::app()->language] : floor($t / 86400) . $msg[1][Yii::app()->language] . floor($t % 86400 / 3600) . $msg[2][Yii::app()->language] . floor($t / 60 % 60) . $msg[3][Yii::app()->language])));
    } else {
        die("BUG");
    }
}

function getFirstLetter($str){
    $fchar = ord($str{0});
    if($fchar >= ord("A") and $fchar <= ord("z") )
        return strtoupper($str{0});
    $s1 = iconv("UTF-8","gb2312", $str);
    $s2 = iconv("gb2312","UTF-8", $s1);
    if($s2 == $str){
        $s = $s1;
    }else{
        $s = $str;
    }
    $asc = ord($s{0}) * 256 + ord($s{1}) - 65536;
    if($asc >= -20319 and $asc <= -20284) return "A";
    if($asc >= -20283 and $asc <= -19776) return "B";
    if($asc >= -19775 and $asc <= -19219) return "C";
    if($asc >= -19218 and $asc <= -18711) return "D";
    if($asc >= -18710 and $asc <= -18527) return "E";
    if($asc >= -18526 and $asc <= -18240) return "F";
    if($asc >= -18239 and $asc <= -17923) return "G";
    if($asc >= -17922 and $asc <= -17418) return "I";
    if($asc >= -17417 and $asc <= -16475) return "J";
    if($asc >= -16474 and $asc <= -16213) return "K";
    if($asc >= -16212 and $asc <= -15641) return "L";
    if($asc >= -15640 and $asc <= -15166) return "M";
    if($asc >= -15165 and $asc <= -14923) return "N";
    if($asc >= -14922 and $asc <= -14915) return "O";
    if($asc >= -14914 and $asc <= -14631) return "P";
    if($asc >= -14630 and $asc <= -14150) return "Q";
    if($asc >= -14149 and $asc <= -14091) return "R";
    if($asc >= -14090 and $asc <= -13319) return "S";
    if($asc >= -13318 and $asc <= -12839) return "T";
    if($asc >= -12838 and $asc <= -12557) return "W";
    if($asc >= -12556 and $asc <= -11848) return "X";
    if($asc >= -11847 and $asc <= -11056) return "Y";
    if($asc >= -11055 and $asc <= -10247) return "Z";
    return null;
}
    
function toABC($zh){
     $ret = "";
     $s1 = iconv("UTF-8","gb2312", $zh);
     $s2 = iconv("gb2312","UTF-8", $s1);
     if($s2 == $zh){$zh = $s1;}
     for($i = 0; $i < strlen($zh); $i++){
         $s1 = substr($zh,$i,1);
         $p = ord($s1);
         if($p > 160){
             $s2 = substr($zh,$i++,2);
             $ret .= getFirstLetter($s2);
         }else{
             $ret .= $s1;
         }
     }
     return $ret;
}

function getIp(){
    if(getenv('HTTP_CLIENT_IP')) { 
        $onlineip = getenv('HTTP_CLIENT_IP'); 
    } elseif(getenv('HTTP_X_FORWARDED_FOR')) { 
        $onlineip = getenv('HTTP_X_FORWARDED_FOR'); 
    } elseif(getenv('REMOTE_ADDR')) { 
        $onlineip = getenv('REMOTE_ADDR'); 
    } else { 
        $onlineip = $HTTP_SERVER_VARS['REMOTE_ADDR']; 
    }
    return $onlineip;
}

//加密
function FUE($hash,$times=1) {
    for($i=$times;$i>0;$i--) {
        // Encode with base64...
        $hash=base64_encode($hash);
        // and md5...
        $hash=md5($hash);
        // sha1...
        $hash=sha1($hash);
        // sha256... (one more)
        $hash=hash("sha256", $hash);
        // sha512
        $hash=hash("sha512", $hash);
    }
    return $hash;
}

function toLuaArray($arr){
    if(is_array($arr)){
        $a = array();
        foreach($arr as $k=>$v){
            if(is_numeric($k)){
                $a[$k+1] = toLuaArray($v);
            }else{
                $a[$k] = toLuaArray($v);
            }
        }
        $arr = $a;
    }
    return $arr;
}

//自定义排序
class cmpable{
    var $func;
    var $obj;
    function __construct($func,$obj) {
        $this->func=$func;
        $this->obj =$obj;
    }
    function cmp($a,$b){
        return call_user_func($this->func,$a,$b,$this->obj);
    }
}
function mySort($ar,$func,$obj=null){
    if($obj===null){
        @usort($ar,$func);
    }else{
        $cmp_obj=new cmpable($func,$obj);
        usort($ar,array($cmp_obj,"cmp"));
    }
    return $ar;
}
function myCmp($a, $b)
{
    if ($a == $b) {
        return 0;
    }
    return ($a < $b) ? -1 : 1;
}

//文字变量替换
class MSCallback {
    var $param;
    function __construct($param) {
        $this->param = $param;
    }
    private static function text2html($s) {
        return htmlentities($s, ENT_QUOTES, "UTF-8");
    }
    public function func($row) {
        $s = $row[0];
        $name = substr($s, 1, strlen($s) - 2);
        $encode = false;
        if (substr($name, 0, 1) == ":") {
            $encode = true;
            $name = substr($name, 1);
        }
        $data = $name == "?" ? $this->param : $this->param[$name];
        return $encode ? self::text2html($data) : $data;
    }
}
function ms($tmpl, $param) {
    $callback = new MSCallback($param);
    return preg_replace_callback("{\\{\\:?[A-Za-z0-9_\\?]+\\}}u", array($callback, "func"), $tmpl);
}

/**
*  auto open or close transaction,never conflict.
*  @param boolean bForce ：if transaction allready begin,and this params is true,will throw exception.
*/
class AutoTransactionBegin
{
    private $active;
    private $transactions;

    //默认同时打开db和wordDb的事物，可通过options.toDb来设置只打开某一个
    function __construct($bForce=false,$options=array()){
        $options['toDb'] = isset($options['toDb']) ? $options['toDb'] : null;
        if($options['toDb']===null){
            $currentTransaction = Yii::app()->db->getCurrentTransaction();
        }else{
            if(!Yii::app()->{$options['toDb']}){
                throwException('illegal db type.');
            }
            $currentTransaction = Yii::app()->{$options['toDb']}->getCurrentTransaction();
        }

        if($currentTransaction && $bForce){//如果某处要强行开事物，但之前已经有事物，则会报错
            throwException('transaction allready begin.');
        }elseif($currentTransaction){
            $this->active = false;//如果在此之前已经打开，则此处也既不会开启事物，此实例也不会结束已开启的事物
            $this->transactions = array();
        }else{
            $this->active = true;
            if($options['toDb']===null){
                $this->transactions = array(
                    Yii::app()->db->beginTransaction(),
                    Yii::app()->worldDb->beginTransaction()
                );
            }else{
                $this->transactions = array(
                    Yii::app()->{$options['toDb']}->beginTransaction()
                );
            }
        }
    }

    public function rollback(){
        if($this->active){
            foreach($this->transactions as $v){
                $v->rollback();
            }
        }
        return true;
    }

    public function commit(){
        if($this->active){
            foreach($this->transactions as $v){
                $v->commit();
            }
        }
        return true;
    }
}

function throwException($message, $excepitonType=S::EXCEPTION_USER, $checkTransaction=true, $params=array()){
    if($checkTransaction && $transaction = Yii::app()->db->getCurrentTransaction()){
        $transaction->rollback();
    }

    //判断是用户操作异常还是代码异常
    $excepitonType = $excepitonType==S::EXCEPTION_USER ? 'UException' : 'CException';

    if(is_array($message)){
        $l = array();
        foreach($message as $s){
            $l[] = $s[0];
        }
        $message = implode(';',$l);
    }

    if($message instanceOf Exception){
        $e = $message;
    }else{
        if(isset($params["errorCode"])){
            $e = new $excepitonType($message, $params["errorCode"]);
        }else{
            $e = new $excepitonType($message);
        }
    }
    throw $e;
}

//简单序列化
class SSerialize{
    private static $delimiters = array(',','#','<','|');//分割符
    private static function decodeOne($delimiters,$str){
        $ret = $str;
        if($delimiters){
            $ret = array();
            foreach(xexplode(array_shift($delimiters),$str) as $v){
                $ret[] = self::decodeOne($delimiters,$v);
            }
        }
        return $ret;
    }
    private static function encodeOne($delimiters,$val){
        $ret = $val;
        if($delimiters){
            $delimiter = array_shift($delimiters);
            $a = array();
            foreach($val as $row){
                $a[] = self::encodeOne($delimiters,$row);
            }
            $ret = implode($delimiter, $a);
        }
        return $ret;
    }
    public static function encode($arr,$layers=1){
        if(!$arr){
            $arr = array();
        }
        return self::encodeOne(array_slice(self::$delimiters, 0, $layers),$arr);
    }

    public static function decode($str,$layers=1){
        if($str==''){
            return array();
        }
        $ret = self::decodeOne(array_slice(self::$delimiters, 0, $layers),$str);
        if(!$ret){
            throwException('SSerialize error,illegal farmat.');
        }
        return $ret;
    }
}