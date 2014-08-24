先进入build中通过如下命令完成项目成品化
    configBuild 设置build的配置文件
    bin/phing [-Dbuild.env=config] configBuild

    configSite 设置站点的配置文件
    bin/phing [-Dbuild.env=config] configSite

    config 设置build和站点的配置文件
    bin/phing [-Dbuild.env=config] config

    sass 生成css
    bin/phing [-Dbuild.env=config] sass

    sassAdmin 生成admin的css
    bin/phing [-Dbuild.env=config] sassAdmin

    coffee 生成js
    bin/phing [-Dbuild.env=config] coffee

    coffeeAdmin 生成admin的js
    bin/phing [-Dbuild.env=config] coffeeAdmin

    rmAssets 清除暂存文件
    bin/phing [-Dbuild.env=config] rmAssets

    setCssJs 生成css和js
    bin/phing [-Dbuild.env=config] setCssJs

    setAdminCssJs 生成admin的css和js并清除assets
    bin/phing [-Dbuild.env=config] setAdminCssJs

    coffeeViews 执行setCssJs，并处理site的views中的coffee生成js并处理所有mustache模板
    bin/phing [-Dbuild.env=config] coffeeViews

    coffeeAdminViews 执行setAdminCssJs，并处理admin的views中的coffee生成js并处理所有mustache模板
    bin/phing [-Dbuild.env=config] coffeeAdminViews

    cacheCode 处理图片生成url.js缓存号文件给指定css文件中的图片路径加上缓存号
    bin/phing [-Dbuild.env=config] cacheCode

    clearLangs 根据配置删除各语言版本目录和文件
    bin/phing [-Dbuild.env=config] clearLangs

    initLangs 根据配置复制生成各语言版本目录和文件
    bin/phing [-Dbuild.env=config] initLangs

    tanslate 根据配置对指定文件作多语言翻译
    bin/phing [-Dbuild.env=config] tanslate

    mini 压缩合并代码
        压缩指定目录，一般有 css,js,dev/js
        合并js目录下指定的js文件
    bin/phing [-Dbuild.env=config] mini

    optimizeJs 优化js
        编译各语言的hoganjs
            公用模板追加到(lang)/js/helper.min.js 
            试图模板编译为(lang)/views/(controller)/(view).min.js
            局部模板保存为(lang)/views/(controller).min.js
        处理视图文件
            从视图文件夹的翻译视图中提取指定js代码,压缩后追加到(lang)/views/(controller)/(view).min.js中，不存在则创建;然后替换视图中提取代码为js文件外链
    bin/phing [-Dbuild.env=config] optimizeJs

    release 生成发布版本
    bin/phing [-Dbuild.env=config] release

    releaseJs 生成发布全js渲染版本
    bin/phing [-Dbuild.env=config] releaseJs

    export 导出项目
        复制出发布版本所需要的文件
    bin/phing [-Dbuild.env=config] [-Dproduct.dir=../product] export

