<?php
namespace app\store\controller;

class Message extends Common
{
    public function index()
    {
    	$ret=db('storemessage')->where('store_id',session('store_id'))->order('addtime desc')->select();
    	$this->assign('message',$ret);
        return view("message");
    }  
    public function isread(){
    	$id=input('post.id');
    	$update=[
    		'isread'  =>  1
    	];
    	$ret=db('storemessage')->where('id',$id)->update($update);
    	return $ret;
    }
}
