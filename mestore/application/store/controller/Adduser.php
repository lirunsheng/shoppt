<?php
namespace app\store\controller;

class Adduser extends \think\Controller
{
    public function adduser()
    {
        $openid = $_GET['openid'];
        $nickName = $_GET['nickName']; //用户名称
        $avatarUrl = $_GET['avatarUrl'];//头像
        $time = time();

        $data = [
            'openid' =>$openid,
            'name' =>$nickName,
            'head_img' =>$avatarUrl,
            'addtime' => $time
        ];

        // dump($data);
        $ret = db('user')->where('openid',$openid)->find();
        if(!$ret){
            $ret=db('user')->insert($data);
        }
        
        return 1;
    }

    public function reuser()
    {
        echo "string";
    }
}
