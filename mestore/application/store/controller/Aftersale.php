<?php
namespace app\store\controller;

class Aftersale extends \think\Controller
{
	public function add(){

		$store_id=input('get.store_id');
		$goodstatus=input('get.goodstatus');
		$reason=input('get.reason');
		$note=input('get.note');
		$order_id=input('get.order_id');
	
		$data=[
			'store_id'=>$store_id,
			'message_title'=>"来自订单".$order_id."的售后消息",
			'message'=>'订单号：'.$order_id."<br>货物状态：".$goodstatus."<br>退款原因：".$reason."<br>补充说明:".$note,
			'isread'=>0,
			'addtime'=>time()
		];
		$res=db('storemessage')->insert($data);
		$ret=db('order')->where('id',$order_id)->setField('status', "售后中");
			 
		if(!$res&&!$ret){
			return json_encode(false);
		}
		else
			return json_encode(true);
	}
}