// Generated by CoffeeScript 1.6.3
(function() {
  window.oFinalPage = Class.extend({
    init: function(oArgs) {
      this.oArgs = oArgs;
      return this.__fInitData();
    },
    __fInitData: function() {},
    fPrint: function() {
      return this.fRefresh();
    },
    fRefresh: function(bRefresh) {
      bRefresh = bRefresh != null ? bRefresh : false;
      $('.maincontent').html(this.oArgs.oTemplate.render(this.oArgs.oParams, this.oArgs.oPartials));
      return this.fBind();
    },
    fBind: function() {
      return $('.js_back').click(function() {
        return State.back(1);
      });
    }
  });

}).call(this);

var categoryTemplate=new Hogan.Template();categoryTemplate.r =function(c,p,i){var _=this;_.b(i=i||"");_.b("<div class=\"category\">");_.b("\n" + i);_.b("    <p class=\"mb1\">博客分类</p>");_.b("\n" + i);_.b("    <ul>");_.b("\n" + i);if(_.s(_.f("aList",c,p,1),c,p,0,74,189,"{{ }}")){_.rs(c,p,function(c,p,_){_.b("        <li class=\"");if(_.s(_.f("on",c,p,1),c,p,0,101,103,"{{ }}")){_.rs(c,p,function(c,p,_){_.b("on");});c.pop();}_.b("\">");_.b("\n" + i);_.b("            <a href=\"");_.b(_.v(_.f("url",c,p,0)));_.b("\">");_.b(_.v(_.f("name",c,p,0)));_.b("(");_.b(_.v(_.f("blogCount",c,p,0)));_.b(")</a>");_.b("\n" + i);_.b("        </li>");_.b("\n");});c.pop();}_.b("    </ul>");_.b("\n" + i);_.b("</div>");return _.fl();;};
var footerTemplate=new Hogan.Template();footerTemplate.r =function(c,p,i){var _=this;_.b(i=i||"");_.b("<div class=\"footer\"></div>");return _.fl();;};
var headerTemplate=new Hogan.Template();headerTemplate.r =function(c,p,i){var _=this;_.b(i=i||"");_.b("<div class=\"header\">");_.b("\n" + i);_.b("    <div class=\"logo clearfix\">");_.b("\n" + i);_.b("        <p class=\"name\">sidney</p>");_.b("\n" + i);_.b("        <img class=\"img\" src=\"");_.b(_.v(_.f("logo",c,p,0)));_.b("\" alt=\"sidney\">");_.b("\n" + i);_.b("    </div>");_.b("\n" + i);_.b("</div>");return _.fl();;};
var categoryTemplate=new Hogan.Template();categoryTemplate.r =function(c,p,i){var _=this;_.b(i=i||"");_.b("<div class=\"category\">");_.b("\n" + i);_.b("    <p class=\"mb1\">博客分类</p>");_.b("\n" + i);_.b("    <ul>");_.b("\n" + i);if(_.s(_.f("aList",c,p,1),c,p,0,74,189,"{{ }}")){_.rs(c,p,function(c,p,_){_.b("        <li class=\"");if(_.s(_.f("on",c,p,1),c,p,0,101,103,"{{ }}")){_.rs(c,p,function(c,p,_){_.b("on");});c.pop();}_.b("\">");_.b("\n" + i);_.b("            <a href=\"");_.b(_.v(_.f("url",c,p,0)));_.b("\">");_.b(_.v(_.f("name",c,p,0)));_.b("(");_.b(_.v(_.f("blogCount",c,p,0)));_.b(")</a>");_.b("\n" + i);_.b("        </li>");_.b("\n");});c.pop();}_.b("    </ul>");_.b("\n" + i);_.b("</div>");return _.fl();;};
var footerTemplate=new Hogan.Template();footerTemplate.r =function(c,p,i){var _=this;_.b(i=i||"");_.b("<div class=\"footer\"></div>");return _.fl();;};
var headerTemplate=new Hogan.Template();headerTemplate.r =function(c,p,i){var _=this;_.b(i=i||"");_.b("<div class=\"header\">");_.b("\n" + i);_.b("    <div class=\"logo clearfix\">");_.b("\n" + i);_.b("        <p class=\"name\">sidney</p>");_.b("\n" + i);_.b("        <img class=\"img\" src=\"");_.b(_.v(_.f("logo",c,p,0)));_.b("\" alt=\"sidney\">");_.b("\n" + i);_.b("    </div>");_.b("\n" + i);_.b("</div>");return _.fl();;};