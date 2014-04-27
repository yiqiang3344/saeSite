<?php
class MAdmin extends YActiveRecord
{
    static protected $table = "admin";

    private $_identity;
    public $passwordConfirm;

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
            'brief'=>array(
                'select'=>'id,username,super',
            ),
        );
    }

    protected function beforeSave() {
        if($this->isNewRecord) {
            $this->password = $this->encrypPassword();
            $this->recordTime = getTime();
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

    private function encrypPassword($password=null){
        if($password===null)
            $password = $this->password;
    	return FUE($password);
    }
    
    public function login() {
        if($this->_identity === null) {
            $this->_identity = new UserIdentity($this->username, $this->password);
            $this->_identity->authenticate();
        }
        if($this->_identity->errorCode === UserIdentity::ERROR_NONE) {
            $duration = 3600 * 24 * 7; // 30天
            Yii::app()->user->login($this->_identity, $duration);
            return true;
        } else {
            return false;
        }
    }
}