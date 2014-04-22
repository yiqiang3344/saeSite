<?php
class YActiveRecord extends CActiveRecord
{
    const DEFAULT_SCENE = 1;

    static private $_scene;
    public function setScene($value=self::DEFAULT_SCENE){
        self::$_scene = $value;
        return $this;
    }
    public function resetScene(){
        self::$_scene = null;
        return $this;
    }
    public function getScene(){
        return self::$_scene;
    }

    public function afterFind(){
        $this->doScene();
        return parent::afterFind();
    }

    public function doScene(){

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

    public function toArray(){
        $attributes = get_object_vars($this);
        $attributes = array_merge($attributes,$this->attributes);
        return $attributes;
    }


    static public function create($model_name,$attributes){
        $m = new $model_name;
        $m->attributes = $attributes;
        if($m->save()){
            return array(1,array());
        }
        return array(2,$m->getErrors());
    }

    static public function updateByIds($model_name, $ids, $attributes){
        $criteria=new CDbCriteria;
        $criteria->addInCondition('id',$ids);
        return $model_name::model()->updateAll($attributes,$criteria);
    }

    static public function deleteByIds($model_name,$ids){
        $criteria=new CDbCriteria;
        $criteria->addInCondition('id',$ids);
        return $model_name::model()->updateAll(array('deleteFlag'=>1),$criteria);
    }

    //根据条件获取指定列的列表
    static public function getList($model_name,$select, $condition, $order='', $params=array(), $include_delete=false){
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
        return     modelsToArray($model_name::model()->findAll($criteria));
    }

    //根据条件获取全部信息的列表并分页
    static public function getListByPage($model_name,$select, $condition, $order, $params, $page, $page_size, $require_all, $include_delete=false){
        $criteria=new CDbCriteria;
        $condition && ($criteria->condition = $condition);
        $include_delete || $criteria->addCondition('deleteFlag=0');
        if ($page_size == 0) {
            return array(
                'item_count' => $count, 
                'page' => 1, 
                'page_count' => 1, 
                'data' => $model_name::getList($select,$criteria, $order, $params,$include_delete), 
                'page_size' => $page_size
            );
        }
        $count = $model_name::model()->count($criteria,$params);
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
            $data =     modelsToArray($model_name::model()->findAll($criteria));
        } else {
            $data = array();
        }
        return array(
            "item_count" => $count, "page" => $page, "page_count" => $page_count, "data" => $data, "page_size" => $page_size
        );
    }
}