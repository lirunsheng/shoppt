<?php
namespace app\store\controller;

class Order extends \think\Controller
{

    public function orderDowm(){   //下单页面
        // $menu = file_get_contents('php://input');//----------获取cart购物车信息
        // $val = json_decode($menu,true);  
        $list = input('get.list');
        $list = json_decode($list,true);      
        $info = $list['list'];                  //-----------订单列表
        $total = $list['order_total'];          //-----------订单总价
        $user_id = input('get.user_id');        //-----------电话号码即用户ID
        $store_id = $list['store_id'];          //-----------商户ID
 
        $remark=input('get.remark');            //----------备注信息
        $order_time = input('get.order_time');  //----------获取预定时间

        $name = input('get.name');              //----------联系方式以及地址
        $tell = input('get.tell');
        $area = input('get.area');
        $door = input('get.door');
        $address = [
            'name' => $name,
            'tell' => $tell,
            'area' => $area,
            'door' => $door
        ];
        $address = json_encode($address,JSON_UNESCAPED_UNICODE);

        $addtime = time();                      //----------添加时间
        $id = date("YmdHis",$addtime) ;         //----------订单号
       
        $isonline = 1;                          //----------线上完成为1
        $status = '待付款';                    //----------下单后的情况



        $info = json_encode($info,JSON_UNESCAPED_UNICODE);    //json格式保存订单信息  "商品名称"：数量  
       

        $data = [
            'id'            => $id,
            'order_info'    => $info,
            'order_total'   => $total,
            'order_time'    => $order_time,
            'addtime'       => $addtime,
            'user_id'       => $user_id,
            'store_id'      => $store_id,
            'isonline'      => $isonline,
            'status'        => $status,
            'order_address' => $address,
            'remark'        => $remark,
            'feedbacktype'  => 0
        ];

        // dump($data);
        // die;
        $ret =db('order')->insert($data);
        return json_encode($data);
    }

    public function pay() //支付成功
    {
        $id = input('get.id');
        $list = input('get.list');
        $list = json_decode($list,true);      
        $info = $list['list'];                  //-----------订单列表
        $total = $list['order_total'];          //-----------订单总价
        $user_id = input('get.user_id');        //-----------电话号码即用户ID
        $store_id = $list['store_id'];          //-----------商户ID
 
        $remark=input('get.remark');            //----------备注信息
        $order_time = input('get.order_time');  //----------获取预定时间

        $name = input('get.name');              //----------联系方式以及地址
        $tell = input('get.tell');
        $area = input('get.area');
        $door = input('get.door');
        $address = [
            'name' => $name,
            'tell' => $tell,
            'area' => $area,
            'door' => $door
        ];
        $address = json_encode($address,JSON_UNESCAPED_UNICODE);

        $addtime = time();                      //----------添加时间

       
        $isonline = 1;                          //----------线上完成为1
        $status = '待发货';                    //----------下单后的情况



        $info = json_encode($info,JSON_UNESCAPED_UNICODE);    //json格式保存订单信息  "商品名称"：数量  
       

        $data = [
            'order_info'    => $info,
            'order_total'   => $total,
            'order_time'    => $order_time,
            'addtime'       => $addtime,
            'user_id'       => $user_id,
            'store_id'      => $store_id,
            'isonline'      => $isonline,
            'status'        => $status,
            'order_address' => $address,
            'remark'        => $remark,
            'feedbacktype'  => 0
        ];

        // dump($data);
        // die;
        $ret =db('order')->where('id',$id)->update($data);
        return json_encode($data);


    }


	public function show(){   //展示订单   /page/order/order调用这个
		$user_id = $_GET['id'];
		$ret2 = db('order')->alias('a')->join('store b','a.store_id=b.id')->where('a.user_id',$user_id)->order('id desc')->field('a.*,b.store_name,b.file,b.store_tell')->select();
		// dump($ret2);
		$data=[];
		foreach ($ret2 as $key => $val) {   //order_info信息转为数组
			$d2=[];
			foreach ($val as $k => $v) {
				if($k=='order_info'){
				$va=json_decode($v,true);
				$v=$va;
				}
				$d2[$k]=$v;
			}
			$data[$key]=$d2;		
		}

		// dump($ret2);
		// dump($data);
		// die;

		return json_encode($data);
	}

	public function addAddress(){   //添加用户定义的收货地址
		$door = $_GET['door'];
		$tell = $_GET['tell'];
		$name = $_GET['name'];
		$area = $_GET['area'];
		$id = $_GET['id'];
		$data =[
				'door'=>$door,
				'tell'=>$tell,
				'name'=>$name,
				'area'=>$area,
		] ;


		$ret1 = db('user')->where('id',$id)->field('address')->find();
		$ret2 = $ret1['address'];
		dump($ret1);
		if($ret2==""){
			$ret2=[];
		}else{
			$ret2 = json_decode($ret2,true);
		}
		array_push($ret2, $data);
		// dump($ret2);
		$data1 = json_encode($ret2,JSON_UNESCAPED_UNICODE);
		// $update=[
		// 	'address'  =>  $data1
		// ];

		$ret = db('user')->where('id',$id)->setField('address',$data1);
		dump($data1);

	}

	public function showAddress(){   //展示用户收货信息
		$openid = $_GET['openid'];
		$ret1 = db('user')->where('openid',$openid)->field('address')->find();
		$ret2 = $ret1['address'];
		// dump($ret2);
		return $ret2;
	}

    public function modifyAddress(){   //修改地址
    	$id = input('get.id');
    	$door = input('get.door');
    	$area = input('get.area');
    	$tell = input('get.tell');
    	$name = input('get.name');
    	$index = input('get.index');

    	$ret1 = db('user')->where("id",$id)->field('address')->find();
    	$ret1 = json_decode($ret1['address'],true);
    	$change =$ret1[$index];
    	$change['door'] = $door;
    	$change['tell'] = $tell;
    	$change['name'] = $name;
    	$change['area'] = $area;
    	$ch[] = $change;
    	array_splice($ret1,$index,1,$ch);

    	$ret4 = json_encode($ret1,JSON_UNESCAPED_UNICODE);
        // dump($mytell);
        // die;
    	// $update=['address' => $ret4];
    	$r =db('user')->where("id={$id}")->setField('address',$ret4);
    	// dump($r);
    	// die;
    	return 1;

    }

    public function deleteAddress(){
        $id = input('get.id');
        $index = input('get.index');
        $ret1 = db('user')->where("id",$id)->field('address')->find();
        $ret1 = json_decode($ret1['address'],true);
        dump($ret1);
        array_splice($ret1,$index,1);
        $ret4 = json_encode($ret1,JSON_UNESCAPED_UNICODE);
        $r =db('user')->where("id={$id}")->setField('address',$ret4);
    }
}