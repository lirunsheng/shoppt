<?php
namespace app\store\controller;

class Index extends Common
{
    public function index()
    {
        $ret=db('store')->where('id',session("store_id"))->find();
        $this->assign('store_id',$ret);
        return view("index");
    }
    public function listenorder(){
    	$ret=db('order')->where('status','待发货')->where('store_id',session('store_id'))->count();
    	echo $ret;
    }

    public function listenmessage(){
        $ret=db('storemessage')->where('isread',0)->where('store_id',session('store_id'))->count();
        echo $ret;
    }

    public function uplogo(){
        $file = request()->file('file');
        if($file){
            $info = $file->move(ROOT_PATH . 'public' . DS . 'storeLogos');
            if($info){
                $img_src='/storeLogos/'.$info->getSaveName();
                $update=['store_logo'=>$img_src];
                $ret=db('store')->where('id',session("store_id"))->update($update);
                return json_encode($img_src,JSON_UNESCAPED_UNICODE);
            }
        }
        else{
            $this->error('请上传图片！');
        }
    }
}
