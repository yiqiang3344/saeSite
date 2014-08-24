<?php
class MainController extends Controller
{
    public function actionIndex(){
        #input

        #start
        $params = array(
            'blogList' => modelsToArray(MBlog::model('blogIndex')->with('blogCategory')->findAll(array(
                'limit'=>20,
                'order'=>'t.recordTime desc'
            )))
        );

        END:
        $bind = array(
            'params' => $params,
        );
        $this->renderJs('index', $bind);
    }

    public function actionTools(){
        #input

        #start
        $params = array(
        );

        END:
        $bind = array(
            'params' => $params,
        );
        $this->renderJs('tools', $bind);
    }

    public function actionTest(){
    }

    /************************************ajax*********************************/
    public function actionAjaxEncrypt(){
        #input
        $type = $_POST['type'];
        $source = $_POST['source'];
        #start

        $ret = '';
        if(in_array($type, array('md5','base64_encode','base64_decode','addslashes','stripslashes','htmlentities','html_entity_decode','json_encode','json_decode'))){
            if($type=='json_decode'){
                $ret = $type($source,true);
                $ret = print_r($ret,true);
            }elseif($type=='json_encode'){
                list($arr) = strToArr($source);
                $ret = $type($arr);
            }else{
                $ret = $type($source);
            }
        }
        $code = 1;
        END:
        $bind = array(
            'code'=>$code,
            'ret'=>$ret
        );
        $this->render($bind);
    }

    public function actionAjaxFormat(){
        #input
        $type = $_POST['type'];
        $source = $_POST['source'];
        #start

        $ret = '';
        if($type=='css'){
            $ret = FormatCss::format($source);
        }
        $ret === null and $ret = 'null';
        $code = 1;
        END:
        $bind = array(
            'code'=>$code,
            'ret'=>$ret
        );
        $this->render($bind);
    }

    public function actionAjaxCompress(){
        #input
        $type = $_POST['type'];
        $source = $_POST['source'];
        #start

        $ret = '';
        if($type=='css'){
            //删除所有换行
            $ret = preg_replace("{/\*[\s\S]*?\*/|\r\n}u", '', $source);
            //两个以上的空格全部换成一个
            $ret = preg_replace("/\\s+/u", ' ', $ret);
        }elseif($type=='js'){
            $ret = JSMin::minify($source);
        }
        $ret === null and $ret = 'null';
        $code = 1;
        END:
        $bind = array(
            'code'=>$code,
            'ret'=>$ret
        );
        $this->render($bind);
    }
}
