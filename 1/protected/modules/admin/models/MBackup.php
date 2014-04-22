<?php
class MBackup extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return User the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'backup';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name', 'required'),
        );
    }

    public function defaultScope()
    {
        return array(
            'condition'=>'deleteFlag=0',
        );
    }

    public function scopes()
    {
        return array(
        );
    }

    protected function beforeSave() {
        if($this->isNewRecord) {
            //uuid作为file
            $file = uniqid('backup', true).'.sql';
            //检查备份目录是否存在，不存在则创建
            $dir = Yii::app()->getBasePath().'/'.Yii::app()->params['backupDir'];
            !is_dir($dir) && mkdir($dir,0777);
            //执行mysqldump命令
            exec(Yii::app()->params['mysqlDir'].'/mysqldump -h'.Yii::app()->params['dbHost'].' -u'.Yii::app()->params['dbUser'].' -p'.Yii::app()->params['dbPassword'].' --ignore-table='.Yii::app()->params['dbName'].'.YiiSession --ignore-table='.Yii::app()->params['dbName'].'.backup --ignore-table='.Yii::app()->params['dbName'].'.admin --add-drop-table --opt '.Yii::app()->params['dbName'].' > '.$dir.'/'.$file);
            $this->file = $file;
            $this->createTime =     getTime();
        }
        return parent::beforeSave();
    }

    public function reback(){
        $ret = array(1,null);
        $file = Yii::app()->getBasePath().'/'.Yii::app()->params['backupDir'].'/'.$this->file;
        if(!is_file($file)){
            $ret = array(2,'备份文件不存在！');
        }
        //执行备份
        exec(Yii::app()->params['mysqlDir'].'/mysql -h'.Yii::app()->params['dbHost'].' -u'.Yii::app()->params['dbUser'].' -p'.Yii::app()->params['dbPassword'].' '.Yii::app()->params['dbName'].' < '.$file);
        //修改数据表
        $this->lastRebackTime =     getTime();
        $this->save();
        return $ret;
    }

    public static function deleteByIds($ids){
            begin();
        $criteria=new CDbCriteria;
        $criteria->addInCondition('id',$ids);
        //删除文件
        foreach(self::model()->findAll($criteria) as $m){
            $file = Yii::app()->getBasePath().'/'.Yii::app()->params['backupDir'].'/'.$m->file;
            is_file($file) && unlink($file);
        }
        self::model()->updateAll(array('deleteFlag'=>1,'file'=>''),$criteria);
            commit();
        return true;
    }

    public static function updateByIds($ids){
        return     updateByIds(__CLASS__,$ids);
    }

    //根据条件获取指定列的列表
    public static function getList($select, $condition, $order='', $params=array(), $include_delete=false){
        return     getList(__CLASS__,$select, $condition, $order='', $params=array(), $include_delete);
    }

    //根据条件获取全部信息的列表并分页
    public static function getListByPage($select, $condition, $order, $params, $page, $page_size, $require_all, $include_delete=false){
        return     getListByPage(__CLASS__,$select, $condition, $order, $params, $page, $page_size, $require_all, $include_delete);
    }
}