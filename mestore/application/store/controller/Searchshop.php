<?php
namespace app\store\controller;

class Searchshop extends \think\Controller
{
    public function search()   //查询有该商品相关信息的商店
    {
        $key = $_GET['searchWords'];

        // dump($key);
        // dump($data);
        //------------模糊查询--------------
        // $rs=Db::name('school')->where($type,'like',"%{$key}%")->order('id desc')->limit($limit)->page($page)->select();


        $ret = db('commodity')->alias('a')->join('store b','a.store_id=b.id')->where('a.goods_name','like',"%{$key}%")->field('a.*,b.store_name,b.description,b.store_tell,b.store_address,b.store_logo,b.file,b.store_longitude,b.store_latitude')->select();

        return json_encode($ret);
    }

    public function showStore()   //查询商店相关信息和商品信息
    {
       $store_id = $_GET['store_id'];
       $ret = db('commodity')->alias('a')->join('store b','a.store_id=b.id')->where('a.store_id',$store_id)->field('a.*,b.store_name,b.description,b.store_tell,b.store_address,b.store_logo')->where('pass',1)->select();
       // $ret2 = 
       
       return json_encode($ret);
    }

    public function indexStore() //首页展示附近商店
    {
        $ret = db('store')->select();
        return json_encode($ret);
    }

}
