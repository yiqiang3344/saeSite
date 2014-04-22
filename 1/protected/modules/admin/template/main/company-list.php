<div>
    <div>
        <span>
            搜索：
            <input type="text" id="search_val"/>
            <select id="search_type">
                <option value="name">名字</option>
                <option value="id">id</option>
                <option value="category">种类</option>
            </select>
            <input type="submit" id="search" value="确定"/>
        </span>
    </div>
    <ul class="mlist js_company">
        <li class="clearfix">
            <div class="fl w50">
                 <input type="checkbox" class="js_cbox_all"/>
            </div>
            <div class="attr short" id="order_id">id</div>
            <div class="attr" id="order_category">种类</div>
            <div class="attr" id="order_name">名字</div>
            <div class="attr" id="order_nameFirstLetter">首字母</div>
            <div class="attr" id="order_weight">权重</div>
            <div class="attr img" id="order_logo">logo</div>
            <div class="attr" id="order_star">星级</div>
            <div class="attr" id="order_score">评分</div>
            <div class="attr" id="order_beFixed">固定</div>
            <div class="attr" id="order_beRecommend">推荐</div>
            <div class="attr" id="order_beGuarantee">担保</div>
            <div class="attr" id="order_clickCount">点击数</div>
            <div class="attr" id="order_commentCount">评论数</div>
            <div class="attr" id="order_platform">平台</div>
            <div class="attr" id="order_hasLicense">有证书</div>
            <div class="attr" id="order_openedTime">开业时间</div>
            <div class="attr" id="order_url">网址</div>
            <div class="attr img" id="order_urlPhoto">网站快照</div>
            <div class="attr" id="order_abstract">简介</div>
            <div class="attr" id="order_deleteFlag">已删除</div>
        </li>
        {{#data}}
        <li  class="clearfix" id="info_{{id}}">
            <div class="fl w50">
                 <input type="checkbox" class="js_cbox" value="{{id}}"/>
            </div>
            <div class="attr short">{{id}}</div>
            <div class="attr" id="attr_category">{{category}}</div>
            <div class="attr" id="attr_name">{{name}}</div>
            <div class="attr" id="attr_nameFirstLetter">{{nameFirstLetter}}</div>
            <div class="attr" id="attr_weight">{{weight}}</div>
            <div class="attr img" id="attr_logo"><img src="{{logo}}"/></div>
            <div class="attr" id="attr_star">{{star}}</div>
            <div class="attr" id="attr_score">{{score}}</div>
            <div class="attr" id="attr_beFixed">{{beFixed}}</div>
            <div class="attr" id="attr_beRecommend">{{beRecommend}}</div>
            <div class="attr" id="attr_beGuarantee">{{beGuarantee}}</div>
            <div class="attr" id="attr_clickCount">{{clickCount}}</div>
            <div class="attr" id="attr_commentCount">{{commentCount}}</div>
            <div class="attr" id="attr_platform">{{platform}}</div>
            <div class="attr" id="attr_hasLicense">{{hasLicense}}</div>
            <div class="attr" id="attr_openedTime">{{openedTime}}</div>
            <div class="attr" id="attr_url">{{url}}</div>
            <div class="attr img" id="attr_urlPhoto"><img src="{{urlPhoto}}"/></div>
            <div class="attr" id="attr_abstract">{{abstract}}</div>
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