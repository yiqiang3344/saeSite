var blogEditTpl=new Hogan.Template();blogEditTpl.r =function(c,p,i){var _=this;_.b(i=i||"");_.b("<div class=\"mblog_edit js_blog_edit\">");_.b("\n" + i);_.b("    <form id=\"js_form_blog\">");_.b("\n" + i);_.b("        <div class=\"title\">");_.b("\n" + i);_.b("            <input class=\"input js_title\" name=\"title\" type=\"text\" value=\"");_.b(_.v(_.d("blog.title",c,p,0)));_.b("\" placeholder=\"标题\">");_.b("\n" + i);_.b("        </div>");_.b("\n" + i);_.b("        <div class=\"content\">");_.b("\n" + i);_.b("            <textarea class=\"textarea js_content\" name=\"content\" id=\"blog_content\">");_.b(_.v(_.d("blog.content",c,p,0)));_.b("</textarea>");_.b("\n" + i);_.b("        </div>");_.b("\n" + i);_.b("    </form>");_.b("\n" + i);_.b("</div>");return _.fl();;};