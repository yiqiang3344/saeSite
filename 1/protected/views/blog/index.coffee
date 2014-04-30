window.oPage = oFinalPage.extend
    fRefresh : (bRefresh)->
        @_super()
    fBind : ()->
        $('[id^=js_blog_]').click ()->
            id = this.id.replace('js_blog_','')
            State.forward('Blog', 'Blog', {id : id})
            false
    __fInitData : ()->
        

