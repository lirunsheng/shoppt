<?php
namespace app\store\controller;

class Conforder extends \think\Controller
{
	public function confirm(){
		$id=input('get.id');
		$res=db('order')->where('id',$id)->setField('status',"待评价");
		if(!$res){
			return json_encode(false);
		}
		else
			return json_encode(true);
	}
}