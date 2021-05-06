<?php


namespace app\controller;
use think\Controller;
use think\facade\Session;
use think\request;


class Base extends Controller
{

    //判断用户未登录
    protected function isLogin()
    {
        define('USER_NAME', Session('username'));
        if (empty(USER_NAME)) {
            $this->redirect('Admin/login');
        }
    }

}