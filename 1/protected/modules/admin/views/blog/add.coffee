window.oPage = oFinalPage.extend
    fRefresh : (bRefresh)->
        @_super()
    fBind : ()->
        oContent = UE.getEditor('blog_content')

        $('.js_push').click ()->
            oFormData = new FormData(document.getElementById("js_form_blog"))
            oFormData.append('content', oContent.getContent())

            fOneAjax('Blog','AjaxAdd',oFormData,(o)->
                if(o.code==1)
                    State.back(0)
                else
                    fShowErrors(o.erros)
            ,@,true);
            false
    __fInitData : ()->
        


