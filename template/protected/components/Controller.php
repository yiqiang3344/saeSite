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
    public $layout = '//layouts/main_php';
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

    public function init() {
    }

    public function filters() {
        return array (
        );
    }

    /**
     * render view or return Ajax data
     *
     * @param array $data
     * @param string $view Template name
     * @return void
     */
    public $Mustache;
    public $template;
    public $publicSubTemplate = array();//公用子模板
    public $partialsSubTemplate = array();//局部子模板
    public $templateFlag = false;//局部子模板
    public function render( $view='',$data=array(), $templateFlag=S::USE_TEMPLATE, $usePartials=true, $usePublic=true){
        if(is_array($view)) {
            $output =  json_encode($view);
        }else {
            $this->templateFlag = $templateFlag;
            //DEV_USE_TEMPLATE 表示开发时使用传入的模板，发布后使用已编译的js文件; USE_TEMPLATE 表示绝对会传入模板，用于php渲染; NOT_USE_TEMPLATE和其他 表示不用模板
            if($templateFlag==S::USE_TEMPLATE){
                $this->Mustache = new Mustache_Engine();
                $this->template = $this->renderFile($this->getBasePath().'/views/'.$this->getId().'/'.(Yii::app()->language=='dev' ? '' : Yii::app()->language.'/').$view.'.mustache',null,true);
                //读取子模板
                if($usePartials){
                    $this->partialsSubTemplate = $this->getSubTemplateMap($this->getBasePath().'/views/'.$this->getId().(Yii::app()->language=='dev' ? '' : Yii::app()->language.'/'));
                }
                if($usePublic){
                    $this->publicSubTemplate = $this->getSubTemplateMap($this->getBasePath().'/views/_public'.(Yii::app()->language=='dev' ? '' : Yii::app()->language.'/'),false);
                }
            }elseif($templateFlag==S::DEV_USE_TEMPLATE){
                if(Yii::app()->language=='dev'){
                    $this->template = $this->renderFile($this->getBasePath().'/views/'.$this->getId().'/'.$view.'.mustache',null,true);
                    //读取子模板 非dev时，子模板已被编译为js方法， 局部子模板的js文件会在layouts的模板view中加载
                    if($usePartials){
                        $this->partialsSubTemplate = $this->getSubTemplateMap($this->getBasePath().'/views/'.$this->getId());
                    }
                    if($usePublic){
                        $this->publicSubTemplate = $this->getSubTemplateMap($this->getBasePath().'/views/_public',false);
                    }
                }else{
                    $this->partialsSubTemplate = $usePartials;
                    $this->publicSubTemplate = $usePublic;
                }
            }
            $output =  parent::render($view,$data,true);
        }
        echo $output;
    }
    private function getSubTemplateMap($dir,$withFlag=true){
        $a = array();
        foreach(scandir($dir) as $file){
            $name = $withFlag ? substr(basename($file,'.mustache'), 1) : basename($file,'.mustache');
            $a[$name] = $this->renderFile($dir.'/'.$file,null,true);
        }
        return $a;
    }

    /**
     * render view of js or return Ajax data
     *
     * @param array $data
     * @param string $view Template name
     * @return void
     */
    public function renderJs( $view='',$data=array(), $layout='//layouts/main_js'){
        $this->layout = $layout;
        if(is_array($view)) {
            $output =  json_encode($view);
        }else {
            $this->setHigthligthLangs();
            $output =  parent::render($view,$data,true);
        }
        echo $output;
    }

    public function actions(){
        return array( 
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha'=>array(
                'class'=>'CCaptchaAction',
                'backColor'=>0xFFFFFF, 
                'maxLength'=>'6',       // 最多生成几个字符
                'minLength'=>'5',       // 最少生成几个字符
                'height'=>'40',
                'width'=>'230',
            ), 
            // 'register'=>array(
            //     'class'=>'RegisterAction',
            // ),
            // 'login'=>array(
            //     'class'=>'LoginAction',
            // ),
            // 'logout'=>array(
            //     'class'=>'LogoutAction',
            // ),
        ); 
        
    }

    public function accessRules(){
        return array(
            array('allow',
                'actions'=>array(
                    'captcha',
                    // 'register',
                    // 'login',
                    // 'logout',
                ),
                'users'=>array('*'),
            ),
        );
    }

    public function checkUser(){
        return $this->getUser()?true:false;
    }

    public function getUser(){
        return MUser::model()->find('username=:username',array(':username'=>Yii::app()->user->getId()));
    }

    public function getUserData(){
        $info = array();
        $info['login_error_time'] = intval(Yii::app()->session['login_error_time']);
        $info['max_login_error_time'] = S::MAX_LOGIN_ERROR_TIME;
        $user = $this->getUser();
        $info['user'] = $user?    cp($user,array('id','username')):false;
        return $info;
    }

    public function getHeaderData($key=false){
        $ret = array(
            'params' => array(
                'logo'=>getUrl('images/logo.jpg'),
            ),
            'partials' => array(),
        );
        return $key && isset($ret[$key]) ? $ret[$key] : $ret;
    }

    public function getCategoryData($key=false){
        $nowBlogCategoryId = getInput('blogCategoryId','int',array('default'=>0));
        $list = array();
        foreach(modelsToArray(MBlogCategory::model()->with('blogCount')->findAllByAttributes(array('deleteFlag'=>0),array('order'=>'sort asc,recordTime desc'))) as $row){
            $row['url'] = getUrl('Blog','Index',array('blogCategoryId'=>$row['blogCategoryId']));
            $row['on'] = $nowBlogCategoryId==$row['blogCategoryId'];
            $list[] = $row;
        }
        $ret = array(
            'params' => array(
                'aList'=>$list
            ),
            'partials' => array(),
        );
        return $key && isset($ret[$key]) ? $ret[$key] : $ret;
    }

    public function getFooterData($key=false){
        $ret = array(
            'params' => array(),
            'partials' => array(),
        );
        return $key && isset($ret[$key]) ? $ret[$key] : $ret;
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
                'js' => 'JScript',
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

    public function getId(){
        return lcfirst(parent::getId());
    }

    public function getActionId(){
        return lcfirst($this->getAction()->id);
    }
}
