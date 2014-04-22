<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController {
    /**
     *
     * @var string the default layout for the controller view. Defaults to
     *      '//layouts/column1',
     *      meaning using a single column layout. See
     *      'protected/views/layouts/column1.php'.
     */
    public $layout = 'main';
    /**
     *
     * @var array context menu items. This property will be assigned to {@link
     *      CMenu::items}.
     */
    public $menu = array ();
    /**
     *
     * @var array the breadcrumbs of the current page. The value of this
     *      property will
     *      be assigned to {@link CBreadcrumbs::links}. Please refer to {@link
     *      CBreadcrumbs::links}
     *      for more details on how to specify this property.
     */
    public $breadcrumbs = array ();

    //页面标识
    public $title = '';

    public function init(){
    }

    public function filters() {
        return array (
            'checkLogin',
        );
    }

    public function filterCheckLogin($filterChain) {
        if(!in_array($this->getId(),array('site')) && Yii::app()->user->isGuest){
            $this->redirect($this->url('Site','Login'));
            return false;
        }
        $filterChain->run();
    }

    public function CheckSuperAdmin($goto=true) {
        $Admin = $this->getUd();
        if(!$Admin || $Admin['super']!=1){
            $goto && $this->redirect($this->url('Main','Index'));
            return false;
        }
        return true;
    }

    public function getUD() {
        $u = Yii::app()->user->isGuest?array():MAdmin::model()->UD()->findByAttributes(array('username'=>Yii::app()->user->name));
        return     modelsToArray($u);
    }

    private $_basePath;
    public function getPath(){
        if(isset($this->_basePath)){
            return $this->_basePath;
        }
        $this->_basePath = $this->module->getBasePath();
        return $this->_basePath;
    }

    private $_assetsUrl;
    public function getAssetsUrl(){
        if(isset($this->_assetsUrl)){
            return $this->_assetsUrl;
        }
        $this->_assetsUrl = $this->module->getAssetsUrl();
        return $this->_assetsUrl;
    }

    /**
     * Display view or return Ajax data
     *
     * @param array $data
     * @param string $view Template name
     * @return void
     */
    public $template;
    public $publicSubTemplate = array();//公用子模板
    public $partialsSubTemplate = array();//局部子模板
    public function render( $view='',$data=array(), $template_flag=S::DEV_USE_TEMPLATE,$usePartials=true,$usePublic=true){
        if(is_array($view)) {
            $output =  json_encode($view);
        }else {
            //DEV_USE_TEMPLATE 表示开发时使用传入的模板，发布后使用已编译的js文件; USE_TEMPLATE 表示绝对会传入模板，用于php渲染; NOT_USE_TEMPLATE和其他 表示不用模板
            if($template_flag==S::USE_TEMPLATE){
                $this->template = $this->renderFile($this->getPath().'/template/'.$this->getId().'/'.$view.'.php',null,true);
                //读取子模板
                if($usePartials){
                    $this->partialsSubTemplate = $this->getSubTemplateMap($this->getPath().'/template/'.$this->getId());
                }
                if($usePublic){
                    $this->publicSubTemplate = $this->getSubTemplateMap($this->getPath().'/template/public_sub_template');
                }
            }elseif($template_flag==S::DEV_USE_TEMPLATE){
                $this->template = $this->renderFile($this->getPath().'/template/'.$this->getId().'/'.$view.'.php',null,true);
                //读取子模板 非dev时，子模板已被编译为js方法， 局部子模板的js文件会在layouts的模板view中加载
                if($usePartials){
                    $this->partialsSubTemplate = $this->getSubTemplateMap($this->getPath().'/template/'.$this->getId());
                }
                if($usePublic){
                    $this->publicSubTemplate = $this->getSubTemplateMap($this->getPath().'/template/public_sub_template');
                }
            }
            $output =  parent::render($view,$data,true);
        }
        echo $output;
    }

    private function getSubTemplateMap($dir){
        $a = array();
        foreach(scandir($dir) as $file){
            if(is_file($dir.'/'.$file) && strpos($file,'_')===0){
                $name = '';
                foreach(explode('_',basename($file,'.php')) as $v){
                    $name .= ucfirst($v);
                }
                $name = lcfirst($name);
                $a[$name] = $this->renderFile($dir.'/'.$file,null,true);
            }
        }
        return $a;
    }

    public function getBaseUrl(){
        return Yii::app()->getBaseUrl().'/index.php/'.$this->module->getName();
    }

    public function url($c,$a=null,$p=array()){
        if($a){
            $ret = $this->getBaseUrl().'/'.$c.'/'.$a.($p?'?':'');
            foreach($p as $k=>$v){
                $ret .= urlencode ( $k ) . "=" . urlencode ( $v ) . "&";
            }
        }else{
            if(preg_match('{^(img|images|upload|upload1)}',$c)){
                $file = Yii::app()->getBasePath().'/../'.$c;
                $md5 = @md5_file ($file);
                $file = Yii::app()->getBaseUrl().'/'.$c;
            }else{
                $file = $this->getPath().'/'.$c;
                $md5 = @md5_file ($file);
                $file = $this->getAssetsUrl().'/'.$c;
            }
            $ret = $file.($md5 ? '?v=' . substr ( $md5, 0, 8 ) : '');
        }
        return $ret;
    }

    

    public function siteUrl($c,$a=null,$p=array()){
        return     getUrl($c,$a,$p);
    }
}
