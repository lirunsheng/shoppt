<?php
namespace app\admin\controller;

class Login extends \think\Controller
{

    public function index()
    {
        return view("login");
    }

    public function check()
    {
    	$admin = input('post.admin');
    	$pwd = input('post.pwd');
        $pwd=md5($pwd);
        $addtime = time();

    	$ret = db('admin')->where("admin='{$admin}' and pwd ='{$pwd}'")->find();
        
    	if ( empty($ret) )
    	{
    		$this->error("账号密码不匹配");
    	}

    	//存session
    	session('admin_id', $ret['id']);
    	// session('name', $ret['name']);
    	// session('head_img', $ret['head_img']);
    	// $this->success("登录成功", '/admin/index/index');
        header("location:https://huazai233.picp.vip/admin/index");

    }


}
