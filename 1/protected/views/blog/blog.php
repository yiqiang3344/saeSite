<script type="text/javascript" src="<?php echo getUrl('js/views/'.$this->getId().'/'.$this->getAction()->id.'.js');?>"></script>
<script type="text/javascript">//php代码只能出现在这个脚本中
    new oPage({
        oParams : <?php echo json_encode($params);?>,
        oTemplate : oTemplate,
        oPartials : {},
    }).fPrint();
</script>