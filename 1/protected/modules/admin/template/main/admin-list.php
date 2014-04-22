<div>
    <div>
        <span>
            搜索：
            <input type="text" id="search_val"/>
            <select id="search_type">
                <option value="username">名字</option>
                <option value="deleteFlag">禁用</option>
                <option value="id">id</option>
            </select>
            <input type="submit" id="search" value="确定"/>
        </span>
    </div>
    <ul class="mlist js_admin">
        <li class="clearfix">
            <div class="fl w50">
                 <input type="checkbox" class="js_cbox_all"/>
            </div>
            <div class="attr" id="order_id">id</div>
            <div class="attr" id="order_username">名字</div>
            <div class="attr" id="order_deleteFlag">禁用</div>
        </li>
        {{#data}}
        <li  class="clearfix" id="info_{{id}}">
            <div class="fl w50">
                 <input type="checkbox" class="js_cbox" value="{{id}}"/>
            </div>
            <div class="attr">{{id}}</div>
            <div class="attr" id="">{{username}}</div>
            <div class="attr" id="attr_deleteFlag">{{deleteFlag}}</div>
        </li>
        {{/data}}
    </ul>
    <div>
        <input type="submit" id="add" value="添加"/>
        <input type="submit" id="delete" value="禁用"/>
    </div>
    <div id="page"></div>
</div>