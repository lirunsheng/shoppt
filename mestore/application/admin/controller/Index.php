<?php
namespace app\admin\controller;
use \think\Db;

class Index extends \think\Controller
{
	//初始化方法 相当于__construct()
	public function _initialize()
    {
        //验证登录代码
        if ( session('admin_id') == NULL )
        {
        	$this->error("未登录",'/admin/login/index');
        }
    }

    public function destroy(){
        session("admin_id",null);
        $this->success("注销成功",'/admin/login/index');
    }

    public function index()
    {
        return view("index");
    }

    public function listen()
    {

        //查找log数据库中不同店铺个数
        $sql = "select * from `mestore_log` as a where not exists (select 1 from `mestore_log` as b where b.operator=a.operator and b.id > a.id) and log = 0";
        $alist = Db::query($sql);
        

        echo count($alist);
    }

    public function control()
    {
        //查找store数据库中未审核的店铺数
        $ret = db('store')->where("status=0")->count();
        echo $ret;
        
    }

    public function wait()
    {
        $ret = db("commodity")->where("pass=0")->count();
        echo $ret;
    }


}
