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
    <ul class="mlist js_comment">
        <li class="clearfix">
            <div class="fl w50">
                 <input type="checkbox" class="js_cbox_all"/>
            </div>
            <div class="attr" id="order_id">id</div>
            <div class="attr" id="order_companyId">公司id</div>
            <div class="attr" id="order_userId">用户id</div>
            <div class="attr" id="order_username">用户名</div>
            <div class="attr" id="order_content">内容</div>
            <div class="attr" id="order_totalScore">总评分</div>
            <div class="attr" id="order_scoreA">评分A</div>
            <div class="attr" id="order_scoreB">评分B</div>
            <div class="attr" id="order_scoreC">评分C</div>
            <div class="attr" id="order_deleteFlag">已删除</div>
        </li>
        {{#data}}
        <li  class="clearfix" id="info_{{id}}">
            <div class="fl w50">
                 <input type="checkbox" class="js_cbox" value="{{id}}"/>
            </div>
            <div class="attr">{{id}}</div>
            <div class="attr" id="">{{companyId}}</div>
            <div class="attr" id="">{{userId}}</div>
            <div class="attr" id="">{{username}}</div>
            <div class="attr" id="attr_content">{{content}}</div>
            <div class="attr" id="attr_totalScore">{{totalScore}}</div>
            <div class="attr" id="attr_scoreA">{{scoreA}}</div>
            <div class="attr" id="attr_scoreB">{{scoreB}}</div>
            <div class="attr" id="attr_scoreC">{{scoreC}}</div>
            <div class="attr" id="attr_deleteFlag">{{deleteFlag}}</div>
            <div class="fl"><a href="" id="edit_{{id}}">编辑</a></div>
        </li>
        {{/data}}
    </ul>
    <div>
        <input type="submit" id="delete" value="删除"/>
    </div>
    <div id="page"></div>
</div>