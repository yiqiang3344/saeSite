<?php

class SiteController extends Controller
{
    public function actionLogin(){
        #input
        #start
        $params = array();
        END:
        $bind = array(
            'params' => $params,
        );
        $this->render('login',$bind);
    }

    public function actionAjaxLogin(){
        #input
        $post = $_POST;
        #start
        $code = 1;
        $errors = '';

        $admin = new MAdmin('login');
        $admin->attributes = $post;
        if(!$admin->validate() || !$admin->login()){
            $code = 2;
            $errors = $admin->getErrors();
        }
        END:
        $bind = array(
            'code' => $code,
            'errors' => $errors,
        );
        $this->render($bind);
    }

    public function actionError(){
        echo '##error!';
    }
}