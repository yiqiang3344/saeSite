$(()->
    if(SYN_HIGHLIGHT)
        SyntaxHighlighter.all()

    SHOW_HEADER && fShowHeader()

    fShowFooter()

    fShowCategory()

    $('.js_logout').click(()->
        fOneAjax('Site', 'AjaxLogout', {}, (o)->
            if o.code == 1
                State.back(0);
        , @)
        false
    )
)

window.fShowHeader = ()->
    $('.header').replaceWith(headerTemplate.render(HEADER.params,HEADER.partials))

window.fShowCategory = ()->
    $('.category').replaceWith(categoryTemplate.render(CATEGORY.params,CATEGORY.partials))

window.fShowFooter = ()->
    $('.footer').replaceWith(footerTemplate.render(FOOTER.params,FOOTER.partials))

# ###
# Pager
# @param {object} dom : jquery selector,where to insert html
# @param {string} c : controller
# @param {string} a : action
# @param {object} params : params
# @param {object} pager : {item_count: count, page : page, page_count : page_count, data : data, page_size : page_size } 
# @param [option] {object} ajax : {className:'this pager class name',c:'controller', a:'action', params:'params', callback:function(){} } 
# ###
window.Pager = (dom,c,a,params,pager,ajax)->
    if pager.page_count <= 1
        return ''
    ajax or= false
    className = if ajax then 'pager_'+ajax.className else false
    last = false
    if pager.page>1
        params.p = pager.page-1
        last = {
            id : if ajax then className+params.p else false
            href : fGetUrl(c,a,params)
        }
    next = false
    if pager.page<pager.page_count
        params.p = pager.page+1
        next = {
            id : if ajax then className+params.p else false
            href : fGetUrl(c,a,params)
        }
    p = {
        className : className
        last : last
        next : next
        list : []
    }

    b2 = pager.page-2
    a2 = pager.page+2
    has_ellipsis = false
    i = 1
    while i <= pager.page_count
        # alway show first and last page; 
        # when show first or last not show last or next; 
        # show tow page before or after now page.
        if (i>=b2 && i<=a2) or i is 1 or i is pager.page_count
            params.p = i
            link = {
                ellipsis : false
                id : if ajax then className+i else false
                href : fGetUrl(c,a,params)
                name : i
                select : i is pager.page
            }
        else
            link = if has_ellipsis then false else {ellipsis : true}
            has_ellipsis = true
        link and p.list.push(link)
        i++

    dom.html(pagerTemplate.render(p))

    if ajax
        dom.find('[id^='+className+']').click(->
            p = this.id.replace(className,'')
            ajax.params.p = p
            fOneAjax(ajax.c,ajax.a,ajax.params,ajax.callback,this)
            false
        )

# ###
# get the time of server
# ###
window.fGetTime = ->
    Math.floor(STIME+(new Date().getTime()-CTIME)/1000)

# ###
# @param {string} c controller or controller/action
# @param {string} a or object action
# @param {object} p params
# ###
window.fGetUrl = (c, a, p) ->
    if a is `undefined`
        pieces = c.split("/")
        arr = URLCACHE
        i = 0
        while i < pieces.length
              arr = arr[pieces[i]]  if arr[pieces[i]]
              i++
        BASEURL + "/" + c + "?v=" + arr
    else
        url = undefined
        param = undefined
        if typeof (a) is "string"
            url = BASEURI + "/" + c + "/" + a
            param = p
        else
            url = BASEURI + "/" + c
            param = a
        if param
            l = []
            for k of param
                l.push encodeURIComponent(k) + "=" + encodeURIComponent(param[k]) if param[k]?
            url += '.html'
            l.length > 0 and (url += "?" + l.join("&"))
        url

# ###
# @param {time} time
# @param {number} flag 1 Y-d-m, 2 Y-d-m h:i:s
# ###
window.fDateFormat = (time,flag)->
    date = new Date(time*1000)
    ret = ''
    if not flag or flag is 1
        ret = "#{date.getFullYear()}-#{date.getMonth()+1}-#{date.getDate()}"
    else if flag is 2
        ret = "#{date.getFullYear()}-#{date.getMonth()+1}-#{date.getDate()} #{date.getHours()}:#{date.getMinutes()}:#{date.getSeconds()}"
    ret

# send ajax after last one succeeded
window.fOneAjax = (c,a,data,succ_callback,dom)->
    if dom
        k_disable = 0
        $(dom).each(->
            if $(this).data("k_disable")
                k_disable = 1
            true
        )
        if k_disable
            return false
        else
            $(dom).data("k_disable",1)

    succ = (obj)->
        dom and $(dom).data("k_disable",0)
        if obj is null or typeof obj isnt "object" or not obj.code
            fail("","parsererror","")
        else if obj.code is "-1"
            fail("","servererror",obj.msg)
        else
            succ_callback(obj)
        true

    fail = (jqXHR, textStatus, errorThrown)->
        dom and $(dom).data("k_disable",0)
        if jqXHR.status is 0 and textStatus is "error"
            alert("ajax error: #{errorThrown}")
        else if textStatus is "parsererror"
            alert('ajax parsererror');
        else if textStatus is "servererror"
            alert("ajax servererror: #{errorThrown}")
        else
            alert('ajax error: other')
        true
    $.ajax({url:fGetUrl(c,a),data:data,dataType:"json",type: "POST",success:succ,error:fail})

# ###
# operate Cookie
# @param {string} uniqueN 
# ###
window.YCache = (uniqueN)->
    uniqueN = if typeof(uniqueN) isnt "string" then "" else 'uniqueN_' + uniqueN + '_'
    setCookie = (name, value)->
        Days = 1
        exp = new Date()
        exp.setTime(exp.getTime() + Days * 24 * 60 * 60 * 1000)
        document.cookie = name + '=' + escape(encode(value)) + ';expires=' + exp.toGMTString()

    getCookie = (name)->
        arr = document.cookie.match(new RegExp('(^| )' + name + '=([^;]*)(;|$)'))
        ret = unencode(unescape(arr[2])) if arr

    delCookie = (name)->
        exp = new Date()
        exp.setTime(exp.getTime() - 1)
        tem = getCookie(name)
        if tem
            document.cookie = 'name=' + tem + ';expires=' + exp.toString()

    encode = (str)->
        temstr = ''
        i = str.length
        while i-- > 0
            temstr = temstr + str.charCodeAt(i)
            if i
                temstr = temstr+'a'
        temstr

    unencode = (str)->
        strarr = '';
        temstr = '';
        strarr = str.split("a")
        i = str.length
        while i-- > 0
            temstr += String.fromCharCode(eval(strarr[i]))
        temstr;

    {
        set: (text)->
            setCookie(uniqueN, text)
        ,
        clear: ->
            delCookie(uniqueN)
        ,
        get: ->
            getCookie(uniqueN)
    }

window.State = {}
(->
  State.forwardToUrl = (url) ->
    document.location.href = url

  State.forward = (c, a, p) ->
    document.location.href = fGetUrl(c, a, p)

  State.replace = (c, a, p) ->
    document.location.replace fGetUrl(c, a, p)

  State.back = (n) ->
    n = n or 0
    history.go -n
)()

