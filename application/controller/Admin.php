<?php
namespace app\controller;

use think\Db;

class Admin extends Base
{
    //空操作（admin模块下错误地址）
    public function _empty()
    {
        $this->redirect('Admin/login');
    }
    public function login()
    {
        return $this->view->fetch();
    }

    public function upstate()
    {

        $this->isLogin();
        return $this->view->fetch();
    }
    public function uptype()
    {
        $this->isLogin();
        return $this->view->fetch();
    }
    public function upsite()
    {
        $this->isLogin();
//        弹出层下拉选择框赋值
        $type = Db::table('type')->field(['type_id', 'type_name'])->distinct(true)->order('type_id')->select();
        $area = Db::table('state')->distinct(true)->field('state_area')->select();
        $state = Db::table('state')->distinct(true)->field('state_name')->select();
        $this->assign(['area'=>$area,'state'=>$state,'type'=>$type]);

        return $this->view->fetch();
    }



}