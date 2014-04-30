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
            var 
                BASEURL = <?php echo json_encode(Yii::app()->getBaseUrl());?>
            ,   BASEURI = <?php echo json_encode(Yii::app()->getBaseUrl() . "/index.php/".$this->module->getName());?>
            ,   STIME   = <?php echo getTime();?>
            ,   CTIME   = new Date().getTime()
            ,   TEST    = <?php echo YII_DEBUG;?>
            ,   SHOW_HEADER  = <?php echo json_encode($this->showHeader);?>
            ,   HEADER  =  <?php echo json_encode($this->getHeaderData());?>
            ,   EMPTY_LAYOUT  =  false
            ,   SYN_HIGHLIGHT  =  <?php echo json_encode(count($this->highlightLangs) ? array('swf'=>$this->url('js/highlighter/clipboard.swf')) : false);?>
            ;
            window.UEDITOR_HOME_URL=<?php echo json_encode($this->getAssetsUrl().'/ueditor/');?>;
        </script>
        <script type="text/javascript" src="<?php echo $this->url('js/tool.js');?>"></script>
        <script type="text/javascript" src="<?php echo $this->url('js/url.js');?>"></script>
        <script type="text/javascript" src="<?php echo $this->url('js/main.js');?>"></script>
        <script type="text/javascript" src="<?php echo $this->url('js/views/'.$this->getId().'.js');?>"></script>
    </head>
    <body>
        <div id="maindiv" class="maindiv">
            <div class="header"></div>
            <div class="maincontent">
            <?php echo $content;?>
            </div>
            <div class="footer"></div>
        </div>
    </body>
</html>

