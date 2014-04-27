window.oPage = oFinalPage.extend
    fRefresh : (bRefresh)->
        @_super()
    fBind : ()->
        me = @
        oContent = UE.getEditor('blog_content')
        oContent.ready ()->
            this.setContent(me.oArgs.oParams.blog.content)

        $('.js_push').click ()->
            oFormData = new FormData(document.getElementById("js_form_blog"))
            oFormData.append('id', me.oArgs.oParams.blog.blogId)
            oFormData.append('content', oContent.getContent())

            fOneAjax('Blog','AjaxEdit',oFormData,(o)->
                if(o.code==1)
                    State.back(0)
                else
                    fShowErrors(o.erros)
            ,@,true);
            false
    __fInitData : ()->
        


