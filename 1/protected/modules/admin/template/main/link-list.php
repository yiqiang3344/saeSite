<div>
    <div>
        <span>
            搜索：
            <input type="text" id="search_val"/>
            <select id="search_type">
                <option value="name">名称</option>
                <option value="id">id</option>
            </select>
            <input type="submit" id="search" value="确定"/>
        </span>
    </div>
    <ul class="mlist js_link">
        <li class="clearfix">
            <div class="fl w50">
                 <input type="checkbox" class="js_cbox_all"/>
            </div>
            <div class="attr" id="order_id">id</div>
            <div class="attr" id="order_name">名称</div>
            <div class="attr" id="order_url">url标识</div>
            <div class="attr" id="order_sort">权重</div>
            <div class="attr" id="order_deleteFlag">已删除</div>
        </li>
        {{#data}}
        <li  class="clearfix" id="info_{{id}}">
            <div class="fl w50">
                 <input type="checkbox" class="js_cbox" value="{{id}}"/>
            </div>
            <div class="attr">{{id}}</div>
            <div class="attr" id="attr_name">{{name}}</div>
            <div class="attr" id="attr_url">{{url}}</div>
            <div class="attr" id="attr_sort">{{sort}}</div>
            <div class="attr" id="attr_deleteFlag">{{deleteFlag}}</div>
        </li>
        {{/data}}
    </ul>
    <div>
        <input type="submit" id="add" value="添加"/>
        <input type="submit" id="delete" value="删除"/>
    </div>
    <div id="page"></div>
</div>