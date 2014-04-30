window.oPage = oFinalPage.extend
    fRefresh : (bRefresh)->
        @_super()
        @__sortAble()
    fBind : ()->
        $('[id^=js_blogCategoryName_]').click ()->
            false
        
        $('[id^=js_blogIndex_]').click ()->
            id = this.id.replace('js_blogIndex_','')
            State.forward('Blog', 'Index', {blogCategoryId : id})
            false

        $('[id^=js_delete_]').click ()->
            id = this.id.replace('js_delete_','')
            fOneAjax('blogCategory', 'AjaxDelete', {id : id}, (o)->
                if(o.code==1)
                    State.back(0)
            , @);
            false

        $('[id^=js_recover_]').click ()->
            id = this.id.replace('js_recover_','')
            fOneAjax('blogCategory', 'AjaxRecover', {id : id}, (o)->
                if(o.code==1)
                    State.back(0)
            , @);
            false

        $('#blogCategoryForm').submit ()->
            data = $(@).serialize()
            fOneAjax('blogCategory', 'AjaxAdd', data, (o)->
                if(o.code==1)
                    State.back(0)
            , @);
            false

        @
    __fInitData : ()->
    __sortAble : ()->
        #保存常用选择器
        list = $("#blogCategoryList")
        #保存原来的排列顺序
        originOrder = []
        list.children("li").each(()->
            originOrder.push(@.id.replace('js_blogCategoryId_',''))
            $(@).attr("title", "你可以拖动进行排序")
        )
        originOrder = originOrder.join(',')

        #ajax更新
        fUpdate = (ids, itemorder)->
            originOrder = ids
            fOneAjax('BlogCategory','AjaxSort',{ids:ids},(o)->
                if o.code==1
                    console.log('ok')
            ,$('#blogCategoryList'))

        #调用ajax更新方法
        fSubmit = ()->
            order = [];
            list.children("li").each(()->
                order.push(@.id.replace('js_blogCategoryId_',''))
            )
            fUpdate(order.join(','))

        #执行排列操作
        list.sortable(
            opacity: 0.7
            update: ()->
                fSubmit()
        )


