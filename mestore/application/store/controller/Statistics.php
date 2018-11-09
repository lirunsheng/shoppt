<?php
namespace app\store\controller;

class Statistics extends Common
{
    public function index()
    {
        $beginThismonth=mktime(0,0,0,date('m'),1,date('Y'));
        $endThismonth=mktime(23,59,59,date('m'),date('t'),date('Y'));
        $ret1=db('order')->where('addtime between '.$beginThismonth.' and '.$endThismonth)->where('status','in',['待评价','已完成'])->where('store_id',session('store_id'))->sum('order_total');
        $ret2=db('order')->where('addtime between '.$beginThismonth.' and '.$endThismonth)->where('status','in',['待评价','已完成'])->where('store_id',session('store_id'))->count();
        $ret=[];
        array_push($ret,$ret1,$ret2);
        $this->assign('ret',$ret);
        return view("statistics");
    }

    public function online(){
        $time=date('Y-m-d',time());
        $time=strtotime($time)+86400;
        $icount7=db('order')->where('isonline',1)->where('addtime between '.($time-86400).' and '.$time)->where('status','in',['待评价','已完成'])->where('store_id',session('store_id'))->count();
        $icount6=db('order')->where('isonline',1)->where('addtime between '.($time-86400*2).' and '.($time-86400))->where('status','in',['待评价','已完成'])->where('store_id',session('store_id'))->count();
        $icount5=db('order')->where('isonline',1)->where('addtime between '.($time-86400*3).' and '.($time-86400*2))->where('status','in',['待评价','已完成'])->where('store_id',session('store_id'))->count();
        $icount4=db('order')->where('isonline',1)->where('addtime between '.($time-86400*4).' and '.($time-86400*3))->where('status','in',['待评价','已完成'])->where('store_id',session('store_id'))->count();
        $icount3=db('order')->where('isonline',1)->where('addtime between '.($time-86400*5).' and '.($time-86400*4))->where('status','in',['待评价','已完成'])->where('store_id',session('store_id'))->count();
        $icount2=db('order')->where('isonline',1)->where('addtime between '.($time-86400*6).' and '.($time-86400*5))->where('status','in',['待评价','已完成'])->where('store_id',session('store_id'))->count();
        $icount1=db('order')->where('isonline',1)->where('addtime between '.($time-86400*7).' and '.($time-86400*6))->where('status','in',['待评价','已完成'])->where('store_id',session('store_id'))->count();
        $online=[];
        array_push($online,$icount1,$icount2,$icount3,$icount4,$icount5,$icount6,$icount7);
        // $online=json_encode($online,JSON_UNESCAPED_UNICODE);
        return $online;
    }

    public function unonline(){
        $time=date('Y-m-d',time());
        $time=strtotime($time)+86400;
        $ucount7=db('order')->where('isonline',0)->where('addtime between '.($time-86400).' and '.$time)->where('status','已完成')->where('store_id',session('store_id'))->count();
        $ucount6=db('order')->where('isonline',0)->where('addtime between '.($time-86400*2).' and '.($time-86400))->where('status','已完成')->where('store_id',session('store_id'))->count();
        $ucount5=db('order')->where('isonline',0)->where('addtime between '.($time-86400*3).' and '.($time-86400*2))->where('status','已完成')->where('store_id',session('store_id'))->count();
        $ucount4=db('order')->where('isonline',0)->where('addtime between '.($time-86400*4).' and '.($time-86400*3))->where('status','已完成')->where('store_id',session('store_id'))->count();
        $ucount3=db('order')->where('isonline',0)->where('addtime between '.($time-86400*5).' and '.($time-86400*4))->where('status','已完成')->where('store_id',session('store_id'))->count();
        $ucount2=db('order')->where('isonline',0)->where('addtime between '.($time-86400*6).' and '.($time-86400*5))->where('status','已完成')->where('store_id',session('store_id'))->count();
        $ucount1=db('order')->where('isonline',0)->where('addtime between '.($time-86400*7).' and '.($time-86400*6))->where('status','已完成')->where('store_id',session('store_id'))->count();
        $unonline=[];
        array_push($unonline,$ucount1,$ucount2,$ucount3,$ucount4,$ucount5,$ucount6,$ucount7);
        // $unonline=json_encode($unonline,JSON_UNESCAPED_UNICODE);
        return $unonline;
    }

    public function total(){
        $time=date('Y-m-d',time());
        $time=strtotime($time)+86400;
        $count7=db('order')->where('addtime between '.($time-86400).' and '.$time)->where('status','in',['待评价','已完成'])->where('store_id',session('store_id'))->count();
        $count6=db('order')->where('addtime between '.($time-86400*2).' and '.($time-86400))->where('status','in',['待评价','已完成'])->where('store_id',session('store_id'))->count();
        $count5=db('order')->where('addtime between '.($time-86400*3).' and '.($time-86400*2))->where('status','in',['待评价','已完成'])->where('store_id',session('store_id'))->count();
        $count4=db('order')->where('addtime between '.($time-86400*4).' and '.($time-86400*3))->where('status','in',['待评价','已完成'])->where('store_id',session('store_id'))->count();
        $count3=db('order')->where('addtime between '.($time-86400*5).' and '.($time-86400*4))->where('status','in',['待评价','已完成'])->where('store_id',session('store_id'))->count();
        $count2=db('order')->where('addtime between '.($time-86400*6).' and '.($time-86400*5))->where('status','in',['待评价','已完成'])->where('store_id',session('store_id'))->count();
        $count1=db('order')->where('addtime between '.($time-86400*7).' and '.($time-86400*6))->where('status','in',['待评价','已完成'])->where('store_id',session('store_id'))->count();
        $total=[];
        array_push($total,$count1,$count2,$count3,$count4,$count5,$count6,$count7);
        // $total=json_encode($total,JSON_UNESCAPED_UNICODE);
        return $total;
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

    public function onlinevo(){
        $time=date('Y-m-d',time());
        $time=strtotime($time)+86400;
        $icount7=db('order')->where('isonline',1)->where('addtime between '.($time-86400).' and '.$time)->where('status','in',['待评价','已完成'])->where('store_id',session('store_id'))->sum('order_total');
        $icount6=db('order')->where('isonline',1)->where('addtime between '.($time-86400*2).' and '.($time-86400))->where('status','in',['待评价','已完成'])->where('store_id',session('store_id'))->sum('order_total');
        $icount5=db('order')->where('isonline',1)->where('addtime between '.($time-86400*3).' and '.($time-86400*2))->where('status','in',['待评价','已完成'])->where('store_id',session('store_id'))->sum('order_total');
        $icount4=db('order')->where('isonline',1)->where('addtime between '.($time-86400*4).' and '.($time-86400*3))->where('status','in',['待评价','已完成'])->where('store_id',session('store_id'))->sum('order_total');
        $icount3=db('order')->where('isonline',1)->where('addtime between '.($time-86400*5).' and '.($time-86400*4))->where('status','in',['待评价','已完成'])->where('store_id',session('store_id'))->sum('order_total');
        $icount2=db('order')->where('isonline',1)->where('addtime between '.($time-86400*6).' and '.($time-86400*5))->where('status','in',['待评价','已完成'])->where('store_id',session('store_id'))->sum('order_total');
        $icount1=db('order')->where('isonline',1)->where('addtime between '.($time-86400*7).' and '.($time-86400*6))->where('status','in',['待评价','已完成'])->where('store_id',session('store_id'))->sum('order_total');
        $onlinevo=[];
        array_push($onlinevo,$icount1,$icount2,$icount3,$icount4,$icount5,$icount6,$icount7);
        // $online=json_encode($online,JSON_UNESCAPED_UNICODE);
        return $onlinevo;
    }
    public function unonlinevo(){
        $time=date('Y-m-d',time());
        $time=strtotime($time)+86400;
        $ucount7=db('order')->where('isonline',0)->where('addtime between '.($time-86400).' and '.$time)->where('status','已完成')->where('store_id',session('store_id'))->sum('order_total');
        $ucount6=db('order')->where('isonline',0)->where('addtime between '.($time-86400*2).' and '.($time-86400))->where('status','已完成')->where('store_id',session('store_id'))->sum('order_total');
        $ucount5=db('order')->where('isonline',0)->where('addtime between '.($time-86400*3).' and '.($time-86400*2))->where('status','已完成')->where('store_id',session('store_id'))->sum('order_total');
        $ucount4=db('order')->where('isonline',0)->where('addtime between '.($time-86400*4).' and '.($time-86400*3))->where('status','已完成')->where('store_id',session('store_id'))->sum('order_total');
        $ucount3=db('order')->where('isonline',0)->where('addtime between '.($time-86400*5).' and '.($time-86400*4))->where('status','已完成')->where('store_id',session('store_id'))->sum('order_total');
        $ucount2=db('order')->where('isonline',0)->where('addtime between '.($time-86400*6).' and '.($time-86400*5))->where('status','已完成')->where('store_id',session('store_id'))->sum('order_total');
        $ucount1=db('order')->where('isonline',0)->where('addtime between '.($time-86400*7).' and '.($time-86400*6))->where('status','已完成')->where('store_id',session('store_id'))->sum('order_total');
        $unonlinevo=[];
        array_push($unonlinevo,$ucount1,$ucount2,$ucount3,$ucount4,$ucount5,$ucount6,$ucount7);
        // $online=json_encode($online,JSON_UNESCAPED_UNICODE);
        return $unonlinevo;
    }

    public function totalvo(){
        $time=date('Y-m-d',time());
        $time=strtotime($time)+86400;
        $total7=db('order')->where('addtime between '.($time-86400).' and '.$time)->where('status','in',['待评价','已完成'])->where('store_id',session('store_id'))->sum('order_total');
        $total6=db('order')->where('addtime between '.($time-86400*2).' and '.($time-86400))->where('status','in',['待评价','已完成'])->where('store_id',session('store_id'))->sum('order_total');
        $total5=db('order')->where('addtime between '.($time-86400*3).' and '.($time-86400*2))->where('status','in',['待评价','已完成'])->where('store_id',session('store_id'))->sum('order_total');
        $total4=db('order')->where('addtime between '.($time-86400*4).' and '.($time-86400*3))->where('status','in',['待评价','已完成'])->where('store_id',session('store_id'))->sum('order_total');
        $total3=db('order')->where('addtime between '.($time-86400*5).' and '.($time-86400*4))->where('status','in',['待评价','已完成'])->where('store_id',session('store_id'))->sum('order_total');
        $total2=db('order')->where('addtime between '.($time-86400*6).' and '.($time-86400*5))->where('status','in',['待评价','已完成'])->where('store_id',session('store_id'))->sum('order_total');
        $total1=db('order')->where('addtime between '.($time-86400*7).' and '.($time-86400*6))->where('status','in',['待评价','已完成'])->where('store_id',session('store_id'))->sum('order_total');
        $total=[];
        array_push($total,$total1,$total2,$total3,$total4,$total5,$total6,$total7);
        // $total=json_encode($total,JSON_UNESCAPED_UNICODE);
        return $total;
    }
}
