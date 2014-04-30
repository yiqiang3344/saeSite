<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="format-detection" content="telephone=no" />
        <title><?php echo CHtml::encode($this->pageTitle);?></title>
        <link href="<?php echo getUrl("css/base.css");?>" rel="stylesheet" type="text/css" media="screen" />
        <link href="<?php echo getUrl("css/page.css");?>" rel="stylesheet" type="text/css" media="screen" />
        <script type="text/javascript" src="<?php echo getUrl("js/jquery.js");?>"></script>
        <script type="text/javascript">
            var 
                BASEURL = <?php echo json_encode(Yii::app()->getBaseUrl());?>
            ,   BASEURI = <?php echo json_encode(Yii::app()->getBaseUrl());?>
            ,   LANG    = <?php echo json_encode(Yii::app()->language);?>
            ,   STIME   = <?php echo getTime();?>
            ,   CTIME   = new Date().getTime()
            ,   TEST    = <?php echo YII_DEBUG;?>
            ;
        </script>
        <?php if(Yii::app()->language=='dev'):?>
        <script type="text/javascript" src="<?php echo getUrl("js/tools.js");?>"></script>
        <script type="text/javascript" src="<?php echo getUrl("js/main.js");?>"></script>
        <script type="text/javascript" src="<?php echo getUrl('js/url.js');?>"></script>
        <?php else:?>
        <script type="text/javascript" src="<?php echo getUrl("js/all.js");?>"></script>
        <?php endif;?>
        <?php if($this->templateFlag==S::DEV_USE_TEMPLATE):?>
            <script type="text/javascript">
                //设置子模板编译方法，dev中才有定义
                <?php if(Yii::app()->language=='dev'):?>
                    <?php foreach(array_merge($this->partialsSubTemplate,$this->publicSubTemplate) as $k=>$v):?>
                    var <?php echo $k;?> = Hogan.compile(<?php echo json_encode($v);?>);
                    <?php endforeach;?>
                <?php endif;?>
            </script>
        <?php endif;?>
        <script type="text/javascript" src="<?php echo getUrl('js/helper.js');?>"></script>
        <?php if($this->templateFlag==S::DEV_USE_TEMPLATE):?>
            <!-- 倒入含有局部子模板编译方法的js文件 -->
            <?php if(Yii::app()->language!='dev' && $this->partialsSubTemplate):?>
            <script type="text/javascript" src="<?php echo getUrl('js/views/'.$this->getId().'.js');?>"></script>
            <?php endif;?>
        <?php endif;?>
    </head>
    <body>
        <div id="maindiv">
            <?php echo $this->Mustache->render($this->publicSubTemplate['headerTemplate'],$this->getHeaderData('params')); ?>
            <div class="maincontent">
            <?php echo $content;?>
            </div>
            <?php echo $this->Mustache->render($this->publicSubTemplate['footerTemplate'],$this->getFooterData('params')); ?>
        </div>
        <script type="text/javascript">
        $('.js_logout').click(function() {
            fOneAjax('Site', 'AjaxLogout', {}, function(obj) {
                if (obj.code === 1) {
                    State.back(0);
                }
            }, this);
            return false;
        });
        </script>
    </body>
</html>

