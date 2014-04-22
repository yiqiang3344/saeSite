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
            ,   BASEURI = <?php echo json_encode(Yii::app()->getBaseUrl() . "/");?>
            ,   LANG    = <?php echo json_encode(Yii::app()->language);?>
            ,   STIME   = <?php echo getTime();?>
            ,   CTIME   = new Date().getTime()
            ,   TEST    = <?php echo YII_DEBUG;?>
            ,   HEADER  = <?php echo $this->getHeaderData();?>
            ,   FOOTER  = <?php echo $this->getFooterData();?>
            ;
        </script>
        <?php if(Yii::app()->language=='dev'):?>
        <script type="text/javascript" src="<?php echo getUrl("js/tools.js");?>"></script>
        <script type="text/javascript" src="<?php echo getUrl("js/main.js");?>"></script>
        <script type="text/javascript" src="<?php echo getUrl('js/url.js');?>"></script>
        <?php else:?>
        <script type="text/javascript" src="<?php echo getUrl("js/all.js");?>"></script>
        <?php endif;?>
        <script type="text/javascript" src="<?php echo getUrl('js/helper.js');?>"></script>
        <script type="text/javascript" src="<?php echo getUrl('js/views/'.$this->getId().'.js');?>"></script>
    </head>
    <body>
        <div id="maindiv">
            <div class="header"></div>
            <?php echo $content;?>
            <div class="footer"></div>
        </div>
        <script type="text/javascript">
            $('.header').replaceWith(headerTemplate.render(HEADER.params,HEADER.partials));
            $('.footer').replaceWith(footerTemplate.render(FOOTER.params,FOOTER.partials));
            $('.js_logout').click(function() {
                oneAjax('Site', 'AjaxLogout', {}, function(obj) {
                    if (obj.code === 1) {
                        State.back(0);
                    }
                }, this);
                return false;
            });
        </script>
    </body>
</html>

