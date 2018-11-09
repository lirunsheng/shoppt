<?php
namespace app\admin\controller;
use \think\Db;

class Count extends \think\Controller
{
    public function index()
    {
        return view("count");
	}

	public function gettime(){
        $date=db('order')->order('addtime desc')->limit(1)->field('addtime')->find();
        $getdate7=date('m-d',$date['addtime']);
        $getdate6=date('m-d',$date['addtime']-86400);
        $getdate5=date('m-d',$date['addtime']-86400*2);
        $getdate4=date('m-d',$date['addtime']-86400*3);
        $getdate3=date('m-d',$date['addtime']-86400*4);
        $getdate2=date('m-d',$date['addtime']-86400*5);
        $getdate1=date('m-d',$date['addtime']-86400*6);
        $gettime=[];
        array_push($gettime,$getdate1,$getdate2,$getdate3,$getdate4,$getdate5,$getdate6,$getdate7);
        return $gettime;
    }

	public function store(){
        $time=date('Y-m-d',time());
        $time=strtotime($time)-86400;
        $store=[];
 
        $count7=db('order')->where('addtime between '.($time-86400).' and '.$time)->where('status','in',['待评价','已完成'])->count();
        $count6=db('order')->where('addtime between '.($time-86400*2).' and '.($time-86400))->where('status','in',['待评价','已完成'])->count();
        $count5=db('order')->where('addtime between '.($time-86400*3).' and '.($time-86400*2))->where('status','in',['待评价','已完成'])->count();
        $count4=db('order')->where('addtime between '.($time-86400*4).' and '.($time-86400*3))->where('status','in',['待评价','已完成'])->count();
        $count3=db('order')->where('addtime between '.($time-86400*5).' and '.($time-86400*4))->where('status','in',['待评价','已完成'])->count();
        $count2=db('order')->where('addtime between '.($time-86400*6).' and '.($time-86400*5))->where('status','in',['待评价','已完成'])->count();
        $count1=db('order')->where('addtime between '.($time-86400*7).' and '.($time-86400*6))->where('status','in',['待评价','已完成'])->count();
        
        array_push($store,$count1,$count2,$count3,$count4,$count5,$count6,$count7);
        // $online=json_encode($online,JSON_UNESCAPED_UNICODE);
        return $store;
    }

    public function sum(){
        $time=date('Y-m-d',time());
        $time=strtotime($time)-86400;
        $total7=json_encode(db('order')->where('addtime between '.($time-86400).' and '.$time)->where('status','in',['待评价','已完成'])->sum('order_total'));
        $total6=json_encode(db('order')->where('addtime between '.($time-86400*2).' and '.($time-86400))->where('status','in',['待评价','已完成'])->sum('order_total'));
        $total5=json_encode(db('order')->where('addtime between '.($time-86400*3).' and '.($time-86400*2))->where('status','in',['待评价','已完成'])->sum('order_total'));
        $total4=json_encode(db('order')->where('addtime between '.($time-86400*4).' and '.($time-86400*3))->where('status','in',['待评价','已完成'])->sum('order_total'));
        $total3=json_encode(db('order')->where('addtime between '.($time-86400*5).' and '.($time-86400*4))->where('status','in',['待评价','已完成'])->sum('order_total'));
        $total2=json_encode(db('order')->where('addtime between '.($time-86400*6).' and '.($time-86400*5))->where('status','in',['待评价','已完成'])->sum('order_total'));
        $total1=json_encode(db('order')->where('addtime between '.($time-86400*7).' and '.($time-86400*6))->where('status','in',['待评价','已完成'])->sum('order_total'));
        $total=[];
        array_push($total,$total1,$total2,$total3,$total4,$total5,$total6,$total7);
        // $total=json_encode($total,JSON_UNESCAPED_UNICODE);
        return $total;
    }
    

}
