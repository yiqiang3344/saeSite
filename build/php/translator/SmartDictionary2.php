<?php
/**
 * 二代字典替换引擎 qian.yu
 */

require_once "STConvertor.php";

//表示字典中的一行
class DicItem{
	public $comment;
	public $key; //crc32 dev
	public $len; //strlen dev
	public $dev;
	public $zh_cn;
	public $zh_tw;
	public $ja;
	public $en;
	public $access=0;

	/* 允许三种形式 #开头的注释 空行 正常的翻译
	#abc
	
	|a|b|c|d|e|	
	*/
	static public function parse($line){
		global $trans;
		
		$dic_item=new DicItem();		
		$line=trim($line);
		if($line==""){
			//空行
		}elseif(substr($line,0,1)=="#"){
			//就一个注释的一行
			$dic_item->comment=trim(substr($line,1));
		}else{
			$words=preg_split("{\\|}u",$line);
			if(count($words)<2 || $words[0]!="" || trim($words[1])==""){
				return false;
			}
			$dic_item->dev=trim(@$words[1]);
			$dic_item->key=sprintf("%u",crc32($dic_item->dev));
			$dic_item->len=strlen($dic_item->dev);
			$dic_item->ja=trim(@$words[2]);
			if($dic_item->ja==""){
				echo "warning some words not translate to ja ".$dic_item->dev."\n<br/>";
			}
			$dic_item->zh_cn=trim(@$words[3]);
			
			$dic_item->zh_tw=trim(@$words[4]);
			if($dic_item->zh_tw==""){
				$dic_item->zh_tw=STConvertor::s2t($dic_item->zh_cn?$dic_item->zh_cn:$dic_item->dev);//trim(@$words[4]);
			}
			$dic_item->en=trim(@$words[5]);
			
		}
		return $dic_item;
	}
	public function toString(){
		$s="";
		if(is_string($this->key) && strlen($this->key)>0){
			$s.="|".strval($this->dev)."|".strval($this->ja)."|".strval($this->zh_cn)."||".strval($this->en)."|";
		}elseif(is_string($this->comment)){
			$s.="#".$this->comment;
		}
		return $s;
	}
}

//表示一个字典
class Dic{
	public $items=array();
	public $item_map =array();

	public $file;
	public function __construct($file=null){
		if($file!==null){
			$this->load($file);
		}
	}	

	public function load($file){
		$this->clear();
		$this->file=$file;
		$content=file_get_contents($file);
		if($content===false){
			die("dic file cannot open: ".$file);	
		}
		
		foreach(preg_split("{\\r?\\n}su",$content) as $line){
			$dic_item=DicItem::parse($line);
			if($dic_item===false){
				die("dic item error file $file line $line");
			}
			$this->add($dic_item);
		}
	}
	private function clear(){
		$this->items=array();
		$this->item_map=array();
		$this->file=null;
	}

	public function save($file=null){
		$s="";
		foreach($this->items as $dic_item){
			$s.=$dic_item->toString()."\r\n";
		}
		if($file===null){
			$file=$this->file;
		}
		file_put_contents($file,$s);
	}

	public function add($dic_item){
		if($dic_item->key &&  @$this->item_map[$dic_item->key]){
			die("dic item exists ".$dic_item->dev." in file ".$this->file);
		}
		$this->items[]=$dic_item;
		if($dic_item->key){
			$this->item_map[$dic_item->key]=$dic_item;
		}
	}
	public function remove($key){
		if(@$this->item_map[$key]){
			unset($this->item_map[$key]);
			foreach($this->items as $k=>$dic_item){
				if($dic_item->key==$key){
					unset($this->items[$k]);
					$this->items=array_values($this->items);
					break;
				}
			}
		}
	}
}

//表示几个字典组成的翻译计划
class DicPlan{
	private $primary_dic_list=array();
	private $secondary_dic_list=array();
	private $comments;
	private $find_str;
	private $replace_key;
	private $items;
	private $bool_log_access=false;
	
	public function __construct(){
		
	}
	public function setPrimaryDic($dic_list){
		$this->primary_dic_list=$dic_list;
	}
	
	private function replace_comment_callback($m){
		$comment=$m[0];
		$crc=sprintf("%u",crc32($comment));
		$this->comments[$crc]=$comment;
		return "!!@!!".$crc."@@!@@";
	}
	
	public function compile(){
		$this->comments=array();
		$dic_item_map=array();
		foreach($this->primary_dic_list as $dic){
			foreach($dic->items as $dic_item){
				$dic_item_map[$dic_item->key]=$dic_item;
			}
		}
		foreach($this->secondary_dic_list as $dic){
			foreach($dic->items as $dic_item){
				$dic_item_map[$dic_item->key]=$dic_item;
			}
		}		
		$this->items=$dic_item_map;
		$len_list=array();
		$item_list=array();
		foreach($dic_item_map as $dic_item){
			$len_list[]=$dic_item->len;
			$item_list[]=$dic_item;
		}
		array_multisort($len_list,SORT_DESC,SORT_NUMERIC,$item_list);			
		$replace_crc=array();
		$find=array();
		foreach($item_list as $dic_item){
			$replace_crc[]="!!@!!".$dic_item->key."@@!@@";
			$find[]=$dic_item->dev;
		}
		$this->find_str=$find;
		$this->replace_key=$replace_crc;		
	}
	
	private function replace_callback($m){
		$crc=$m[1];
		if(@$this->items[$crc]){
			if($this->bool_log_access){
				$this->items[$crc]->access++;
			}			
			$str=$this->items[$crc]->{$this->lang};
			if(trim($str)==""){
				$str=$this->items[$crc]->dev;
			}			
			return $str;
		}elseif(@$this->comments[$crc]){
			return $this->comments[$crc];
		}
	}

	static function text2html($str){
		return str_replace(array("&","<",">","\"","\f","\0","\r\n","\n","\r"," ","\t","'"),array("&amp;","&lt;","&gt;","&quot;","","","<br/>","<br/>","","&nbsp;","&nbsp;&nbsp;&nbsp;&nbsp;","&#039;"),$str);
	}
			
	public function scan_file($file,$secondary_dic_list){
		$this->secondary_dic_list=$secondary_dic_list;
		$this->compile();
		$content=@file_get_contents($file);
		if($content===false){
			die("$file not exists");
		}
		//先把注释替换成数字
		$content=preg_replace_callback("{(?://.*\\?\\>)|(?://.*[\\r\\n])|(?s:/\\*.*?\\*/)}u",array($this,"replace_comment_callback"),$content);		
		//再把字典中的字替换成数字
		$content=str_replace($this->find_str,$this->replace_key,$content);		
		//如果有非ASCII字符
		if(!preg_match("{^[\\000-\\177]*\$}su",$content)){
			//把非ASCII做上标记
			$content=preg_replace("{[^\\000-\\177]+}u","!!@!!\\0@@!@@",$content);
			$this->lang="dev";
			$content=preg_replace_callback("{!!@!!(\\d+)@@!@@}u",array($this,"replace_callback"),$content);
			$html=self::text2html($content);
			//把非ASCII加亮
			$html=preg_replace("{!!@!!(.*?)@@!@@}u","<span class=\"e\">\\1</span>",$html);
			//含有非ASCII的错误
			return array("code"=>2,"html"=>$html);
		}
		$this->bool_log_access=true;//记下一次成功匹配记录，只需要记一个语言就行了，其他语言不用记
		$this->lang="zh_cn";
		$zh_cn_content=preg_replace_callback("{!!@!!(\\d+)@@!@@}u",array($this,"replace_callback"),$content);
		$this->bool_log_access=false;
		$this->lang="ja";
		$ja_content=preg_replace_callback("{!!@!!(\\d+)@@!@@}u",array($this,"replace_callback"),$content);
		$this->lang="zh_tw";
		$zh_tw_content=preg_replace_callback("{!!@!!(\\d+)@@!@@}u",array($this,"replace_callback"),$content);
		$this->lang="en";
		$en_content=preg_replace_callback("{!!@!!(\\d+)@@!@@}u",array($this,"replace_callback"),$content);
		foreach($this->secondary_dic_list as $dic){
			foreach($dic->item_map as $dic_item){
				if($dic_item->access==0){//附加字典必须要求每一项都有匹配，如果附加字典里有的没有匹配上，那是要报错的
					return array("code"=>3,"html"=>"找不到匹配项  ".$dic_item->dev);//附加字典没有匹配
					//die("item has no match ".$dic->file." ".$dic_item->dev);
				}
			}
		}
		//一次成功的匹配
		return array("code"=>1,"zh_cn"=>$zh_cn_content,"ja"=>$ja_content,"zh_tw"=>$zh_tw_content,"en"=>$en_content);
	}
}
