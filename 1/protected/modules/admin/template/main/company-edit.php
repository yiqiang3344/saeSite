<form id="form" method="#" action="post">
    <div class="category">
        <span>种类</span>
        <input class="attr" id="category" name="category" type="text" value="{{category}}">
    </div>
    <div class="name">
        <span>名称</span>
        <input class="attr" id="name" name="name" type="text" value="{{name}}">
    </div>
    <div class="nameFirstLetter">
        <span>首字母</span>
        <input class="attr" id="nameFirstLetter" name="nameFirstLetter" type="text" value="{{nameFirstLetter}}">
    </div>
    <div class="weight">
        <span>权重</span>
        <input class="attr" id="weight" name="weight" type="text" value="{{weight}}">
    </div>
    <div class="logo">
        <span>logo</span>
        <div><img src="{{logo}}" alt="无"></div>
        <input class="attr" id="logo" name="logo" type="file"/>
    </div>
    <div class="star">
        <span>星级</span>
        <input class="attr" id="star" name="star" type="text" value="{{star}}">
    </div>
    <div class="score">
        <span>评分</span>
        <input class="attr" id="score" name="score" type="text" value="{{score}}">
    </div>
    <div class="beFixed">
        <span>固定</span>
        <input class="attr" id="beFixed" name="beFixed" type="text" value="{{beFixed}}">
    </div>
    <div class="beRecommend">
        <span>推荐</span>
        <input class="attr" id="beRecommend" name="beRecommend" type="text" value="{{beRecommend}}">
    </div>
    <div class="beGuarantee">
        <span>担保</span>
        <input class="attr" id="beGuarantee" name="beGuarantee" type="text" value="{{beGuarantee}}">
    </div>
    <div class="clickCount">
        <span>点击数</span>
        <input class="attr" id="clickCount" name="clickCount" type="text" value="{{clickCount}}">
    </div>
    <div class="commentCount">
        <span>评论数</span>
        <input class="attr" id="commentCount" name="commentCount" type="text" value="{{commentCount}}">
    </div>
    <div class="platform">
        <span>平台</span>
        <input class="attr" id="platform" name="platform" type="text" value="{{platform}}">
    </div>
    <div class="hasLicense">
        <span>有证书</span>
        <input class="attr" id="hasLicense" name="hasLicense" type="text" value="{{hasLicense}}">
    </div>
    <div class="openedTime">
        <span>开业时间</span>
        <input class="attr" id="openedTime" name="openedTime" type="text" value="{{openedTime}}">
    </div>
    <div class="url">
        <span>网址</span>
        <input class="attr" id="url" name="url" type="text" value="{{url}}">
    </div>
    <div class="urlPhoto">
        <span>网页快照</span>
        <div><img src="{{urlPhoto}}" alt="无"></div>
        <input class="attr" id="urlPhoto" name="urlPhoto" type="file"/>
    </div>
    <div class="deleteFlag">
        <span>删除</span>
        <input class="attr" id="deleteFlag" name="deleteFlag" type="text" value="{{deleteFlag}}">
    </div>
    <div class="abstract">
        <span>简介</span>
        <textarea style="width:400px;height:200px;" name="content" class="attr" id="abstract" name="abstract">{{abstract}}</textarea>
    </div>
    <div class="description">
        <span>描述</span>
        <textarea style="width:600px;height:400px;" name="content" class="attr" id="description" name="description">{{description}}</textarea>
    </div>
    <div>
        <input id="submit" type="submit" value="提交">
    </div>
</form>
