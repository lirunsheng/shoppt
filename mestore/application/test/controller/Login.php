<?php
namespace app\store\controller;

class Login extends \think\Controller
{
    public function index()
    {
        return view("login");
    }

    public function islogin(){
        $tell=input('post.store_tell');
        $rec=db('store')->where('store_tell',$tell)->find();
        $status=$rec['status'];
        echo $status;
    }
    public function isloginpwd(){
        $tell=input('post.store_tell');
        $rec=db('store')->where('store_tell',$tell)->find();
        $pwd=$rec['pwd'];
        echo $pwd;
    }

    public function login(){
    	$tell=input('post.store_tell');
    	$password=input('post.password');
    	$rec=db('store')->where('store_tell',$tell)->find();
        $this->assign('store_id',$rec);
    	if($tell==$rec['store_tell']){
    		if(md5($password)==$rec['pwd']){
    			session('store_id',$rec['id']);
                if($rec['status']==0){
                    $this->error("你的店铺还未通过审核！请等待审核完毕后再登录！");
                }
    			else return view("/index/index");
    		}
    		else $this->error('密码错误！');
    	}
    	else $this->error('用户名不存在！');
    }
}
