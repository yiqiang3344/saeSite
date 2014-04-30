<?php
class YActiveRecord extends CActiveRecord
{

    /*
    *   $_scene 设置之后再重载dealScene方法进行对应处理,如果有对应的scopes则会自动调用
    */
    static private $_scene;
    public static function model($scene=null)
    {
        $m = parent::model(get_called_class());
        self::$_scene = $scene;
        $scopes = $m->scopes();
        if(isset($scopes[$scene])){
            $m = $m->$scene();
        }
        return $m;
    }

    public function getScene(){
        return self::$_scene;
    }

    static protected $table;
    public function tableName(){
        return static::$table;
    }

    public $sceneParams = array();//存储该场景所需变量的容器
    public function dealScene(){

    }

    public function afterFind(){
        $this->dealScene();
        return parent::afterFind();
    }

    /**
     * 因为PDO中返回的数据都是字符串类型的，这里根据DB定义中的定义转化为相应的数据类型
     */
    public function populateRecord($attributes, $callAfterFind = true)
    {
        if($attributes != false){
            $record = $this->instantiate($attributes);
            foreach($record->getMetaData()->columns as $column){
                if(isset($attributes[$column->name])){
                    if(strpos($column->dbType,'unsigned')){
                        $attributes[$column->name] = intval($attributes[$column->name]);
                    }else{
                        $attributes[$column->name] = $column->typecast($attributes[$column->name]);
                    }
                }
            }
        }
        return parent::populateRecord($attributes, $callAfterFind);
    }

    static protected $_with = array();
    public function with(){
        static::$_with[get_called_class()] = array();
        if(func_num_args()>0)
        {
            $with=func_get_args();
            if(is_array($with[0]))  // the parameter is given as an array
                $with=$with[0];
            foreach($with as $v){
                $a = explode(':', $v);
                static::$_with[get_called_class()][] = $a[0];//兼容有scopes的格式
            }
            return parent::with($with);
        }
        return $this;
    }

    public function toArray(){
        $attributes = get_object_vars($this);
        //加上with中的值
        $relatedList = array();
        if(isset(static::$_with[get_called_class()])){
            foreach(static::$_with[get_called_class()] as $v){
                $relatedList[$v] = $this->getRelated($v);
            }
        }
        $attributes = array_merge($attributes,$this->attributes,$relatedList);
        return $attributes;
    }


    public function create($attributes){
        $className = get_called_class();
        $m = new $className('create');
        $m->attributes = $attributes;
        if($m->save()){
            return array(1,array());
        }
        return array(2,$m->getErrors());
    }

    //根据条件获取指定列的列表
    public function getList($select, $condition, $order='', $params=array(), $include_delete=false){
        $className = get_called_class();
        if($condition && is_string($condition)){
            $criteria=new CDbCriteria;
            $criteria->condition = $condition;
            $include_delete || $criteria->addCondition('deleteFlag=0');
        }else{
            $criteria = $condition;
        }
        $criteria->select=$select;
        $criteria->order=$order;
        $criteria->params=$params;
        return modelsToArray($className::model()->findAll($criteria));
    }

    public function getListByPage($select, $condition, $order, $params, $page, $page_size, $require_all, $include_delete=false){
        $className = get_called_class();
        $criteria=new CDbCriteria;
        $condition && ($criteria->condition = $condition);
        $include_delete || $criteria->addCondition('deleteFlag=0');
        if ($page_size == 0) {
            return array(
                'item_count' => $count, 
                'page' => 1, 
                'page_count' => 1, 
                'data' => self::getList($select,$criteria, $order, $params,$include_delete), 
                'page_size' => $page_size
            );
        }
        $count = $className::model()->count($criteria,$params);
        $page_count = ceil($count / $page_size);
        $page = max( min($page,$page_count), 0);
        if ($page > 0) {
            list ( $offset, $limit ) = $require_all ? array(
                    0, $page * $page_size
                ) : array(
                    ($page - 1) * $page_size, $page_size
                );
            $criteria->select=$select;
            $criteria->order=$order;
            $criteria->offset=$offset;
            $criteria->limit=$limit;
            $criteria->params=$params;
            $data = modelsToArray($className::model()->findAll($criteria));
        } else {
            $data = array();
        }
        return array(
            "item_count" => $count, "page" => $page, "page_count" => $page_count, "data" => $data, "page_size" => $page_size
        );
    }
}