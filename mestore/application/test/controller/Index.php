<?php
namespace app\store\controller;

class Index extends Common
{
    public function index()
    {
        $ret=db('store')->where('id',session("store_id"))->find();
        $this->assign('store_id',$ret);
        return view("index");
    }

}
