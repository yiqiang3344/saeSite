window.oPage = oFinalPage.extend
    fRefresh : (bRefresh)->
        @_super()
    fBind : ()->
        $('[id^=js_blog_]').click ()->
            id = this.id.replace('js_blog_','')
            State.forward('Blog', 'Blog', {id : id})
            false

        $('[id^=js_edit_blog_]').click ()->
            id = this.id.replace('js_edit_blog_','')
            State.forward('Blog', 'Edit', {id : id})
            false

        $('[id^=js_delete_blog_]').click ()->
            id = this.id.replace('js_delete_blog_','')
            fOneAjax('Blog', 'AjaxDelete', {id : id}, (o)->
                if(o.code==1)
                    State.back(0)
            , @);
            false

        $('[id^=js_recover_blog_]').click ()->
            id = this.id.replace('js_recover_blog_','')
            fOneAjax('Blog', 'AjaxRecover', {id : id}, (o)->
                if(o.code==1)
                    State.back(0)
            , @);
            false

    __fInitData : ()->
        


