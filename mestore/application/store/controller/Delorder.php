<?php
namespace app\store\controller;

class Delorder extends \think\Controller
{
	public function del(){
		$id=input('get.id');
		$res=db('order')->where('id',$id)->delete();
		if(!$res){
			return json_encode(false);
		}
		else
			return json_encode(true);
	}
}