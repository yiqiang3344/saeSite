<script type="text/javascript" scr="<?php echo json_encode(getUrl('js/views/'.$this->getId().'/'.$this->getAction().'.js'));?>"></script>
<script type="text/javascript">//php代码只能出现在这个脚本中
    new oPage({
        oParams : <?php echo json_encode($params);?>,
        oTemplate : oTemplate,
        oPartials : {},
    }).fPrint();
</script>