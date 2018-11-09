<?php
namespace app\store\controller;

class Addfeedback extends \think\Controller
{
	public function add(){


		$order_id=input('get.order_id');
		$flag1=input('get.flag1');
		$flag2=input('get.flag2');
		$flag3=input('get.flag3');
		$content=input('get.content');
		$flag=[$flag1,$flag2,$flag3];
		$flag=json_encode($flag);
		// dump($flag);
		$data=[
			'feedbacktype'=>$flag,
			'feedback'=>$content,
			'status'=>"已完成"
		];
		
		$res=db('order')->where('id',$order_id)->update($data);
			 
		if(!$res){
			return json_encode(false);
		}
		else
			return json_encode(true);
	}
}