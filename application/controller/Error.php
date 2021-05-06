<?php


namespace app\controller;


class Error
{
    use \traits\controller\Jump;
    public function error()
    {
        $this->redirect('index/index');
    }
    public function _empty()
    {
        $this->redirect('index/index');
    }
}