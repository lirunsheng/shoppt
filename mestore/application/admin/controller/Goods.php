<?php
namespace app\admin\controller;

class Goods extends \think\Controller
{
    public function index()
    {
    	$store_id = input('get.id');
    	
    	$result = db('commodity')->where("store_id={$store_id} ")->order('id desc')->paginate(4,false,[ 'query' => request()->param()]);
    	$pageHtml = $result->render();

    	$this->assign('result',$result);
    	$this->assign('pageHtml',$pageHtml);


    	return view("goods");
    }

//列出详细信息
    public function alist()
    {
    	$id = input('get.id');

    	$result = db('commodity')->where("id",$id)->find();
    	$this->assign('result',$result);

    	$file=json_decode($result['goods_file']);
        $this->assign("file",$file);

    	return view("alist");
    }

//审核信息
    public function insert()
    {
    	$id = input('get.id');
 
        $result = db('commodity')->where("id={$id}")->find();  	
        $pass = $result['pass'];

        if($pass != "")
        {
            $this->error("您已经审核过了",'admin/store/index');
        }
        else{
            
            $data = [
            'pass' => 1
            ];
            $result = db('commodity')->where("id={$id}")->update($data);
            $this->success("审核通过!",'/admin/store/index');
        }
    }

//下架商品并提醒商户
    public function delete()
    {
    	$goods_id = input('get.id');
    	$store_id = input('get.store_id');
    	$goods_name = input('get.goods_name');

        $data = [
            'pass' => '2',
        ];

    	//商品下架
    	$result = db('commodity')->where("id='{$goods_id}'")->update($data);

    	//存入表中
    	$data = [
    		'id' => $goods_id,
    		'store_id' => $store_id,
    		'message_title'=>'商品下架通知',
    		'message'  => '您添加的商品'.$goods_name.'被下架',
    		'isread'   => '0',
            'addtime'  =>time()	
    	];
    	
    	$ret = db('storemessage')->insert($data);

    	$this->success("下架成功！",'/admin/store/index');
      	
    }
}
