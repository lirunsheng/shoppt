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

    public function update(){
    	$store_tell=input("post.store_tell");
    	$store_desc=input("post.desc");
    	$store_address=input("post.store_address");
    	$data=[
    		'store_tell'    =>  $store_tell,
    		'description'   =>  $store_desc,
    		'store_address' =>  $store_address,
    		'status'        =>  0
    	];
    	$ret=db('store')->where("id",session("store_id"))->update($data);
    	header('Location:http://www.mestore.org/store/manage');
    }

    public function updatepic(){
        @$store_imgs=$_POST['store_imgs'];
        @$store_imgs=json_encode($store_imgs,JSON_UNESCAPED_UNICODE);
        @$store_license=$_POST['store_license'];
        $data=[
            'file'     =>  $store_imgs,
            'License'  =>  $store_license,
            'status'   =>  0
        ];
        $ret=db('store')->where("id",session("store_id"))->update($data);
        header('Location:http://www.mestore.org/store/manage/managepic');
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
        header('Location:http://www.mestore.org');
    }
}
