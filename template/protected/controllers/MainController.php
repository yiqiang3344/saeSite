<?php
class MainController extends Controller
{
    public function actionIndex(){
        #input

        #start
        $params = array(
            'test' => 'test',
        );

        END:
        $bind = array(
            'params' => $params,
        );
        $this->renderJs('index', $bind);
    }

    public function actionTest(){
        echo getUrl('js/views/'.$this->getId().'/'.$this->getAction()->id.'.js');
    }
}
