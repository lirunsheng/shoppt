<?php
namespace app\store\controller;

class Payorder extends \think\Controller
{
	public function pay(){
		$id=input('get.id');
		$res=db('order')->where('id',$id)->setField('status',"待发货");
		if(!$res){
			return json_encode(false);
		}
		else
			return json_encode(true);
	}
}