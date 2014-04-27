<?php

class MainController extends Controller{
    public function actionIndex() {
        #input
        $post = $_POST;
        #start
        $params = array(
        );
        END:
        $bind = array(
            'params' => $params,
        );
        $this->render('index',$bind);
    }

    public function actionBackup() {
        #input
        $search = @$_GET['search'];//搜索 attr:val
        $order_str = @$_GET['order'];//排序 type1:sc1,type2:sc2
        $p = max(intval(@$_GET['p']),1);//分页
        #start
        $this->checkSuperAdmin();
        $condition = '';
        if($search){
            $l = array();
            foreach(    xexplode(',', $search) as $v){
                $a = explode(':', $v);
                $l[] = $a[0].' like \'%'.$a[1].'%\'';
            }
            $condition .= 'and '.implode(' and ', $l);
        }
        $order = '';
        $orders = array();
        if($order_str){
            $l = array();
            foreach(    xexplode(',', $order_str) as $v){
                $a = explode(':', $v);
                $l[] = $a[0].' '.$a[1];
                $orders[$a[0]] = $a[1];
            }
            $order .= implode(' , ', $l);
        }
        $select = 'id,name,lastRebackTime,createTime';
        $params =  MBackup::getListByPage($select, $condition, $order, array(), $p, 10, false, true);
        $params['now'] = date('Ymdhis',    getTime());
        END:
        $bind = array(
            'params' => $params,
            'orders' => $orders
        );        
        $this->render('backup',$bind);
    }
}
