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

    public $showHeader = true;
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
    public $pageName;

    public function init(){
        
    }

    public function filters() {
        return array (
            'checkLogin',
        );
    }

    public function filterCheckLogin($filterChain) {
        if(!in_array($this->getId(),array('site')) && Yii::app()->admin->isGuest){
            $this->redirect($this->url('Site','Login'));
            return false;
        }
        $filterChain->run();
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
     * render view or return Ajax data
     *
     * @param array $data
     * @param string $view Template name
     * @return void
     */
    public function render($view, $data=array()){
        $this->pageName = $this->getId().'_'.$this->getAction()->id;
        
        if(is_array($view)) {
            $view['errors'] = isset($view['errors']) ? $view['errors'] : array();
            $output =  json_encode($view);
        }else {
            $this->setHigthligthLangs();
            $output =  parent::render($view,$data,true);
        }
        echo $output;
    }

    public $higthlightContent;
    public $highlightLangs = array();
    private function setHigthligthLangs(){
        if($this->higthlightContent){
            $langMap = array(
                'php' => 'Php',
                'cpp' => 'Cpp',
                'css' => 'Css',
                'c#' => 'CSharp',
                'delphi' => 'Delphi',
                'java' => 'Java',
                'js' => 'jScript',
                'python' => 'Python',
                'ruby' => 'Ruby',
                'sql' => 'Sql',
                'vb' => 'Vb',
                'xml' => 'Xml',
                'as3' => 'AS3',
                'bash' => 'Bash',
                'delphi' => 'Delphi',
                'diff' => 'Diff',
                'erlang' => 'Erlang',
                'groovy' => 'Groovy',
                'html' => 'Xml',
                'jfx' => 'JavaFX',
                'pl' => 'Perl',
            );
            preg_match_all('/<pre\s+class="brush:([^;].*?);/', $this->higthlightContent, $matchs);
            foreach(array_unique($matchs[1]) as $v){
                if(isset($langMap[$v])){
                    $this->highlightLangs[] = $langMap[$v];
                }
            }
        }
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
        return getUrl($c,$a,$p);
    }

    public function getAdmin() {
        return Yii::app()->admin->isGuest ? null : MAdmin::model()->brief()->findByAttributes(array('username'=>Yii::app()->admin->name));
    }

    public function CheckSuperAdmin($goto=true) {
        $admin = $this->getAdmin();
        if(!$admin || $admin->super != 1){
            $goto && $this->redirect($this->url('Main','Index'));
            return false;
        }
        return true;
    }

    public function getHeaderData(){
        $admin = $this->getAdmin();
        $ret = array(
            'aNav' => array(
                array(
                    'isSelected' => $this->pageName=='blog_add',
                    'url' => $this->url('Blog','Add'),
                    'name' => '写博客',
                ),
                array(
                    'isSelected' => $this->pageName=='blog_Index',
                    'url' => $this->url('Blog','Index'),
                    'name' => '博客列表',
                ),
            ),
            'oAdmin' => $admin ? $admin->toArray() : null,
        );
        if($admin && $admin->super = 1){
            // $ret['aNav'][] = array(
            //     'isSelected' => $this->pageName=='backup_index',
            //     'url' => $this->url('Backup','Index'),
            //     'name' => '备份还原',
            // );
        }
        $ret['aNav'][] = array(
            'isSelected' => false,
            'url' => '##',
            'cls' => 'js_logOut',
            'name' => '退出',
        );
        return $ret;
    }
}
