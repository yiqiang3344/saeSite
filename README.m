先进入build中通过如下命令完成项目成品化
    config_build 设置build的配置文件
    bin/phing [-Dbuild.env=config.properties] config_build

    config_site 设置站点的配置文件
    bin/phing [-Dbuild.env=config.properties] config_site

    config 设置build和站点的配置文件
    bin/phing [-Dbuild.env=config.properties] config

    sass 生成css
    bin/phing [-Dbuild.env=config.properties] sass

    sassAdmin 生成admin的css
    bin/phing [-Dbuild.env=config.properties] sassAdmin

    coffee 生成js
    bin/phing [-Dbuild.env=config.properties] coffee

    coffeeAdmin 生成admin的js
    bin/phing [-Dbuild.env=config.properties] coffeeAdmin

    rmAssets 清除暂存文件
    bin/phing [-Dbuild.env=config.properties] rmAssets

    setCssJs 生成css和js
    bin/phing [-Dbuild.env=config.properties] setCssJs

    setAdminCssJs 生成admin的css和js并清除assets
    bin/phing [-Dbuild.env=config.properties] setAdminCssJs

    coffeeViews 执行setCssJs，并处理site的views中的coffee生成js并处理所有mustache模板
    bin/phing [-Dbuild.env=config.properties] coffeeViews

    coffeeAdminViews 执行setAdminCssJs，并处理admin的views中的coffee生成js并处理所有mustache模板
    bin/phing [-Dbuild.env=config.properties] coffeeAdminViews

    cacheCode 处理图片生成url.js缓存号文件给指定css文件中的图片路径加上缓存号
    bin/phing [-Dbuild.env=config.properties] cacheCode

    clearLangs 根据配置删除各语言版本目录和文件
    bin/phing [-Dbuild.env=config.properties] clearLangs

    initLangs 根据配置复制生成各语言版本目录和文件
    bin/phing [-Dbuild.env=config.properties] initLangs

    tanslate 根据配置对指定文件作多语言翻译
    bin/phing [-Dbuild.env=config.properties] tanslate

    mini 压缩合并代码
        压缩指定目录，一般有 css,js,dev/js
        合并js目录下指定的js文件
    bin/phing [-Dbuild.env=config.properties] mini

    optimizeJs 优化js
        编译各语言的hoganjs
            公用模板追加到(lang)/js/helper.min.js 
            试图模板编译为(lang)/views/(controller)/(view).min.js
            局部模板保存为(lang)/views/(controller).min.js
        处理视图文件
            从视图文件夹的翻译视图中提取指定js代码,压缩后追加到(lang)/views/(controller)/(view).min.js中，不存在则创建;然后替换视图中提取代码为js文件外链
    bin/phing [-Dbuild.env=config.properties] optimizeJs

    release 生成发布版本
    bin/phing [-Dbuild.env=config.properties] release

    releaseJs 生成发布全js渲染版本
    bin/phing [-Dbuild.env=config.properties] releaseJs

    export 导出项目
        复制出发布版本所需要的文件
    bin/phing [-Dbuild.env=config.properties] [-Dproduct.dir=../product] export

