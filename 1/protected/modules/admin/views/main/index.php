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

        $('#upload_logo').click(function(){
            var u = new uploader(document.getElementById('logo_file'),{
                url : getUrl('Main','AjaxUploadImage'),
                success : function(o){
                    if(o.code==1){
                        State.back(0);
                    }else{
                        alert(o.error);
                    }
                }
            });
            u.send();
            return false;
        });
    }
</script>
<script type="text/javascript">//php代码只能出现在这个脚本中
    var params = <?php echo json_encode($params);?>;

    //v
    print_page();
</script>