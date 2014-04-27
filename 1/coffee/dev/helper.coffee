window.oFinalPage = Class.extend
    init : (oArgs)->
        @oArgs = oArgs
        @__fInitData()
    __fInitData : ()->

    fPrint : ()->
        document.write('<div class="maincontent"></div>')
        @fRefresh()
    fRefresh : (bRefresh)->
        bRefresh = bRefresh ? false;
        $('.maincontent').html(@oArgs.oTemplate.render(@oArgs.oParams, @oArgs.oPartials))
        @fBind()
    fBind : ()->
        $('.js_back').click ()->
            State.back(1)
        

