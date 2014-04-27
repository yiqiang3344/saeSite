<?php
class SiteController extends Controller
{
    public function actionError(){
        echo 'error!';
    }

    public function actionLogin(){
        #input

        #start
        $params = array();
        if(intval(Yii::app()->session['login_error_time'])>=S::MAX_LOGIN_ERROR_TIME){
            $params['verifyUrl'] = $this->url($this->getId(),'captcha').'/v/'.mt_rand();
        }
        END:
        $bind = array(
            'params' => $params,
        );
        $this->renderJs('login', $bind);
    }

    public function actionRegister(){
        #input

        #start
        $params = array();
        if(intval(Yii::app()->session['login_error_time'])>=S::MAX_LOGIN_ERROR_TIME){
            $params['verifyUrl'] = $this->url($this->getId(),'captcha').'/v/'.mt_rand();
        }
        END:
        $bind = array(
            'params' => $params,
        );
        $this->renderJs('register', $bind);
    }

    /******************************ajax**********************/
    public function actionAjaxLogin(){
        #input
        $FUser = $_POST;
        #start
        $MUser = new MUser('login');
        $MUser->attributes = $FUser;
        $errors = array();
        $session = Yii::app()->session;
        if($MUser->validate() && $MUser->login()){
            $code = 1;
            $session['login_error_time']=0;
        }else{
            $code = 2;
            if(intval(@$session['login_error_time']))
                $session['login_error_time'] += 1;
            else
                $session['login_error_time']=1;
            $errors = $MUser->getErrors();
        }

        $bind = array(
            'code' => $code,
            'errors' => $errors,
        );
        END:
        $this->renderJs($bind);
    }

    public function actionAjaxRegister(){
        #input
        $FUser = $_POST;
        #start
        $MUser = new MUser('register');
        $MUser->ip =     getIp();
        $MUser->attributes = $FUser;
        $errors = array();
        $session = Yii::app()->session;
        if($MUser->save()){
            $MUser = new MUser('login');
            $MUser->attributes = $FUser;
            $MUser->login();
            $code = 1;
            $session['login_error_time']=0;
        }else{
            $code = 2;
            if(intval(@$session['login_error_time']))
                $session['login_error_time']+=1;
            else
                $session['login_error_time']=1;
            $errors = $MUser->getErrors();
        }
        END:
        $bind = array(
            'code' => $code,
            'errors' => $errors,
        );
        $this->renderJs($bind);
    }

    public function actionAjaxLogout(){
        #input

        #start
        Yii::app()->user->logout();
        $code = 1;
        END:
        $bind = array(
            'code' => $code,
        );
        $this->renderJs($bind);
    }
    
}
