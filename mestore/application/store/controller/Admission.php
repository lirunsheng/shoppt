<?php
namespace app\store\controller;

class Admission extends \think\Controller
{
	public function index(){
        return view('admission');
    }

    public function agreement(){
        return view('agreement');
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

    public function istellexist(){
        $tell=input("post.store_tell");
        $ret=db('store')->where("store_tell",$tell)->find();
        echo $ret['store_tell'];
    }

    public function isnameexist(){
        $name=input("post.store_name");
        $ret=db('store')->where("store_name",$name)->find();
        echo $ret['store_name'];
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

    public function add(){
        $store_name=input("post.store_name");
        $owner_name=input("post.owner_name");
        $owner_ID=input("post.owner_ID");
        @$store_imgs=$_POST['store_imgs'];
        @$store_imgs=json_encode($store_imgs,JSON_UNESCAPED_UNICODE);
        @$store_license=$_POST['store_license'];
        $store_tell=input("post.store_tell");
        $description=input("post.desc");
        $store_address=input("post.store_address");
        $pwd=input("post.pwd");
        
        //获取经纬度
        $address=input('post.store_address');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://apis.map.qq.com/ws/geocoder/v1/?address={$address}&key=ZBSBZ-72C2X-VXD4G-ZDI35-THPGO-JFFL2");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($ch);
        $map = json_decode($data);
        $location = $map->result->location;
        
        $data=[
            'store_name'        =>   $store_name,
            'store_logo'        =>   '/logo.png',
            'owner_name'  =>   $owner_name,
            'owner_ID'    =>   $owner_ID,
            'store_tell'        =>   $store_tell,
            'pwd'         =>   md5($pwd),
            'description' =>   $description,
            'store_address'     =>   $store_address,
            'file'        =>   $store_imgs,
            'License'     =>   $store_license,
            'addtime'     =>   time(),
            'status'      =>   0,
            'store_longitude'  =>  $location->lng,
            'store_latitude'   =>  $location->lat
        ];
        $ret=db('store')->insert($data);
        if($ret==false){
            $this->error("操作失败！");
        }
        $this->success("请等待审核！","/store/login/index");
    }
}
