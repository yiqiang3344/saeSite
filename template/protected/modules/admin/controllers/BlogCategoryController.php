<?php

class BlogCategoryController extends Controller{

    public function actionIndex() {
        #input
        #start

        $params = array(
            'aList' => modelsToArray(MBlogCategory::model()->with('blogCount')->findAll(array('order'=>'t.sort asc,t.recordTime desc'))),
        );
        END:
        $bind = array(
            'params' => $params,
        );
        $this->render('index',$bind);
    }
    ///////////////////////////////////////

    public function actionAjaxAdd(){
        #input
        $name = getInput('name','str',array('length'=>array('min'=>1,'max'=>'128')));
        #start
        $code = 1;

        list($code,$errors) = MBlogCategory::model()->create(array(
            'name'=>$name,
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
        $sort = getInput('sort','int');
        $name = getInput('name','str',array('length'=>array('min'=>1,'max'=>'128')));
        #start
        $code = 1;

        MBlogCategory::model()->updateByPk($id,array(
            'name'=>$name,
            'sort'=>$sort,
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

        MBlogCategory::model()->updateByPk($id,array(
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

        MBlogCategory::model()->updateByPk($id,array(
            'deleteFlag' => 0
        ));

        END:
        $bind = array(
            'code' => $code,
        );
        $this->render($bind);
    }

    public function actionAjaxSort(){
        #input
        $ids = getInput('ids','str');
        #start
        $code = 1;
        $when = array();
        foreach(xexplode(',',$ids) as $sort=>$id){
            $when[] = 'when '.$id.' then '.($sort+1);
        }
        $when = implode(' ',$when);
        $sql = "UPDATE blogCategory SET sort = CASE blogCategoryId $when END WHERE blogCategoryId IN ($ids)";
        Yii::app()->db->createCommand($sql)->execute();

        END:
        $bind = array(
            'code' => $code,
        );
        $this->render($bind);
    }
}
