<?php
namespace app\admin\controller;
use \think\Validate;

class Users extends \think\Controller
{
	//初始化方法，相当于__construct()
	public function _initialize()
    {
    	//echo 'init<br/>';die;
         if ( session('admin_id') == NULL )
        {
        	$this->error("未登录",'admin/login/index');
        }

    }
	
    public function index()
    {
    	$alist = db('user')->order('addtime desc')->paginate(2);
    	$pageHtml = $alist->render();

    	$this->assign('alist',$alist);
    	$this->assign('pageHtml',$pageHtml);

        return view("users");
    }


    public function find()     //查找
    {

    	$name = input('post.user_name');

		$result = db('user')->where("name='{$name}'")->select();

		// $result = db('user')->where("openid",'like','%{$openid}%')->select();


		$this->assign('result',$result);
		if( !empty($result))
		{
			// var_dump($result);die;

			return view("list");
		}
		$this->error("不存在此用户");
    }

    public function edit()   //编辑
	{
		$id = input('get.id');

		$result = db('user')->where("id={$id}")->find();
		$this->assign('result',$result);
		return view('edit');
	}

	public function update()  //更新
	{
		$id = input('post.id');
		$name = input('post.name');
		$tell = input('post.tell');
		$openid = input('post.openid');
		$address = input('post.address');
		
			//控制器验证
		$rule = [
	    'name'  => 'require|min:2',	
		    	    
		];

		$msg = [
		    'name.require' => '用户名称必须填写',
		    'name.length'  => '用户名称长度至少是2位',
		    
		];


		$data = [
		    'name'  => $name,
		    'openid'=> $openid,	
		    'address' => $address
		    	    
		];

		$validate = new Validate($rule, $msg);
		$result   = $validate->check($data);
		if( !$result)
		{
			$this->error($validate->getError());
		}


		//文件上传验证
		$file = request()->file('head_img');
	    
	    // 移动到框架应用根目录/public/uploads/ 目录下
	    
	    if($file){
	        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
	        if($info){
	            // 成功上传后 获取上传信息
	            // 输出 jpg
	            $img_src = '/uploads/'.$info->getSaveName();
	            $data['head_img'] = $img_src;
	            // echo " <img src='{$img_src}'/>"; 
	        }else{
	            // 上传失败获取错误信息
	            $this->error($file->getError());
	        }
	    }
		

	    $ret = db('user')->where("id={$id}")->update($data);
	    if( $ret == false )
		{
			$this->error("修改失败!");
		}
		$this->success("修改成功!");

	}

	public function del()   //删除
	{
		$id = input('get.id');

		$ret = db('user')->where("id={$id}")->delete();
		if( $ret == false )
		{
			$this->error("删除失败!");
		}
		$this->success("删除成功!");
	}

	public function lookaddress(){
		$user_id=input('post.user_id');
		$ret=db('user')->where('id',$user_id)->field('address')->find();
		$data=$ret['address'];
		$data=json_decode($data,true);
		return $data;
	}
}
