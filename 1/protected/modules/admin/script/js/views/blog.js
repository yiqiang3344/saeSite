var blogEditTpl=new Hogan.Template();blogEditTpl.r =function(c,p,i){var _=this;_.b(i=i||"");_.b("<div class=\"mblog_edit js_blog_edit\">");_.b("\n" + i);_.b("    <form id=\"js_form_blog\">");_.b("\n" + i);_.b("        <div class=\"info\">");_.b("\n" + i);_.b("            <input class=\"title js_title\" name=\"title\" type=\"text\" value=\"");_.b(_.v(_.d("blog.title",c,p,0)));_.b("\" placeholder=\"标题\">");_.b("\n" + i);_.b("            <span class=\"category\">");_.b("\n" + i);_.b("                分类:");_.b("\n" + i);_.b("                <select class=\"js_blogCategoryId\" name=\"blogCategoryId\">");_.b("\n" + i);if(_.s(_.f("blogCategoryList",c,p,1),c,p,0,372,501,"{{ }}")){_.rs(c,p,function(c,p,_){_.b("                    <option value=\"");_.b(_.v(_.f("blogCategoryId",c,p,0)));_.b("\" ");if(_.s(_.f("selected",c,p,1),c,p,0,441,449,"{{ }}")){_.rs(c,p,function(c,p,_){_.b("selected");});c.pop();}_.b(">");_.b(_.v(_.f("name",c,p,0)));_.b("</option>");_.b("\n");});c.pop();}_.b("                </select>");_.b("\n" + i);_.b("            </span>");_.b("\n" + i);_.b("        </div>");_.b("\n" + i);_.b("        <div class=\"content\">");_.b("\n" + i);_.b("            <textarea class=\"textarea js_content\" name=\"content\" id=\"blog_content\">");_.b(_.v(_.d("blog.content",c,p,0)));_.b("</textarea>");_.b("\n" + i);_.b("        </div>");_.b("\n" + i);_.b("    </form>");_.b("\n" + i);_.b("</div>");return _.fl();;};