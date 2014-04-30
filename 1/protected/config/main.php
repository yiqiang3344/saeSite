<?php
require_once(dirname(__FILE__).'/constant.cfg.php');
$import = require_once(dirname(__FILE__).'/import.cfg.php');
// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
    'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
    'name'=>'site1 of yiisae',
    'language' => 'dev',

    // preloading 'log' component
    // 'preload'=>array('log'),

    // autoloading model and component classes
    'import'=>$import,

    'modules'=>array(
        'admin',
    ),

    // application components
    'components'=>array(
        'user'=>array(
            // enable cookie-based authentication
            'allowAutoLogin'=>true,
        ),
        // uncomment the following to enable URLs in path-format
        'urlManager'=>array(
            'urlFormat'=>'path',
            'showScriptName' => false,
            'urlSuffix' => '.html',//后缀 
            /*
            'rules'=>array(
                '<controller:\w+>/<id:\d+>'=>'<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
                '<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
            ),
            */
        ),
        'cache'=>array(
            'class' => 'CDummyCache',
        ),
        'sessionCache'=>array(
            'class'=>'CMemCache',
            'keyPrefix' => 'SevenSession',
            'servers'=>array(
            ),
        ),

        // 'session'=>array(
        //     'class'=>'CCacheHttpSession',
        //     'cacheID'=>'sessionCache',
        //     'sessionName' => 'SID',
        //     'cookieMode' => 'only',
        //     'timeout' => 86400,
        // ),

        // uncomment the following to use a MySQL database
        'db'=>array(
            'class' => 'CDbConnection',
            'connectionString' => 'mysql:host=127.0.0.1;dbname=yiisae1',
            'emulatePrepare' => true,
            'username' => 'root',
            'password' => 'yjq',
            'charset' => 'utf8',
        ),

        'errorHandler'=>array(
            // use 'site/error' action to display errors
            'errorAction'=>'site/error',
        ),
        'log'=>array(
            'class'=>'CLogRouter',
            'routes'=>array(
                array(
                    'class'=>'CFileLogRoute',
                    'levels'=>'error, warning, trace',
                ),
                // uncomment the following to show log messages on web pages
                /*
                array(
                    'class'=>'CWebLogRoute',
                ),
                */
            ),
        ), 
    ),

    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    'params'=>array(
        // this is used in contact page
        'adminEmail'=>'webmaster@example.com',
    ),
);
//如果定义了常量，则默认为在SAE环境中
if(defined('SAE_TMP_PATH'))
{
    //SAE 不支持I/O
    $config['runtimePath'] = SAE_TMP_PATH;
    //配置为 SAEDbConnection 则不必考虑用户名密码 并自动读写分离
    $config['components']['db'] = array(
            'class'=>'SAEDbConnection',
            'charset' => 'utf8',
            // 'tablePrefix'=>'tbl_',
            'emulatePrepare' => true,
            //开启sql 记录
            'enableProfiling'=>true,
            'enableParamLogging'=>true,
            //cache
            'schemaCachingDuration'=>3600,
    );
    //SAE不支持I/O 使用storage 存储 assets。 如果在正式环境，请将发布到assets的css/js做合并，直接放到app目录下，storage的分钟限额为5000，app为200000
    //最新的SAE 不使用storage 而是在siteController中，导入了一个SAEAssetsAction，通过 site/assets?path=aaa.txt ，将文件内容输出到web端，来访问实际的 aaa.txt 文件， 
    $config['components']['assetManager'] = array('class' => 'SAEAssetManager','assetsAction'=>'site/assets');
    //如果没有必要，不用修改缓存配置。 SAE不支持本地文件的IO处理 已经提供了memcache
    $config['components']['cache'] = array(
            'class'=> 'SAEMemCache',
            'servers'=>array(
                array('host'=>'localhost', 'port'=>11211, 'weight'=>100),
            ),
        );

}
