<?php

class BlogController extends Controller{

    public function actionIndex() {
        #input
        #start

        $params = array(
            'aList' => modelsToArray(MBlog::model('blogIndex')->findAll(array('limit'=>20,'order'=>'recordTime desc'))),
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

        $params = MBlog::model('blogIndex')->findByPk($id)->toArray();
        $this->higthlightContent = $params['content'];
        $this->layout = 'main_blog';
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
            'blog' => MBlog::model('blogIndex')->findByPk($id)->toArray()
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
        $title = getInput('title','str',array('length'=>array('min'=>1,'max'=>'128')));
        $content = getInput('content','str',array('length'=>array('min'=>1)));
        #start
        $code = 1;

        MBlog::model()->updateByPk($id,array(
            'title'=>$title,
            'content'=>$content
        ));

        END:
        $bind = array(
            'code' => $code,
        );
        $this->render($bind);
    }
}
