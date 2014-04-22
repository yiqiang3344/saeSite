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

        //分页
        Pager($('#page'),'Main','Backup',{},params);

        //搜索
        $('#search').click(function(){
            var search = $('#search_type').val()+':'+$('#search_val').val();
            State.forward('Main','Backup',{search:search,p:params.page});
        });
        //排序
        $('[id^=order_]').click(function(){
            var val = this.id.replace('order_',''),
                sc = orders[val]=='ASC'?'DESC':'ASC',
                order_str = val+':'+sc;
            State.forward('Main','Backup',{order:order_str,p:params.page});
        });
        //点击修改
        $('[id^=attr_]').attr('ContentEditable',true).focus(function(){
            $(this).data('val',$(this).html());
        }).blur(function(){
            var val = $(this).html(),
                id = $(this).parents('li').attr('id').replace('info_',''),
                attr = this.id.replace('attr_',''),
                me = this;
            if(val !== $(this).data('val')){
                oneAjax('Main','AjaxEditOne',{type:'Backup',id:id,attr:attr,val:val},function(o){
                    if(o.code!=1){
                        alert(o.errors[attr][0]);
                    }
                },this);
            }
        });

        $('#delete').click(function(){
            var ids = [];
            $('.js_backup').find('.js_cbox:checked').each(function(){
                ids.push(this.value);
            });
            ids = ids.join(',');
            oneAjax('Main','AjaxDelete',{type:'Backup',ids:ids},function(o){
                if(o.code==1){
                    State.back(0);
                }else{
                    alert('error');
                }
            },this);
            return false;
        });

        //备份
        $('#backup').click(function(){
            var data = {type:'Backup',name:$('#name').val() || params.now};
            oneAjax('Main','AjaxAdd',data,function(o){
                if(o.code==1){
                    State.back(0);
                }else{
                    $.each(o.errors,function(k,v){
                        $('#'+k).addClass('merror');
                    });
                }
            },this);
            return false;
        });

        //还原
        $('[id^=reback_]').click(function(){
            var data = {id:this.id.replace('reback_','')};
            oneAjax('Main','AjaxReback',data,function(o){
                if(o.code==1){
                    State.back(0);
                }else{
                    alert(o.errors);
                }
            },this);
            return false;
        });

    }
</script>
<script type="text/javascript">//php代码只能出现在这个脚本中
    var params = <?php echo json_encode($params);?>;
    $.each(params.data,function(k,v){
        v.createTime = dateFormat(v.createTime,2);
        v.lastRebackTime = v.lastRebackTime!='0' ? dateFormat(v.lastRebackTime,2) : 0;
    });
    //v
    print_page();
</script>