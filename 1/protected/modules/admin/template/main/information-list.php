<div>
    <div>
        <span>
            搜索：
            <input type="text" id="search_val"/>
            <select id="search_type">
                <option value="title">标题</option>
                <option value="id">id</option>
            </select>
            <input type="submit" id="search" value="确定"/>
        </span>
    </div>
    <ul class="mlist js_information">
        <li class="clearfix">
            <div class="fl w50">
                 <input type="checkbox" class="js_cbox_all"/>
            </div>
            <div class="attr" id="order_id">id</div>
            <div class="attr" id="order_title">标题</div>
            <div class="attr" id="order_abstract">简要</div>
            <div class="attr" id="order_img">有图片</div>
            <div class="attr" id="order_deleteFlag">已删除</div>
        </li>
        {{#data}}
        <li  class="clearfix" id="info_{{id}}">
            <div class="fl w50">
                 <input type="checkbox" class="js_cbox" value="{{id}}"/>
            </div>
            <div class="attr">{{id}}</div>
            <div class="attr" id="attr_title">{{title}}</div>
            <div class="attr" id="attr_abstract">{{abstract}}</div>
            <div class="attr img" id="">{{#img}}<img src="{{img}}" alt="无"/>{{/img}}</div>
            <div class="attr" id="attr_deleteFlag">{{deleteFlag}}</div>
            <div class="fl"><a href="" id="edit_{{id}}">编辑</a></div>
        </li>
        {{/data}}
    </ul>
    <div>
        <input type="submit" id="add" value="添加"/>
        <input type="submit" id="delete" value="删除"/>
    </div>
    <div id="page"></div>
</div>