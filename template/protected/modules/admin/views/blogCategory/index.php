<link rel="stylesheet" href="<?php echo $this->url('css/jquery_ui.css');?>">
<script type="text/javascript" src="<?php echo $this->url('js/jquery_ui.js');?>"></script>
<script type="text/javascript" src="<?php echo $this->url('js/views/'.$this->getId().'/'.$this->getActionId().'.js');?>"></script>
<script type="text/javascript">//php代码只能出现在这个脚本中
    new oPage({
        oParams : <?php echo json_encode($params);?>,
        oTemplate : oTemplate,
        oPartials : {},
    }).fPrint();
</script>