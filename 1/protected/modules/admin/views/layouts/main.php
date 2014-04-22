<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="format-detection" content="telephone=no" />
        <title><?php echo CHtml::encode($this->pageTitle);?></title>
        <link href="<?php echo $this->url("css/base.css");?>" rel="stylesheet" type="text/css" media="screen" />
        <link href="<?php echo $this->url("css/page.css");?>" rel="stylesheet" type="text/css" media="screen" />
        <script type="text/javascript" src="<?php echo $this->url("js/jquery.js");?>"></script>
        <script type="text/javascript">
            var BASEURL=<?php echo json_encode(Yii::app()->getBaseUrl());?>;
            var BASEURI=<?php echo json_encode(Yii::app()->getBaseUrl() . "/index.php/".$this->module->getName());?>;
            var STIME=<?php echo     getTime();?>;
            var CTIME=new Date().getTime();
            var TEST_SERVER_FLAG=<?php echo YII_DEBUG;?>;
            var UD=<?php echo json_encode($this->getUd());?>;
            window.UEDITOR_HOME_URL=<?php echo json_encode($this->getAssetsUrl().'/ueditor/');?>;
        </script>
        <script type="text/javascript" src="<?php echo $this->url('js/tool.js');?>"></script>
        <script type="text/javascript" src="<?php echo $this->url('js/url.js');?>"></script>
        <script type="text/javascript" src="<?php echo $this->url('js/main.js');?>"></script>
        <script type="text/javascript">
            //设置子模板编译方法，dev中才有定义
            <?php foreach(array_merge($this->partialsSubTemplate,$this->publicSubTemplate) as $k=>$v):?>
            var <?php echo $k;?> = Hogan.compile(<?php echo json_encode($v);?>);
            <?php endforeach;?>
        </script>
    </head>
    <body>
        <div id="maindiv" class="maindiv">
            <?php echo $content;?>
        </div>
        <script type="text/javascript">
            show_head(<?php echo json_encode($this->title);?>);
            function show_head(t){
                var params = {list:[
                    {
                        select:t=='Index'?1:0,
                        url:getUrl('Main','index'),
                        name:'logo管理'
                    },
                    {
                        select:t=='Link'?1:0,
                        url:getUrl('Main','LinkList'),
                        name:'导航链接'
                    },
                    {
                        select:t=='Contact'?1:0,
                        url:getUrl('Main','ContactList'),
                        name:'联系我们'
                    },
                    {
                        select:t=='Company'?1:0,
                        url:getUrl('Main','CompanyList'),
                        name:'公司'
                    },
                    {
                        select:t=='Comment'?1:0,
                        url:getUrl('Main','CommentList'),
                        name:'评论'
                    },
                    {
                        select:t=='Activity'?1:0,
                        url:getUrl('Main','ActivityList'),
                        name:'活动'
                    },
                    {
                        select:t=='Information'?1:0,
                        url:getUrl('Main','InformationList'),
                        name:'资讯'
                    },
                    {
                        select:t=='User'?1:0,
                        url:getUrl('Main','UserList'),
                        name:'用户管理'
                    }
                ]};
                if(UD){
                    params.list = params.list.concat([
                        {
                            select:t=='Admin'?1:0,
                            url:getUrl('Main','AdminList'),
                            name:'管理员管理'
                        },
                        {
                            select:t=='Backup'?1:0,
                            url:getUrl('Main','Backup'),
                            name:'备份还原'
                        }
                    ]);
                }
                params.list.push(
                    {
                        select:0,
                        url:getUrl('Main','Logout'),
                        name:'退出'
                    }
                );
                $('#maindiv').prepend(pHeader.render(params));
            }
        </script>
    </body>
</html>

