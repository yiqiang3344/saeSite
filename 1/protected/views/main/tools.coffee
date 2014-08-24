window.oPage = oFinalPage.extend
    fRefresh : (bRefresh)->
        @_super()
    fBind : ()->
        source = $('#source')
        output = $('#output')
        output_html = $('#output_html')
        tmp_html = '<!doctype html>\r\n <html lang="en">\r\n <head>\r\n <meta charset="UTF-8">\r\n <title>Document\</title>\r\n </head>\r\n <body>\r\n </body>\r\n </html>'

        $('#compress_js').click ()->
            s_val = source.val()
            fOneAjax('Main','AjaxCompress',{type:'js',source:source.val()},(obj)->
                if obj.code==1
                    output_html.hide()
                    output.show().val(obj.ret)
            ,@)
        $('#format_js').click ()->
            s_val = source.val()
            output_html.hide()
            output.show().val(jsBeautify(s_val))
        $('#compress_css').click ()->
            s_val = source.val()
            fOneAjax('Main','AjaxCompress',{type:'css',source:source.val()},(obj)->
                if obj.code==1
                    output_html.hide()
                    output.show().val(obj.ret)
            ,@)
        $('#format_css').click ()->
            s_val = source.val()
            fOneAjax('Main','AjaxFormat',{type:'css',source:source.val()},(obj)->
                if obj.code==1
                    output_html.hide()
                    output.show().val(obj.ret)
            ,@)
        $('#encrypt').click ()->
            type = $('#encrypt_type').val()
            ret = ''
            if type in ['md5','base64_encode','base64_decode','addslashes','stripslashes','htmlentities','html_entity_decode','json_encode','json_decode']
                fOneAjax('Main','AjaxEncrypt',{type:type,source:source.val()},(obj)->
                    if obj.code==1
                        output_html.hide()
                        output.show().val(obj.ret)
                ,@)
            else
                if type=='jsencodeuri'
                    ret = encodeURI(source.val())
                else if type=='jsdecodeuri'
                    ret = decodeURI(source.val())
                else if type=='jsencodeuricomponent'
                    ret = encodeURIComponent(source.val())
                else if type=='jsdecodeuricomponent'
                    ret = decodeURIComponent(source.val())
                output_html.hide()
                output.show().val(ret)
        $('#swap').click ()->
            s_val = source.val()
            output_html.hide()
            source.val(output.val())
            output.show().val(s_val)
            false
        $('#execute_html').click ()->
            s = document.getElementById("source")
            output.hide()
            output_html.show().html(s.value)
            false
        $('#execute_js').click ()->
            s = document.getElementById("source")
            eval(s.value)
            false
        $('#tmp_html').click ()->
            source.val(tmp_html)
        $('#clear_source').click ()->
            source.val('')
            output.show().val('')
            output_html.hide().html('')
        $('#get_time').click ()->
            source.val(fGetTime())
        $('#date_format').click ()->
            date = source.val()
            output_html.hide()
            output.show().val(fDateFormat(date,2))
    __fInitData : ()->
        