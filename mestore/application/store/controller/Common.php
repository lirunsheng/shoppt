<?php
namespace app\store\controller;

class Common extends \think\Controller
{
	//初始化方法 相当于__construct()
	public function _initialize()
    {
        //验证登录代码
        if ( session('store_id') == NULL )
        {
        	$this->error("未登录",'/store/login/index');
        }
    }

    public function destroy(){
        session("store_id",null);
        $this->success("注销成功",'/store/login/index');
    }
}
