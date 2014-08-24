var oTemplate=new Hogan.Template();oTemplate.r =function(c,p,i){var _=this;_.b(i=i||"");_.b("<div>");_.b("\n" + i);_.b("    <span><input id=\"tmp_html\" type=\"button\" value=\"html模板\"></span>");_.b("\n" + i);_.b("    <span><input id=\"get_time\" type=\"button\" value=\"时间戳\"></span>");_.b("\n" + i);_.b("</div>");_.b("\n" + i);_.b("<div>");_.b("\n" + i);_.b("    <textarea id=\"source\" class=\"wall h150px\"></textarea>");_.b("\n" + i);_.b("</div>");_.b("\n" + i);_.b("<div>");_.b("\n" + i);_.b("    <span><input id=\"clear_source\" type=\"button\" value=\"还原\"></span>");_.b("\n" + i);_.b("    <span><input id=\"swap\" type=\"button\" value=\"互换\"></span>");_.b("\n" + i);_.b("    <span><input id=\"date_format\" type=\"button\" value=\"日期格式化\"></span>");_.b("\n" + i);_.b("    <span><input id=\"execute_js\" type=\"button\" value=\"执行js\"></span>");_.b("\n" + i);_.b("    <span><input id=\"execute_html\" type=\"button\" value=\"执行html\"></span>");_.b("\n" + i);_.b("    <span>");_.b("\n" + i);_.b("        <select id=\"encrypt_type\" type=\"button\">");_.b("\n" + i);_.b("            <option value=\"json_encode\">数组转json</option>");_.b("\n" + i);_.b("            <option value=\"json_decode\">json转数组</option>");_.b("\n" + i);_.b("            <option value=\"md5\">md5</option>");_.b("\n" + i);_.b("            <option value=\"base64_encode\">转base64</option>");_.b("\n" + i);_.b("            <option value=\"base64_decode\">解base64</option>");_.b("\n" + i);_.b("            <option value=\"addslashes\">转义</option>");_.b("\n" + i);_.b("            <option value=\"stripslashes\">反转义</option>");_.b("\n" + i);_.b("            <option value=\"htmlentities\">转实体</option>");_.b("\n" + i);_.b("            <option value=\"html_entity_decode\">反转实体</option>");_.b("\n" + i);_.b("            <option value=\"jsencodeuri\">转jsURI</option>");_.b("\n" + i);_.b("            <option value=\"jsdecodeuri\">解jsURI</option>");_.b("\n" + i);_.b("            <option value=\"jsencodeuricomponent\">转jsURIComponent</option>");_.b("\n" + i);_.b("            <option value=\"jsdecodeuricomponent\">解jsURIComponent</option>");_.b("\n" + i);_.b("        </select>");_.b("\n" + i);_.b("        <input id=\"encrypt\" type=\"button\" value=\"执行\">");_.b("\n" + i);_.b("    </span>");_.b("\n" + i);_.b("    <span><input id=\"format_css\" type=\"button\" value=\"css格式化\"></span>");_.b("\n" + i);_.b("    <span><input id=\"compress_css\" type=\"button\" value=\"css压缩\"></span>");_.b("\n" + i);_.b("    <span><input id=\"format_js\" type=\"button\" value=\"js格式化\"></span>");_.b("\n" + i);_.b("    <span><input id=\"compress_js\" type=\"button\" value=\"js压缩\"></span>");_.b("\n" + i);_.b("</div>");_.b("\n" + i);_.b("<div id=\"output_html\" class=\"dn\">");_.b("\n" + i);_.b("</div>");_.b("\n" + i);_.b("<div>");_.b("\n" + i);_.b("    <textarea id=\"output\" class=\"wall h150px\"></textarea>");_.b("\n" + i);_.b("</div>");return _.fl();;};
// Generated by CoffeeScript 1.6.3
(function() {
  var __indexOf = [].indexOf || function(item) { for (var i = 0, l = this.length; i < l; i++) { if (i in this && this[i] === item) return i; } return -1; };

  window.oPage = oFinalPage.extend({
    fRefresh: function(bRefresh) {
      return this._super();
    },
    fBind: function() {
      var output, output_html, source, tmp_html;
      source = $('#source');
      output = $('#output');
      output_html = $('#output_html');
      tmp_html = '<!doctype html>\r\n <html lang="en">\r\n <head>\r\n <meta charset="UTF-8">\r\n <title>Document\</title>\r\n </head>\r\n <body>\r\n </body>\r\n </html>';
      $('#compress_js').click(function() {
        var s_val;
        s_val = source.val();
        return fOneAjax('Main', 'AjaxCompress', {
          type: 'js',
          source: source.val()
        }, function(obj) {
          if (obj.code === 1) {
            output_html.hide();
            return output.show().val(obj.ret);
          }
        }, this);
      });
      $('#format_js').click(function() {
        var s_val;
        s_val = source.val();
        output_html.hide();
        return output.show().val(jsBeautify(s_val));
      });
      $('#compress_css').click(function() {
        var s_val;
        s_val = source.val();
        return fOneAjax('Main', 'AjaxCompress', {
          type: 'css',
          source: source.val()
        }, function(obj) {
          if (obj.code === 1) {
            output_html.hide();
            return output.show().val(obj.ret);
          }
        }, this);
      });
      $('#format_css').click(function() {
        var s_val;
        s_val = source.val();
        return fOneAjax('Main', 'AjaxFormat', {
          type: 'css',
          source: source.val()
        }, function(obj) {
          if (obj.code === 1) {
            output_html.hide();
            return output.show().val(obj.ret);
          }
        }, this);
      });
      $('#encrypt').click(function() {
        var ret, type;
        type = $('#encrypt_type').val();
        ret;
        if (__indexOf.call({
          md5: 1,
          base64_encode: 1,
          base64_decode: 1,
          addslashes: 1,
          stripslashes: 1,
          htmlentities: 1,
          html_entity_decode: 1,
          json_encode: 1,
          json_decode: 1
        }, type) >= 0) {
          return fOneAjax('Main', 'AjaxEncrypt', {
            type: type,
            source: source.val()
          }, function(obj) {
            if (obj.code === 1) {
              output_html.hide();
              return output.show().val(obj.ret);
            }
          }, this);
        } else {
          if (type === 'jsencodeuri') {
            ret = encodeURI(source.val());
          } else if (type === 'jsdecodeuri') {
            ret = decodeURI(source.val());
          } else if (type === 'jsencodeuricomponent') {
            ret = decodeURIComponent(source.val());
          } else if (type === 'jsdecodeuricomponent') {
            ret = encodeURIComponent(source.val());
          }
          output_html.hide();
          return output.show().val(ret);
        }
      });
      $('#swap').click(function() {
        var s_val;
        s_val = source.val();
        output_html.hide();
        source.val(output.val());
        output.show().val(s_val);
        return false;
      });
      $('#execute_html').click(function() {
        var s;
        s = document.getElementById("source");
        output.hide();
        output_html.show().html(s.value);
        return false;
      });
      $('#execute_js').click(function() {
        var s;
        s = document.getElementById("source");
        eval(s.value);
        return false;
      });
      $('#tmp_html').click(function() {
        return source.val(tmp_html);
      });
      $('#clear_source').click(function() {
        source.val('');
        output.show().val('');
        return output_html.hide().html('');
      });
      $('#get_time').click(function() {
        return source.val(fGetTime());
      });
      return $('#date_format').click(function() {
        var date;
        date = source.val();
        output_html.hide();
        return output.show().val(fDateFormat(date, 2));
      });
    },
    __fInitData: function() {}
  });

}).call(this);