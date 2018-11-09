<?php
namespace app\admin\controller;

class Form extends \think\Controller
{
    public function index()
    {
        return view("form");
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

    public function sum(){
        $time=date('Y-m-d',time());
        $time=strtotime($time)-86400;
        $count7=db('order')->where('addtime between '.($time-86400).' and '.$time)->count();
        $count6=db('order')->where('addtime between '.($time-86400*2).' and '.($time-86400))->count();
        $count5=db('order')->where('addtime between '.($time-86400*3).' and '.($time-86400*2))->count();
        $count4=db('order')->where('addtime between '.($time-86400*4).' and '.($time-86400*3))->count();
        $count3=db('order')->where('addtime between '.($time-86400*5).' and '.($time-86400*4))->count();
        $count2=db('order')->where('addtime between '.($time-86400*6).' and '.($time-86400*5))->count();
        $count1=db('order')->where('addtime between '.($time-86400*7).' and '.($time-86400*6))->count();
        $total=[];
        array_push($total,$count1,$count2,$count3,$count4,$count5,$count6,$count7);
        // $total=json_encode($total,JSON_UNESCAPED_UNICODE);
        return $total;
    }
}
