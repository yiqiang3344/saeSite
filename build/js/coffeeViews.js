var 
    hogan = require('hogan.js')
,   fs = require('fs')
,   helper = require('./helper')
,   process = require('child_process')
,   langList = [
    ]
;

//处理公共子模板
var 
    name
,   ss = ''
,   fd = fs.openSync('dev/js/'+'helper.js', 'a+');
;
helper.scanDir('protected/views/_public/',true).forEach(function(file){
    if(helper.isFile(file)){
        name = helper.baseName(file,true);
        ss += '\nvar '+name+'=new Hogan.Template();'+name+'.r ='+hogan.compile(fs.readFileSync(file,'utf8'),{asString:true})+';';
    }
});
fs.writeSync(fd, ss);
fs.closeSync(fd);

//将views中所有coffee文件处理成dev/js/views中的对应js文件
helper.rm('dev/js/views');
helper.mkdir('dev/js/views');
var made_js_map = {};
helper.scanDir('protected/views').forEach(function(dir){
    dirName = helper.baseName(dir);
    if(!helper.isDir(dir) || dirName=='layouts' || dirName=='_public'){
        return;
    }
    //局部子模板
    fs.writeFileSync('dev/js/views/'+dirName+'.js', '');
    helper.scanDir(dir).forEach(function(file){
        var 
            basename = helper.baseName(file,true)
        ,   name = helper.baseName(file,false)
        ,   ext = name.substring(name.lastIndexOf('.')+1)
        ;
        if(!helper.isFile(file) || basename.indexOf('_')!=0 || ext!='mustache'){
            return;
        }
        name = basename.substring(1);
        ss = 'var '+name+'=new Hogan.Template();'+name+'.r ='+hogan.compile(fs.readFileSync(file,'utf8'),{asString:true})+';';
        if(!made_js_map[dirName]){
            mode = 'w+';//覆盖新建方式写文件
        }else{
            made_js_map[dirName] = true;
            mode = 'a+';//添加新建方式写文件
        }
        fd = fs.openSync('dev/js/views/'+dirName+'.js', mode);
        fs.writeSync(fd, ss);
        fs.closeSync(fd);
    });
    var urlDir = 'dev/js/views/'+dirName;
    helper.mkdir(urlDir);
    process.exec('coffee --output '+urlDir+'  --compile '+dir,function(){
        helper.scanDir(dir).forEach(function(file){
            if(!helper.isFile(file) || file.substring(file.lastIndexOf('.')+1)!='coffee'){
                return;
            }
            dealFile(file,urlDir+'/'+helper.baseName(file,true)+'.js');
        });
    });
});

function dealFile(file,url){
    if(!isFile(url)){
        console.log('coffee fail:'+file);
        return;
    }
    
    var 
        ss = fs.readFileSync(url,'utf8')
    ,   fd = fs.openSync(url, 'w+')
    ;
    //编译试图模板
    ss = 'var oTemplate=new Hogan.Template();oTemplate.r ='+hogan.compile(fs.readFileSync(file.replace('.coffee','.mustache'),'utf8'),{asString:true})+';\n'+ss;
    fs.writeSync(fd, ss);
    fs.closeSync(fd);
}
