window.oPage = oFinalPage.extend
    fRefresh : (bRefresh)->
        @_super()
    fBind : ()->
        $('[id^=js_edit_blog_]').click ()->
            id = this.id.replace('js_edit_blog_','')
            State.forward('Blog', 'Edit', {id : id})
            false
    __fInitData : ()->
        


