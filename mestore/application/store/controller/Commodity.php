<?php
namespace app\store\controller;

class commodity extends Common
{
    public function addcommodity()
    {
        return view("addcommodity");
    }

    public function commodity(){
    	$ret=db('commodity')->where('pass',1)->where('store_id',session('store_id'))->paginate(8);
        $page = $ret->render();
        $this->assign('commodity',$ret);
        $this->assign('page',$page);
    	return view("commodity");
    }

    public function search(){
    	$goods_name=input('post.goods_name');
    	$barcode=input("post.barcode");
    	if($goods_name==""){
    		$ret=db('commodity')->where('barcode',$barcode)->where('pass',1)->where('store_id',session('store_id'))->paginate(8);
    	}
    	if($barcode==""){
    		$ret=db('commodity')->where('goods_name like "%'.$goods_name.'%"')->where('pass',1)->where('store_id',session("store_id"))->paginate(8);
    	}
    	else{
    		$ret=db('commodity')->where('goods_name like "%'.$goods_name.'%" and barcode="'.$barcode.'"')->where('pass',1)->where('store_id',session("store_id"))->paginate(8);
    	}
        $page1=$ret->render();
    	$this->assign('commodity',$ret);
        $this->assign('page1',$page1);
    	$html=$this->fetch('get_commodity_list');
    	return $html;
    }

    public function upimg(){
        $file = request()->file('file');
        if($file){
            $info = $file->move(ROOT_PATH . 'public' . DS . 'storeFileUploads');
            if($info){
                $img_src='https://huazai233.picp.vip/storeFileUploads/'.$info->getSaveName();
                return json_encode($img_src,JSON_UNESCAPED_UNICODE);
            }
        }
        else{
            $this->error('请上传图片！');
        }
    }

    public function addcommoditycheck(){
        $barcode=input("post.barcode");
        $goods_name=input("post.goods_name");
        $goods_style=input("post.goods_style");
        $goods_price=input("post.goods_price");
        $goods_stork=input("post.goods_stork");
        $file=input("post.goods_file");

        $data=[
            'barcode'  =>  $barcode,
            'goods_name'=>$goods_name,
            'goods_style'=>$goods_style,
            'goods_price'=>$goods_price,
            'goods_stork'=>$goods_stork,
            'store_id'=>session("store_id"),
            'goods_file'=>$file,
            'pass'      =>  0
        ];

        $ret=db('commodity')->insert($data);
        if($ret==false){
            $this->error("操作失败！");
        }
        $this->success("添加成功！请等待审核","/store/commodity/addcommodity");
    }

    public function detail(){
        $commodity_id=input("post.commodity_id");
        $ret=db('commodity')->where('id',$commodity_id)->where('pass',1)->where('store_id',session('store_id'))->select();
        $this->assign('detail',$ret);
        $html=$this->fetch("commodity_detail");
        return $html;
    }

    public function updatecommodity(){
        $barcode=input("post.barcode");
        $goods_name=input("post.goods_name");
        $goods_style=input("post.goods_style");
        $goods_price=input("post.goods_price");
        $goods_stork=input("post.goods_stork");
        $file=input("post.goods_file");

        $data=[
            'barcode'  =>  $barcode,
            'goods_name'=>$goods_name,
            'goods_style'=>$goods_style,
            'goods_price'=>$goods_price,
            'goods_stork'=>$goods_stork,
            'store_id'=>session("store_id"),
            'goods_file'=>$file
        ];
        
        $ret=db('commodity')->where('barcode',$barcode)->where('pass',1)->update($data);
        if($ret==false){
            $this->error("操作失败！");
        }
        $this->success("修改成功！","/store/commodity/commodity");
    }

    public function del(){
        $barcode=input('get.barcode');
        $ret=db('commodity')->where("barcode",$barcode)->where('store_id',session('store_id'))->delete();
        $ret2=db('commodity')->where('pass',1)->where('store_id',session('store_id'))->paginate(8);
        $page = $ret2->render();
        $this->assign('commodity',$ret2);
        $this->assign('page',$page);
        return view("commodity");
    }
}
