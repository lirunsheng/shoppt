<?php
namespace app\admin\controller;
use \think\Validate;
use \think\Db;

class Store extends \think\Controller
{

    public function index()
    {
        $alist = db('store')->order('addtime desc')->paginate(3);
    	// $alist = db('store')->where("status=1")->paginate(2);  //只列出审核通过的
        $pageHtml = $alist->render();

        $this->assign('alist',$alist);
        $this->assign('pageHtml',$pageHtml);
        return view("store");
    }

    public function check()   //审核
    {
        $id = input('get.id');

        $result = db('store')->where("id={$id}")->find();
        $this->assign('result',$result);
        $pic=json_decode($result['file']);
        $this->assign("pic",$pic);
        return view("storecheck");
    }

    public function insert()   //审核是否通过
    {
        $id = input('get.id');
        // $status = input('post.id');
        $result = db('store')->where("id={$id}")->find();
    
        $ret = $result['status'];
        // var_dump($ret);die;
        if($ret != "")
        {
            $this->error("您已经审核过了",'/admin/store/index');
        }
        else{
            
            $data = [
            'status' => 1
            ];
            $result = db('store')->where("id={$id}")->update($data);
            $this->success("审核通过!",'/admin/store/index');
        }
        
    }

    //修改图片
    public function alist()
    {
        //使用原生sql语句查询最新提交的信息
        
        $sql = "select * from `mestore_log` as a where not exists (select 1 from `mestore_log` as b where b.operator=a.operator and b.id > a.id ) and log=0";
        $alist = Db::query($sql);
        
        $this->assign('alist',$alist);

        return view('alist');
    }

    public function listen()
    {
        $id = input('get.id');
        
        $result = db('log')->where("id={$id}")->find();
        $this->assign('result',$result);

        $file=json_decode($result['file']);
        $this->assign("file",$file);


        return view('storelisten');
    }

    public function manage()
    {   
        $id = input('post.id');

        //获取log里面的商户id值,找到对应要改的数据
        $store_id = input('post.operator');
        $store_imgs=$_POST['store_imgs'];
        $store_imgs=json_encode($store_imgs,JSON_UNESCAPED_UNICODE);
        $store_license=$_POST['store_license'];

        $data=[
            'id'  =>  $store_id,          
            'file'    =>  $store_imgs,
            'License'   =>  $store_license,         
        ];

        $ret = db('store')->where("id={$store_id}")->update($data);

        //审核成功后将log改为1
        
        $array = [
            'id' => $id,
            'log'=> '1'
        ];
        $alist = db('log')->where("id={$id}")->update($array);


        // $this->success("审核成功",'/admin/store/index');
        header('Location:https://huazai233.picp.vip/admin'); 


    }


    public function find()     //查找
    {
        $status = input('post.status');
        if(is_numeric($status)) 
         {
            $result = db('store')->where("status={$status}")->select();

            $this->assign('result',$result);

            if( !empty($result))
            {
                // var_dump($result);die;

                return view("storelist");
            }
            $this->error("不存在此状态");
         }

        else $this->error("请输入正整数");
    }

    public function edit()   //编辑
    {
        $id = input('get.id');

        $result = db('store')->where("id={$id}")->find();
        $this->assign('result',$result);
        return view('storeedit');
    }

    public function update()  //更新
    {
        $id = input('post.id');
        $store_name = input('post.store_name');
        $owner_name = input('post.owner_name');
        $owner_ID   = input('post.owner_ID');
        $store_tell = input('post.store_tell');
        $description = input('post.description');
        $store_address = input('post.store_address');

            //控制器验证
        $rule = [
        'store_name'  => 'require|min:2', 
                    
        ];

        $msg = [
            'store_name.require' => '店铺名称必须填写',
            'store_name.length'  => '店铺名称长度至少是2位',
            
        ];


        $data = [
            'store_name' => $store_name,
            'owner_name' => $owner_name, 
            'owner_ID'  => $owner_ID,
            'store_tell'=> $store_tell,
            'description'=> $description,
            'store_address' => $store_address                 
        ];

        $validate = new Validate($rule, $msg);
        $result   = $validate->check($data);
        if( !$result)
        {
            $this->error($validate->getError());
        }
        

        $ret = db('store')->where("id={$id}")->update($data);
        if( $ret == false )
        {
            $this->error("修改失败!");
        }
        $this->success("修改成功!",'/admin/store/index');

    }

    public function del()   //删除
    {
        $id = input('get.id');

        $ret  = db('store')->where("id",$id)->delete();
        $ret2 = db('log')->where("operator",$id)->delete();
        $ret3 = db('commodity')->where("store_id",$id)->delete();
        $ret4 = db('order')->where("store_id",$id)->delete();
        if( $ret == 0 )
        {
            $this->error("删除失败!");
        }
        $this->success("删除成功!",'/admin/store/index');
    }

    public function refuse()
    {
        $id = input('get.id');

        $ret = db('store')->where("id={$id}")->find();
        $status = $ret['status'];
        // var_dump($status);die;

        if( $status != ""){
            $this->error("已审核，不能再更改！",'/admin/store/index');
        }

        $data = [
            'id' => $id,
            'status'   => '2'  //拒绝状态
        ];

        $result = db('store')->where("id={$id}")->update($data);
        $this->success("成功拒绝！",'/admin/store/index');
        
    }

}
