<script type="text/javascript" src="<?php echo $this->url('ueditor/ueditor.config.js');?>"></script>
<script type="text/javascript" src="<?php echo $this->url('ueditor/ueditor.all.js');?>"></script>
<script type="text/javascript">//template
    var template = Hogan.compile(<?php echo json_encode($this->template);?>);
</script>
<script type="text/javascript">//static
    function print_page(){
        var html = '<div class="maincontent"></div>';
        document.write(html);

        content_refresh();
    }

    function content_refresh(){
        var html = template.render(params);
        $('.maincontent').html(html);

        var description = UE.getEditor('description');
        description.ready(function(){
            // this.setContent('test');
        });
        $('#submit').click(function(e){
            var fd = new FormData(document.getElementById("form"));
            fd.append('type','Company');
            fd.append('id',params.id);
            fd.append('logo',$('#logo').get(0).files[0]);
            fd.append('urlPhoto',$('#urlPhoto').get(0).files[0]);
            fd.append('description', description.getContent());
            oneAjax('Main','AjaxAdd',fd,function(o){
                if(o.code==1){
                    State.back(1);
                }else{
                    $.each(o.errors,function(k,v){
                        $('#'+k).addClass('merror');
                    });
                }
            },this,true);
            return false;
        });
    }
</script>
<script type="text/javascript">//php代码只能出现在这个脚本中
    var params = <?php echo json_encode($params);?>;

    //v
    print_page();
</script>