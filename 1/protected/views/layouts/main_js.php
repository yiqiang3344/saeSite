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
            ,   TEST    = <?php echo json_encode(YII_DEBUG);?>
            ,   SHOW_HEADER  = <?php echo json_encode($this->showHeader);?>
            ,   HEADER  = <?php echo json_encode($this->getHeaderData());?>
            ,   CATEGORY  = <?php echo json_encode($this->getCategoryData());?>
            ,   FOOTER  = <?php echo json_encode($this->getFooterData());?>
            ,   EMPTY_LAYOUT  =  false
            ,   SYN_HIGHLIGHT  =  <?php echo json_encode(count($this->highlightLangs) ? true : false);?>
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
        <?php if(count($this->highlightLangs)>0):?>
        <link type="text/css" rel="stylesheet" href="<?php echo getUrl('css/shCore.css');?>"></link>
        <link type="text/css" rel="stylesheet" href="<?php echo getUrl('css/shThemeDefault.css');?>"></link>
        <script type="text/javascript" src="<?php echo getUrl('js/highlighter/shCore.js');?>"></script>
        <script type="text/javascript" src="<?php echo getUrl('js/highlighter/shAutoloader.js');?>"></script>
        <?php foreach($this->highlightLangs as $v):?>
        <script type="text/javascript" src="<?php echo getUrl('js/highlighter/shBrush'.$v.'.js');?>"></script>
        <?php endforeach;?>
        <?php endif;?>
    </head>
    <body>
        <div id="maindiv">
            <div class="header"></div>
            <div class="category"></div>
            <div class="maincontent">
            <?php echo $content;?>
            </div>
            <div class="footer"></div>
        </div>
    </body>
</html>

