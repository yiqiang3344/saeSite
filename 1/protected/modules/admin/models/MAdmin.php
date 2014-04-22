<?php
class MAdmin extends CActiveRecord
{
    private $_identity;
    public $passwordConfirm;
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
        return 'admin';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('username, password', 'required'),
            array('passwordConfirm', 'required', 'on' => 'create'),
            array('passwordConfirm', 'compare', 'compareAttribute' => 'password', 'on' => 'create'),
            array('username', 'unique', 'on' => 'create'),
            array('password', 'length', 'min' => 4, 'max' => 15, 'on' => 'create'),
            array('password', 'authenticate', 'on' => 'login'),
        );
    }

    public function scopes()
    {
        return array(
            'canUse'=>array(
                'condition'=>'deleteFlag=0',
            ),
            'UD'=>array(
                'select'=>'id,username,super',
            ),
        );
    }

    protected function beforeSave() {
        if($this->isNewRecord) {
            $this->password = $this->encrypPassword($this->password);
            $this->recordTime =     getTime();
        }
        return parent::beforeSave();
    }
    
    public function authenticate()
    {
        if(!$this->hasErrors()) {
            $this->_identity = new UserIdentity($this->username, $this->password);
            
            if($this->_identity->authenticate() !== UserIdentity::ERROR_NONE) {
                $this->addError('password', Yii::t('model','password error'));
            }
        }
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
        );
    }

    public function validatePassword($password)
    {
        if($this->encrypPassword($password) === $this->password) {
            return true;
        } else {
            return false;
        }
    }

    private function encrypPassword($password){
    	return     FUE($password);
    }
    
    public function login() {
        if($this->_identity === null) {
            $this->_identity = new UserIdentity($this->username, $this->password);
            $this->_identity->authenticate();
        }
        
        if($this->_identity->errorCode === UserIdentity::ERROR_NONE) {
            Yii::app()->user->login($this->_identity, $duration);
            return true;
        } else {
            return false;
        }
    }

    public static function updateByIds($ids, $attributes){
        return     updateByIds(__CLASS__,$ids, $attributes);
    }

    public static function create($attributes){
        return     create(__CLASS__,$attributes);
    }

    public static function deleteByIds($ids){
        return     deleteByIds(__CLASS__,$ids);
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