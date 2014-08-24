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
            if(!preg_match('{^(js/(jquery|tools|main|url|highlighter|jsBeautify)|css|img|images)}',$c)){
                //开发语言中需要翻译的
                $c = Yii::app()->language.'/'.$c;
            }
        }else{
            $min_name = str_replace(array('.js','.css'),array('.min.js','.min.css'),$c);
            if(preg_match('{^(js/(jquery|all|highlighter|jsBeautify)|css)}',$c)){
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


function strToArr($str){
    $pushByKey = function($str,$s_pos,$i,&$arr,&$key,$v=false){
        $v = $v?$v:substr($str, $s_pos, $i-$s_pos);
        if(is_string($v) && (strpos($v,'\'')===0 || strpos($v,'"')===0)){
            $v = substr($v, 1, strlen($v)-2);
        }
        if($key){
            if(strpos($key,'\'')===0 || strpos($key,'"')===0){
                $key = substr($key, 1, strlen($key)-2);
            }
            $arr[$key] = $v;
            $key = false;
        }else{
            $arr[] = $v;
        }
    };

    $str = str_replace(array("\n","\r\n","\t"," "), '', $str);
    $ass = 'array(';
    if(strpos($str,$ass)!==0){
        return array(null);//非数组则返回空值
    }
    $arr = array();
    $key = false;
    $s_pos = strlen($ass);
    for($i=$s_pos,$c=strlen($str);$i<$c;$i++){
        if($str[$i]=='"'){
            if(($i=noEscapeStrPos($str,'"',$i+1)) === false){
                break;
            }
        }elseif($str[$i]=='\''){
            if(($i=noEscapeStrPos($str,'\'',$i+1)) === false){
                break;
            }
        }elseif(substr($str, $i, 2)=='=>'){
            $key = substr($str, $s_pos, $i-$s_pos);
            $i += 1;
            $s_pos = $i+1;
        }elseif(substr($str, $i, 6)==$ass){
            list($aa,$ii) = strToArr(substr($str, $i));
            if($aa===null){
                break;
            }
            $pushByKey($str,$s_pos,$i,$arr,$key,$aa);
            $i += $ii;
            $s_pos = $i+1;
        }elseif($str[$i]==','){
            $pushByKey($str,$s_pos,$i,$arr,$key);
            $s_pos = $i+1;
        }elseif($str[$i]==')'){
            $pushByKey($str,$s_pos,$i,$arr,$key);
            return array($arr,$i);
        }
    }
    return array(null,null);
}

class JSMin {
  const ORD_LF            = 10;
  const ORD_SPACE         = 32;
  const ACTION_KEEP_A     = 1;
  const ACTION_DELETE_A   = 2;
  const ACTION_DELETE_A_B = 3;

  protected $a           = '';
  protected $b           = '';
  protected $input       = '';
  protected $inputIndex  = 0;
  protected $inputLength = 0;
  protected $lookAhead   = null;
  protected $output      = '';

  // -- Public Static Methods --------------------------------------------------

  /**
   * Minify Javascript
   *
   * @uses __construct()
   * @uses min()
   * @param string $js Javascript to be minified
   * @return string
   */
  public static function minify($js) {
    $jsmin = new JSMin($js);
    return $jsmin->min();
  }

  // -- Public Instance Methods ------------------------------------------------

  /**
   * Constructor
   *
   * @param string $input Javascript to be minified
   */
  public function __construct($input) {
    $this->input       = str_replace("\r\n", "\n", $input);
    $this->inputLength = strlen($this->input);
  }

  // -- Protected Instance Methods ---------------------------------------------

  /**
   * Action -- do something! What to do is determined by the $command argument.
   *
   * action treats a string as a single character. Wow!
   * action recognizes a regular expression if it is preceded by ( or , or =.
   *
   * @uses next()
   * @uses get()
   * @throws JSMinException If parser errors are found:
   *         - Unterminated string literal
   *         - Unterminated regular expression set in regex literal
   *         - Unterminated regular expression literal
   * @param int $command One of class constants:
   *      ACTION_KEEP_A      Output A. Copy B to A. Get the next B.
   *      ACTION_DELETE_A    Copy B to A. Get the next B. (Delete A).
   *      ACTION_DELETE_A_B  Get the next B. (Delete B).
  */
  protected function action($command) {
    switch($command) {
      case self::ACTION_KEEP_A:
        $this->output .= $this->a;

      case self::ACTION_DELETE_A:
        $this->a = $this->b;

        if ($this->a === "'" || $this->a === '"') {
          for (;;) {
            $this->output .= $this->a;
            $this->a       = $this->get();

            if ($this->a === $this->b) {
              break;
            }

            if (ord($this->a) <= self::ORD_LF) {
              throw new JSMinException('Unterminated string literal.');
            }

            if ($this->a === '\\') {
              $this->output .= $this->a;
              $this->a       = $this->get();
            }
          }
        }

      case self::ACTION_DELETE_A_B:
        $this->b = $this->next();

        if ($this->b === '/' && (
            $this->a === '(' || $this->a === ',' || $this->a === '=' ||
            $this->a === ':' || $this->a === '[' || $this->a === '!' ||
            $this->a === '&' || $this->a === '|' || $this->a === '?' ||
            $this->a === '{' || $this->a === '}' || $this->a === ';' ||
            $this->a === "\n" )) {

          $this->output .= $this->a . $this->b;

          for (;;) {
            $this->a = $this->get();

            if ($this->a === '[') {
              /*
                inside a regex [...] set, which MAY contain a '/' itself. Example: mootools Form.Validator near line 460:
                  return Form.Validator.getValidator('IsEmpty').test(element) || (/^(?:[a-z0-9!#$%&'*+/=?^_`{|}~-]\.?){0,63}[a-z0-9!#$%&'*+/=?^_`{|}~-]@(?:(?:[a-z0-9](?:[a-z0-9-]{0,61}[a-z0-9])?\.)*[a-z0-9](?:[a-z0-9-]{0,61}[a-z0-9])?|\[(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\])$/i).test(element.get('value'));
              */
              for (;;) {
                $this->output .= $this->a;
                $this->a = $this->get();

                if ($this->a === ']') {
                    break;
                } elseif ($this->a === '\\') {
                  $this->output .= $this->a;
                  $this->a       = $this->get();
                } elseif (ord($this->a) <= self::ORD_LF) {
                  throw new JSMinException('Unterminated regular expression set in regex literal.');
                }
              }
            } elseif ($this->a === '/') {
              break;
            } elseif ($this->a === '\\') {
              $this->output .= $this->a;
              $this->a       = $this->get();
            } elseif (ord($this->a) <= self::ORD_LF) {
              throw new JSMinException('Unterminated regular expression literal.');
            }

            $this->output .= $this->a;
          }

          $this->b = $this->next();
        }
    }
  }

  /**
   * Get next char. Convert ctrl char to space.
   *
   * @return string|null
   */
  protected function get() {
    $c = $this->lookAhead;
    $this->lookAhead = null;

    if ($c === null) {
      if ($this->inputIndex < $this->inputLength) {
        $c = substr($this->input, $this->inputIndex, 1);
        $this->inputIndex += 1;
      } else {
        $c = null;
      }
    }

    if ($c === "\r") {
      return "\n";
    }

    if ($c === null || $c === "\n" || ord($c) >= self::ORD_SPACE) {
      return $c;
    }

    return ' ';
  }

  /**
   * Is $c a letter, digit, underscore, dollar sign, or non-ASCII character.
   *
   * @return bool
   */
  protected function isAlphaNum($c) {
    return ord($c) > 126 || $c === '\\' || preg_match('/^[\w\$]$/', $c) === 1;
  }

  /**
   * Perform minification, return result
   *
   * @uses action()
   * @uses isAlphaNum()
   * @uses get()
   * @uses peek()
   * @return string
   */
  protected function min() {
    if (0 == strncmp($this->peek(), "\xef", 1)) {
        $this->get();
        $this->get();
        $this->get();
    } 

    $this->a = "\n";
    $this->action(self::ACTION_DELETE_A_B);

    while ($this->a !== null) {
      switch ($this->a) {
        case ' ':
          if ($this->isAlphaNum($this->b)) {
            $this->action(self::ACTION_KEEP_A);
          } else {
            $this->action(self::ACTION_DELETE_A);
          }
          break;

        case "\n":
          switch ($this->b) {
            case '{':
            case '[':
            case '(':
            case '+':
            case '-':
            case '!':
            case '~':
              $this->action(self::ACTION_KEEP_A);
              break;

            case ' ':
              $this->action(self::ACTION_DELETE_A_B);
              break;

            default:
              if ($this->isAlphaNum($this->b)) {
                $this->action(self::ACTION_KEEP_A);
              }
              else {
                $this->action(self::ACTION_DELETE_A);
              }
          }
          break;

        default:
          switch ($this->b) {
            case ' ':
              if ($this->isAlphaNum($this->a)) {
                $this->action(self::ACTION_KEEP_A);
                break;
              }

              $this->action(self::ACTION_DELETE_A_B);
              break;

            case "\n":
              switch ($this->a) {
                case '}':
                case ']':
                case ')':
                case '+':
                case '-':
                case '"':
                case "'":
                  $this->action(self::ACTION_KEEP_A);
                  break;

                default:
                  if ($this->isAlphaNum($this->a)) {
                    $this->action(self::ACTION_KEEP_A);
                  }
                  else {
                    $this->action(self::ACTION_DELETE_A_B);
                  }
              }
              break;

            default:
              $this->action(self::ACTION_KEEP_A);
              break;
          }
      }
    }

    return $this->output;
  }

  /**
   * Get the next character, skipping over comments. peek() is used to see
   *  if a '/' is followed by a '/' or '*'.
   *
   * @uses get()
   * @uses peek()
   * @throws JSMinException On unterminated comment.
   * @return string
   */
  protected function next() {
    $c = $this->get();

    if ($c === '/') {
      switch($this->peek()) {
        case '/':
          for (;;) {
            $c = $this->get();

            if (ord($c) <= self::ORD_LF) {
              return $c;
            }
          }

        case '*':
          $this->get();

          for (;;) {
            switch($this->get()) {
              case '*':
                if ($this->peek() === '/') {
                  $this->get();
                  return ' ';
                }
                break;

              case null:
                throw new JSMinException('Unterminated comment.');
            }
          }

        default:
          return $c;
      }
    }

    return $c;
  }

  /**
   * Get next char. If is ctrl character, translate to a space or newline.
   *
   * @uses get()
   * @return string|null
   */
  protected function peek() {
    $this->lookAhead = $this->get();
    return $this->lookAhead;
  }
}

class JSMinException extends Exception {}

final class FormatCss{
    static private function indent($counts){
        return implode(array_pad(array(), 4*$counts, ' '),'');
    }
    
    static private function dealNote(&$str,&$i,&$c,$s_blank='',$blank=''){
        $endpos = strpos($str, '*/', $i+2)+1;
        if(in_array($before=substr($str, $i-1,1),array(';','}'))){//所有; } 之后的注释都换行
            $b = '';
            if($s_blank!==null){
                $b = substr($str, $i-1,1)=='}'?$s_blank:$blank;//缩进处理
            }
            $i = $endpos+1;
            $s = substr($str, 0, $i);
            $s1 = ltrim(substr($str, $i+1));
            $str = $s."\r\n".$b.$s1;
            $c=strlen($str);
        }elseif(strpos($s2=ltrim(substr($str, $endpos+1),"\t \x0B\0"),"\r\n")===0 || strpos($s2,"\n")===0){//注释后面有换行则加缩进
            $i = $endpos+1;
            $s = substr($str, 0, $i);
            $str = $s."\r\n".$blank.ltrim(substr($str, $i+1));
        }else{//其他注释不处理
            $i = $endpos+1;
        }
    }

    static private function dealEndSign(&$str,&$i,&$c,$s_blank,$blank){
        $s = substr($str, 0, $i+1);
        $o_s1 = substr($str, $i+1);
        $s1 = ltrim($o_s1);

        if($str[$i]=='}' && strlen($s)>1 && !preg_match("/[;{]\\s+}|\$/",$s)){
            $s = substr_replace($s, "\r\n".$s_blank, -1, 0);//不以;或{结尾的}前加换行和缩进
        }

        if(substr($s1, 0, 1)=='}' && $str[$i]=='}'){
            $str = $s."\r\n".$s1;
        }elseif(substr($s1, 0, 1)=='}' && $str[$i]==';'){
            $str = $s."\r\n".$s_blank.$s1;
        }elseif(substr($s1, 0, 2)=='/*'){
            if(($s2_before=substr($s2=ltrim($o_s1,"\t \x0B\0"), 0, 2))!='/*' && (strpos($s2,"\r\n")===0 || strpos($s2,"\n")===0)){
                $str = $s."\r\n".($str[$i]==';'?$blank:$s_blank).ltrim($o_s1);
            }else{
                $str = $s.$s1;//紧接着注释则不换行
            }
        }elseif($str[$i]=='}'){
            $str = $s."\r\n".$s_blank.$s1;
        }elseif($str[$i]==';'){
            $str = $s."\r\n".$blank.$s1;
        }
        $c=strlen($str);
    }

    static private function dealOne($str,$indentation=1){
        $blank = self::indent($indentation);
        $s_blank = self::indent($indentation-1);
        $str=ltrim($str);
        if($str[0]!='}'){
            $str = "\r\n".$blank.$str;
        }
        for($i=0,$c=strlen($str);$i<$c;$i++){
            if($str[$i]=='{'){
                list($r,$l) = self::dealOne(substr($str,$i+1),$indentation+1);
                if(!$r){
                    break;
                }
                $str = substr($str, 0, $i+1).$r;
                $c=strlen($str);
                $i+=$l;
            }elseif($str[$i]==':' || $str[$i]==','){
                $str = substr($str, 0, $i+1).' '.ltrim(substr($str, $i+1));
                $c=strlen($str);
            }elseif(substr($str, $i, 2)=='/*'){
                self::dealNote($str,$i,$c,$s_blank,$blank);
            }elseif($str[$i]==';'){
                self::dealEndSign($str,$i,$c,$s_blank,$blank);
            }elseif($str[$i]=='}'){
                self::dealEndSign($str,$i,$c,$s_blank,$blank);
                return array($str,$i+1);
            }
        }
        return array(null,null);
    }

    static public function format($str){
        $str = trim($str);
        $s_pos = 0;
        for($i=0,$c=strlen($str);$i<$c;$i++){
            if($str[$i]=='{'){
                list($r,$l) = self::dealOne(substr($str, $i+1));
                if($r===null){
                    return null;
                }
                $str = substr($str, 0, $i+1).$r;
                $c=strlen($str);
                $i += $l;
            }elseif(substr($str, $i, 2)=='/*'){
                self::dealNote($str,$i,$c);
            }
        }
        return $str;
    }
}