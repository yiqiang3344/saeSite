var hogan = require('hogan.js');
var fs = require('fs');
var helper = require('./helper');
var langList = [
        'zh_cn',
    ];

//找到views/_public所有文件，编译之后追加到(lang)/js/helper.min.js文件中
langList.forEach(function(lang){
    var 
        name
    ,   ss = ''
    ,   fd = fs.openSync(lang+'/js/'+'helper.min.js', 'a+');
    ;
    helper.scanDir('protected/views/_public/'+lang,true).forEach(function(file){
        if(helper.isFile(file)){
            name = helper.baseName(file,true)
            ss += '\nvar '+name+'=new Hogan.Template();'+name+'.r ='+hogan.compile(fs.readFileSync(file,'utf8'),{asString:true})+';'
        }
    });
    fs.writeSync(fd, ss);
    fs.closeSync(fd);
});

// 找到views下除了_public_template,layouts外所有目录
// 试图模板编译为(lang)/views/(controller)/(view).min.js
// 局部子模板保存为(lang)/views/(controller).min.js
helper.scanDir('protected/views/').forEach(function(dir){
    var dirbasename=helper.baseName(dir,true);
    if(helper.isDir(dir) && dirbasename!='_public' && dirbasename!='layouts'){
        langList.forEach(function(lang){
            var 
                made_js_map = {}
            ,   ss
            ;
            mkdir(lang+'/js/views');
            helper.scanDir(dir+'/'+lang).forEach(function(file){
                var 
                    basename = helper.baseName(file,true)
                ,   name = helper.baseName(file,false)
                ,   ext = name.substring(name.lastIndexOf('.')+1)
                ,   dir
                ,   mode
                ,   fd
                ;
                if(ext=='mustache' && basename.indexOf('_')==0){
                    //局部子模板
                    name = basename.substring(1);
                    ss = 'var '+name+'=new Hogan.Template();'+name+'.r ='+hogan.compile(fs.readFileSync(file,'utf8'),{asString:true})+';';
                    if(!made_js_map[dirbasename]){
                        mode = 'w+';//覆盖新建方式写文件
                    }else{
                        made_js_map[dirbasename] = true;
                        mode = 'a+';//添加新建方式写文件
                    }
                    fd = fs.openSync(lang+'/js/views/'+dirbasename+'.min.js', mode);
                    fs.writeSync(fd, ss);
                    fs.closeSync(fd);
                }else if(ext=='mustache'){
                    //试图模板
                    ss = 'var oTemplate=new Hogan.Template();oTemplate.r ='+hogan.compile(fs.readFileSync(file,'utf8'),{asString:true})+';';
                    helper.mkdir(lang+'/js/views/'+dirbasename);
                    fd = fs.openSync(lang+'/js/views/'+dirbasename+'/'+basename+'.min.js', 'w+');
                    fs.writeSync(fd, ss);
                    fs.closeSync(fd);
                }else if(ext=='php'){
                    // 视图文件
                    // 从视图文件夹的翻译视图中提取指定js代码,压缩后追加到(lang)/views/(controller)/(view).min.js中，不存在则创建;然后替换视图中提取代码为js文件外链
                    var 
                        res = jsExtract(fs.readFileSync(file,'utf8'),'js/views/'+dirbasename+'/'+basename+'.js')
                    ;
                    if(res[1]){
                        var fd = fs.openSync(file, 'w');
                        fs.writeSync(fd, res[0])
                        fs.closeSync(fd);
                        helper.mkdir(lang+'/js/views/'+dirbasename);
                        fd = fs.openSync(lang+'/js/views/'+dirbasename+'/'+basename+'.min.js', 'a+');//自然顺序php在mustache之后
                        fs.writeSync(fd, helper.compressJs(res[1]));
                        fs.closeSync(fd);
                    }else{
                        console.log(lang+'/js/views/'+dirbasename+'/'+basename+'.min.js content is empty');
                    }
                }else{
                    return;
                }
            });
        });
    }
});
console.log('success transformed template to js file.');

function jsExtract(content,path){
    var 
        m
    ,   js
    ;
    if(m=content.match(/<script\s+type\s*=\s*\"text\/javascript\"\s*>\s*\/\/static([\s\S]*?)<\/script>/im)){
        js = m[1].trim();
        content=content.replace(/(<script\s+type\s*=\s*"text\/javascript"\s*>\s*\/\/template([\s\S]*?)<\/script>\s*)?<script\s+type\s*=\s*"text\/javascript"\s*>\s*\/\/static([\s\S]*?)<\/script>/i,'<script type="text/javascript" src="<?php echo $this->url("'+path+'")?>"></script>');
        if(js.indexOf('<?')!==-1){
            console.log('jsExtract fail');
            process.exit;
        }
    }else{
        js=false;
    }
    return [content,js];
}