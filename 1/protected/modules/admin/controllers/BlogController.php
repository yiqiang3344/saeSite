<?php

class BlogController extends Controller{

    public function actionIndex() {
        #input
        $blogCategoryId = getInput('blogCategoryId','int',array('default'=>0));
        #start
        $condition = '';
        if($blogCategoryId!==0){
            $condition = 't.blogCategoryId='.$blogCategoryId;
        }

        $params = array(
            'aList' => modelsToArray(MBlog::model('adminBlogIndex')->with('blogCategory')->findAll(array(
                'condition' => $condition,
                'limit'=>20,
                'order'=>'t.recordTime desc'
            ))),
        );
        END:
        $bind = array(
            'params' => $params,
        );
        $this->render('index',$bind);
    }

    public function actionBlog() {
        #input
        $id = getInput('id','int');
        #start

        $params = MBlog::model('adminBlogIndex')->with('blogCategory')->findByPk($id)->toArray();
        $this->higthlightContent = $params['content'];
        END:
        $bind = array(
            'params' => $params,
        );
        $this->render('blog',$bind);
    }

    public function actionAdd() {
        #input
        #start

        $params = array(
            'blogCategoryList' => modelsToArray(MBlogCategory::model()->findAllByAttributes(array('deleteFlag'=>0),array('order'=>'recordTime desc')))
        );
        END:
        $bind = array(
            'params' => $params,
        );
        $this->render('add',$bind);
    }

    public function actionEdit() {
        #input
        $id = getInput('id','int');
        #start

        $params = array(
            'blog' => MBlog::model('adminBlogIndex')->with('blogCategory')->findByPk($id)->toArray(),
            'blogCategoryList' => modelsToArray(MBlogCategory::model()->findAllByAttributes(array('deleteFlag'=>0),array('order'=>'t.sort asc,t.recordTime desc')))
        );
        END:
        $bind = array(
            'params' => $params,
        );
        $this->render('Edit',$bind);
    }

    ///////////////////////////////////////

    public function actionAjaxAdd(){
        #input
        $title = getInput('title','str',array('length'=>array('min'=>1,'max'=>'128')));
        $content = getInput('content','str',array('length'=>array('min'=>1)));
        #start
        $code = 1;


        list($code,$errors) = MBlog::model()->create(array(
            'title'=>$title,
            'content'=>$content,
        ));
        END:
        $bind = array(
            'code' => $code,
            'errors' => $errors,
        );
        $this->render($bind);
    }

    public function actionAjaxEdit(){
        #input
        $id = getInput('id','int');
        $blogCategoryId = getInput('blogCategoryId','int');
        $title = getInput('title','str',array('length'=>array('min'=>1,'max'=>'128')));
        $content = getInput('content','str',array('length'=>array('min'=>1)));
        #start
        $code = 1;

        MBlog::model()->updateByPk($id,array(
            'blogCategoryId'=>$blogCategoryId,
            'title'=>$title,
            'content'=>$content
        ));

        END:
        $bind = array(
            'code' => $code,
        );
        $this->render($bind);
    }

    public function actionAjaxDelete(){
        #input
        $id = getInput('id','int');
        #start
        $code = 1;

        MBlog::model()->updateByPk($id,array(
            'deleteFlag' => 1
        ));

        END:
        $bind = array(
            'code' => $code,
        );
        $this->render($bind);
    }

    public function actionAjaxRecover(){
        #input
        $id = getInput('id','int');
        #start
        $code = 1;

        MBlog::model()->updateByPk($id,array(
            'deleteFlag' => 0
        ));

        END:
        $bind = array(
            'code' => $code,
        );
        $this->render($bind);
    }
}
