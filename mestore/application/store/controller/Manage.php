<?php
namespace app\store\controller;

class Manage extends Common
{
    public function index()
    {
    	$ret=db('store')->where('id',session('store_id'))->find();
    	$this->assign("store",$ret);
        return view("manage");
    }

    public function managepic(){
        $ret=db('store')->where('id',session('store_id'))->find();
        $this->assign("store",$ret);
        $file=json_decode($ret['file']);
        $this->assign("file",$file);
        return view("managepic");
    }

    public function managepwd(){
        $ret=db("store")->where('id',session("store_id"))->find();
        $this->assign("store",$ret);
        return view("managepwd");
    }

    public function manageorder(){
        $ret=db('order')->where('store_id',session('store_id'))->order('addtime desc')->paginate(8);
        $page=$ret->render();
        $this->assign('order',$ret);
        $this->assign('page',$page);
        return view("manageorder");
    }

    public function upimg(){
        $file = request()->file('file');
        if($file){
            $info = $file->move(ROOT_PATH . 'public' . DS . 'storeFileUploads');
            if($info){
                $img_src='/storeFileUploads/'.$info->getSaveName();
                return json_encode($img_src,JSON_UNESCAPED_UNICODE);
            }
        }
        else{
            $this->error('请上传图片！');
        }
    }

    public function getaddress(){
        $address=input('post.store_address');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://apis.map.qq.com/ws/geocoder/v1/?address={$address}&key=ZBSBZ-72C2X-VXD4G-ZDI35-THPGO-JFFL2");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($ch);
        $map = json_decode($data);
        $location = $map->result->location;
        return "经度：".$location->lng."，纬度：".$location->lat;
    }
    
    public function update(){
    	$store_tell=input("post.store_tell");
    	$store_desc=input("post.desc");
    	$store_address=input("post.store_address");
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://apis.map.qq.com/ws/geocoder/v1/?address={$store_address}&key=ZBSBZ-72C2X-VXD4G-ZDI35-THPGO-JFFL2");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($ch);
        $map = json_decode($data);
        $location = $map->result->location;

    	$data=[
    		'store_tell'     =>  $store_tell,
    		'description'    =>  $store_desc,
    		'store_address'  =>  $store_address,
            'store_longitude'=>  $location->lng,
            'store_latitude' =>  $location->lat
    	];
        $ret=db('store')->where('id',session('store_id'))->update($data);
    	header('Location:https://huazai233.picp.vip/store/manage');
    }

   public function updatepic()
     {
        $store_id = session("store_id");

        //查找当前店铺名字
        $ab = db('store')->where("id",$store_id)->find();
        $store_name = $ab['store_name'];

        @$store_imgs=$_POST['store_imgs'];
        @$store_imgs=json_encode($store_imgs,JSON_UNESCAPED_UNICODE);
        @$store_license=$_POST['store_license'];
        
        $data=[
            'operator'  =>  $store_id,
            'store_name'=>  $store_name,
            'file'      =>  $store_imgs,
            'License'   =>  $store_license,
            'log' => '0'
        ];

        // $data=json_encode($data,JSON_UNESCAPED_UNICODE);
        

        $ret=db('log')->insert($data);

        $result = db('log')->where("License",$store_license)->find();      //查找当前修改数据
        $this->assign('result',$result);
 
        $file=json_decode($result['file']);
        $this->assign("file",$file);

        return view("checkpic");
    }

    public function updatepwd(){
        $oldpwd=input('post.oldpwd');
        $newpwd=input('post.newpwd');
        $confirmpwd=input('post.confirmpwd');
        $oldpwd=md5($oldpwd);
        $ret=db('store')->where('pwd',$oldpwd)->find();
        if($oldpwd!=$ret['pwd']){
            $this->error("原密码错误！","/store/manage/managepwd");
        }
        if($newpwd!=$confirmpwd){
            $this->error("密码与确认密码不一致！","/store/manage/managepwd");
        }
        $data=[
            'pwd' => md5($newpwd)
        ];
        $ret=db('store')->where('id',session('store_id'))->update($data);
        session("store_id",null);
        header('Location:https://huazai233.picp.vip/store');
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
    }

    public function searchorder(){
        $status=input('post.sel');
        $date=input('post.date');
        $date=strtotime($date);
        $nextdate=$date+86400;
        if($date==""){
            $ret=db('order')->where('status',$status)->where('store_id',session('store_id'))->paginate(8);
        }
        else if($status==""){
             $ret=db('order')->where('addtime between '.$date.' and '.$nextdate)->where('store_id',session('store_id'))->paginate(8);
        }
        else {
            $ret=db('order')->where('status',$status)->where('addtime between '.$date.' and '.$nextdate)->where('store_id',session('store_id'))->paginate(8);
        }
        $page=$ret->render();
        $this->assign('status',$ret);
        $this->assign('page',$page);
        $html=$this->fetch('manageorder_list');
        return $html;
    }

    public function afterorder(){
        $id=input('get.id');
        $update=[
            'status'=>'已完成'
        ];
        $ret=db('order')->where('id',$id)->update($update);
        header("Location:https://huazai233.picp.vip/store/manage/manageorder");
    }
}
