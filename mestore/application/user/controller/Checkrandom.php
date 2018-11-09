<?php
namespace app\user\controller;
use \think\Validate;
class Checkrandom extends \think\Controller
{
public function index()
{
    	$id=input('get.id');
        $tell=input('get.tell');
    	$res=db('user')->where("id={$id}")->setField("tell","{$tell}");
    	if($res){
        $msg["status"]=true;
        }
        else{$msg["status"]=false;}
    	return json_encode($msg);
}
}