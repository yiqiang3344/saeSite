var oTemplate=new Hogan.Template();oTemplate.r =function(c,p,i){var _=this;_.b(i=i||"");_.b("<div class=\"\">");_.b("\n" + i);_.b("    <ul>");_.b("\n" + i);if(_.s(_.f("aList",c,p,1),c,p,0,38,307,"{{ }}")){_.rs(c,p,function(c,p,_){_.b("        <li class=\"clearfix\">");_.b("\n" + i);_.b("            <span class=\"fl\"><a id=\"js_blog_");_.b(_.v(_.f("blogId",c,p,0)));_.b("\">");_.b(_.v(_.f("title",c,p,0)));_.b("</a></span>");_.b("\n" + i);_.b("            <span class=\"fr\">");_.b(_.v(_.d("sceneParams.createTime",c,p,0)));_.b("</span>");_.b("\n" + i);_.b("            <span class=\"fr ml10\"><a id=\"js_edit_blog_");_.b(_.v(_.f("blogId",c,p,0)));_.b("\">编辑</a></span>");_.b("\n" + i);_.b("        </li>");_.b("\n");});c.pop();}_.b("    </ul>");_.b("\n" + i);_.b("</div>");return _.fl();;};
// Generated by CoffeeScript 1.6.3
(function() {
  window.oPage = oFinalPage.extend({
    fRefresh: function(bRefresh) {
      return this._super();
    },
    fBind: function() {
      $('[id^=js_blog_]').click(function() {
        var id;
        id = this.id.replace('js_blog_', '');
        State.forward('Blog', 'Blog', {
          id: id
        });
        return false;
      });
      return $('[id^=js_edit_blog_]').click(function() {
        var id;
        id = this.id.replace('js_edit_blog_', '');
        State.forward('Blog', 'Edit', {
          id: id
        });
        return false;
      });
    },
    __fInitData: function() {}
  });

}).call(this);
