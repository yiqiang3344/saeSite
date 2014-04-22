<?php

class AdminModule extends CWebModule
{
    public function init()
    {
        // this method is called when the module is being created
        // you may place code here to customize the module or the application
        $this->layoutPath = Yii::getPathOfAlias('application.modules.admin.views.layouts');
        // $this->layout = 'main';

        // import the module-level models and components
        $this->setImport(array(
            'admin.models.*',
            'admin.components.*',
        ));

        Yii::app()->setComponents(array(
            'errorHandler' => array(
                'errorAction' => 'admin/site/error',
            ),
        ));
    }

    private $_assetsUrl;
    public function getAssetsUrl()
    {
        if ($this->_assetsUrl === null){
            $this->_assetsUrl = Yii::app()->getAssetManager()->publish(
                Yii::getPathOfAlias('application.modules.admin.script') );
        }
        return $this->_assetsUrl;
    }

    public function beforeControllerAction($controller, $action)
    {
        if(parent::beforeControllerAction($controller, $action))
        {
            // this method is called before any module controller action is performed
            // you may place customized code here
            
            Yii::app()->homeUrl = array('/admin/main');

            if($controller->uniqueId != 'admin/site'){
                if(Yii::app()->user->isGuest){
                    $controller->redirect($controller->createUrl('site/login'));
                    return false;
                }
            }
            return true;
        }else{
            return false;
        }
    }
}
