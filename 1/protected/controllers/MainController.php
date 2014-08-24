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

    public function actionTest(){
        echo getUrl('js/views/'.$this->getId().'/'.$this->getActionId().'.js');
    }
}
