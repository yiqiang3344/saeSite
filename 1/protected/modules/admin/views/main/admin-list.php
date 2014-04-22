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
        Pager($('#page'),'Main','AdminList',{},params);

        //搜索
        $('#search').click(function(){
            var search = $('#search_type').val()+':'+$('#search_val').val();
            State.forward('Main','AdminList',{search:search,p:params.page});
        });
        //排序
        $('[id^=order_]').click(function(){
            var val = this.id.replace('order_',''),
                sc = orders[val]=='ASC'?'DESC':'ASC',
                order_str = val+':'+sc;
            State.forward('Main','AdminList',{order:order_str,p:params.page});
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
                oneAjax('Main','AjaxEditOne',{type:'Admin',id:id,attr:attr,val:val},function(o){
                    if(o.code!=1){
                        alert(o.errors[attr][0]);
                    }
                },this);
            }
        });

        $('#add').click(function(){
            State.forward('Main','AdminEdit');
            return false;
        });

        $('#delete').click(function(){
            var ids = [];
            $('.js_admin').find('.js_cbox:checked').each(function(){
                ids.push(this.value);
            });
            ids = ids.join(',');
            oneAjax('Main','AjaxDelete',{type:'Admin',ids:ids},function(o){
                if(o.code==1){
                    State.back(0);
                }else{
                    alert('error');
                }
            },this);
            return false;
        });

    }
</script>
<script type="text/javascript">//php代码只能出现在这个脚本中
    var params = <?php echo json_encode($params);?>;
    var orders = <?php echo json_encode($orders);?>;

    //v
    print_page();
</script>