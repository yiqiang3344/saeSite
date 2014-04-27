window.oPage = oFinalPage.extend
    fRefresh : (bRefresh)->
        @_super()
    fBind : ()->
        $('#submit').click ()->
            data = {}
            $('.attr').each ()->
                $(@).removeClass('merror')
                data[@id] = $(@).val()

            fOneAjax('Site','AjaxLogin',data,(o)->
                if(o.code==1)
                    State.forward('Main','Index')
                else
                    $.each(o.errors, (k,v)->
                        $('#'+k).addClass('merror')
                    )
            ,@)
            false
    __fInitData : ()->
        


