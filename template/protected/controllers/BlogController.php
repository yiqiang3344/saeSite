<?php
class BlogController extends Controller
{
    public function actionIndex(){
        #input
        $blogCategoryId = getInput('blogCategoryId','int',array('default'=>0));
        #start
        $condition = '';
        if($blogCategoryId!==0){
            $condition = 't.blogCategoryId='.$blogCategoryId;
        }

        $params = array(
            'aList' => modelsToArray(MBlog::model('blogIndex')->with('blogCategory')->findAll(array(
                'condition' => $condition,
                'limit'=>20,
                'order'=>'t.recordTime desc'
            ))),
        );
        END:
        $bind = array(
            'params' => $params,
        );
        $this->renderJs('index', $bind);
    }

    public function actionBlog(){
        #input
        $id = getInput('id','int');
        #start
        if(!($blog = MBlog::model('blogIndex')->with('blogCategory')->findByPk($id))){
            throwException('博文不存在.',S::EXCEPTION_USE);
        }


        $params = $blog->toArray();
        $this->higthlightContent = $params['content'];
        END:
        $bind = array(
            'params' => $params,
        );
        $this->renderJs('index', $bind);
    }
}
