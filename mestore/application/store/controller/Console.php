<?php
namespace app\store\controller;

class Console extends Common
{
    public function index()
    {
        $ret=db('order')->where('isonline',1)->where('store_id',session('store_id'))->order('addtime desc')->limit(4)->select();
        $this->assign('order',$ret);
        return view("console");
    }

    public function selectorder(){
        $order_id=input('post.order_id');
        $ret=db('order')->where('id',$order_id)->where('store_id',session('store_id'))->find();
        $key=json_decode($ret['order_info'],true);
        $order_key=array_keys($key);
        $name=[];
        $price=[];
        foreach ($order_key as $value) {
            $searchcom=db('commodity')->where('goods_name',$value)->where('store_id',session('store_id'))->find();
            array_push($name,$searchcom['goods_name']);
            array_push($price,$searchcom['goods_price']);
        }
        $com=array_combine($name,$price);
        array_push($ret,$com);
        return $ret;
        // $ret=json_encode($ret,JSON_UNESCAPED_UNICODE);
        // return $ret;
    }

    public function select(){
    	$barcode = input('post.barcode');
    	$ret = db('commodity')->where('barcode',$barcode)->where('pass',1)->where('store_id',session('store_id'))->select();
    	$this->assign('barcode',$ret);
    	$html=$this->fetch('get_commodity');
    	return $html;
    }

    public function order(){
        $order_id=date('YmdHis',time());
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
        $time = time();
    	$data=[
            'id'             =>     $order_id,
    		'order_info'     =>     $totalinfo,
    		'order_total'    =>     $total,
    		'addtime'  =>     time(),
    		'user_id'  =>     "",
    		'store_id' =>     session("store_id"),
            'feedbacktype'   =>     0,
            'status'   =>     '未完成'
    	];

        for ($i=0; $i <count($infoarray) ; $i++) { 
            $goods_name=$infoarray[$i];
            $find=db("commodity")->where('goods_name',$goods_name)->field('goods_stork')->find();
             $update=[
                'goods_stork'=>  $find['goods_stork']-$number[$i]
            ];
            $updatestork=db('commodity')->where("goods_name",$goods_name)->update($update);
        }
    	$ret=db('order')->insert($data);
    	// dump($update);
    	header("Location:https://huazai233.picp.vip/store/pay?total={$total}&id={$order_id}");
        // header("Location:http://www.mestore.org/pay.php");

    }

    public function send(){
        // $time=strtotime(date('Y-m-d'));
        // $nexttime=$time+86400;
        $ret=db('order')->where('isonline',1)->where("status='待发货' or status='待收货'")->where('store_id',session('store_id'))->order('status asc, id desc')->paginate(8);
        $page=$ret->render();
        $this->assign('time',date("Y-m-d"));
        $this->assign('order',$ret);
        $this->assign('page',$page);
        return view('send');
    }

    public function searchorder(){
        $date=input('post.date');
        $date=strtotime($date);
        $nextdate=$date+86400;
        $ret=db('order')->where("isonline=1")->where('addtime between '.$date.' and '.$nextdate)->where('store_id',session('store_id'))->paginate(8);
        $page=$ret->render();
        $this->assign('order',$ret);
        $this->assign('page',$page);
        $html=$this->fetch('send_list');
        return $html;
    }

    public function sendenter(){
        $order_id=input('get.order_id');
        $update=[
            'status'=>'待收货'
        ];
        $ret=db('order')->where('id',$order_id)->where('store_id',session('store_id'))->update($update);
        header('Location:https://huazai233.picp.vip/store/console/send');
    }
}
