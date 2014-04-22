<?php
class MUser extends YActiveRecord
{
	//注册
    public $passwordConfirm;
    public $verifyCode;
    //登录
    public $remember;
    
    private $_identity;
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
        return 'user';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // 注册或者登陆错误3次则需要验证码
        $session = Yii::app()->session;
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('username, password', 'required'),
            array('verifyCode', 'captcha', 'allowEmpty'=>intval(@$session['login_error_time'])<S::MAX_LOGIN_ERROR_TIME,'on'=>'login,register'),
            // array('verifyCode', 'activeCaptcha', 'on'=>'register,login'), // set "active" scenario when ajax validation is being performed.
            array('passwordConfirm', 'required', 'on' => 'register'),
            array('passwordConfirm', 'compare', 'compareAttribute' => 'password', 'on' => 'register'),
            array('ip', 'unique', 'on' => 'register'),
            array('username', 'unique', 'on' => 'register'),
            array('password', 'length', 'min' => 4, 'max' => 15, 'on' => 'register'),
            array('password', 'authenticate', 'on' => 'login'),
            array('remember', 'boolean', 'on' => 'login'),
        );
    }

    public function activeCaptcha()
    {
        $code = Yii::app()->controller->createAction('captcha')->verifyCode;
        if ($code != $this->verifyCode)
            $this->addError('verifyCode', Yii::t('model','wrong verify code'));
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
        // echo $password.'##'.$this->encrypPassword($password).'##'.$this->password;
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
            $duration = $this->remember ? 3600 * 24 * 30 : 0; // 30天
            Yii::app()->user->login($this->_identity, $duration);
            return true;
        } else {
            return false;
        }
    }

    public static function updateByIds($ids, $attributes){
        return     updateByIds(__CLASS__,$ids, $attributes);
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