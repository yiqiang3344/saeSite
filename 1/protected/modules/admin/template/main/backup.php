<div>
    标识：
    <input id="name" type="text" placeholder="{{now}}">
    <input id="backup" type="submit" value="备份">
</div>
<div>
    <p>备份列表</p>
    <div>
        <span>
            搜索：
            <input type="text" id="search_val"/>
            <select id="search_type">
                <option value="name">标识</option>
                <option value="createTime">备份时间</option>
                <option value="id">id</option>
            </select>
            <input type="submit" id="search" value="确定"/>
        </span>
    </div>
    <ul class="mlist js_backup">
        <li class="clearfix">
            <div class="fl w50">
                 <input type="checkbox" class="js_cbox_all"/>
            </div>
            <div class="attr" id="order_id">id</div>
            <div class="attr long" id="order_name">标识</div>
            <div class="attr long" id="order_createTime">备份时间</div>
            <div class="attr long" id="order_lastRebackTime">还原时间</div>
        </li>
        {{#data}}
        <li  class="clearfix" id="info_{{id}}">
            <div class="fl w50">
                 <input type="checkbox" class="js_cbox" value="{{id}}"/>
            </div>
            <div class="attr">{{id}}</div>
            <div class="attr long" id="attr_name">{{name}}</div>
            <div class="attr long" id="">{{createTime}}</div>
            <div class="attr long" id="">{{lastRebackTime}}</div>
            <div class="fl"><a href="" id="reback_{{id}}">还原</a></div>
        </li>
        {{/data}}
    </ul>
    <div>
        <input type="submit" id="delete" value="删除"/>
    </div>
    <div id="page"></div>
</div>