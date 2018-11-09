<?php
namespace app\admin\controller;
use \think\Db;

class Money extends \think\Controller
{
    public function index()
    {
    	$result = db('store')->where("status",1)->select();    	
    	
    	$sql = "select  store_id   ,sum(order_total) as order_total   from   `mestore_order`   group   by  store_id";
    	$ret = Db::query($sql);    	

    	$list = [];
    	foreach( $ret as $v )
    	{
    		$list[$v['store_id']] = $v;
    	}
    	
    	for($j=0;$j<count($result);$j++)
		{
			$store_id = $result[$j]['id'];
			if ( isset( $list[$store_id] ) )
			{
				$result[$j]['order_total']= $list[$store_id]['order_total'];
			}
			else
			{
				$result[$j]['order_total']=0;
			}
		}
	
		$this->assign('result',$result);
    	
        return view("money");
    }
}

?>
