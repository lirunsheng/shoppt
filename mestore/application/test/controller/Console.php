<?php
namespace app\store\controller;

class Console extends Common
{
    public function index()
    {
        $ret=db('order')->order('id desc')->limit(4)->select();
        $this->assign('order',$ret);
        return view("console");
    }

    public function select(){
    	$barcode = input('post.barcode');
    	$ret = db('commodity')->where('barcode',$barcode)->select();
    	$this->assign('barcode',$ret);
    	$html=$this->fetch('get_commodity');
    	return $html;
    }

    public function order(){
    	$info=input("post.info");
    	$delinfo=input("post.delinfo");
    	$total=input("post.total");
    	$number=$_POST['num'];
    	$infoarray = explode("\r\n",$info);
    	$delarray = explode("\r\n",$delinfo);
    	array_pop($infoarray);
    	array_pop($delarray);
    	$infoarray=array_diff($infoarray, $delarray);
    	$totalinfo=array_combine($infoarray,$number);
    	$totalinfo=json_encode($totalinfo,JSON_UNESCAPED_UNICODE);
    	$data=[
    		'order_info'     =>     $totalinfo,
    		'order_total'    =>     $total,
    		'addtime'  =>     time(),
    		'user_id'  =>     "",
    		'store_id' =>     session("store_id"),
            'status'   =>     '未完成'

    	];
    	$ret=db('order')->insert($data);
    	print_r($ret);
    	header("Location:http://www.mestore-alipay.org/?total={$total}");
    }
}
