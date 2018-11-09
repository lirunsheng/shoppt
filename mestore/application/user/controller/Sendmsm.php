<?php
namespace app\user\controller;
use \think\Validate;
class Sendmsm extends \think\Controller
{
    public function sms()
    {
    	$strMobile = input('get.tell'); //tel 的 mobile 字段的内容
    	$data["tell"]=$strMobile;
    	$validate = validate('User');
    	if (!$validate->check($data)){
		     $res["msg"]=$validate->getError();
		     $res["status"]=false;
		     return json_encode($res);
		}
    	$appid=1400152723;
		$strAppKey = "b301b4433df9bb6bee05584016c8e18a"; //sdkappid 对应的 appkey，需要业务方高度保密
		$strRand = mt_rand(100000, 999999); //url 中的 random 字段的值
		$strTime = time();
		$sig="appkey={$strAppKey}&random={$strRand}&time={$strTime}&mobile={$strMobile}";
		$sig=hash("sha256",$sig);
    	$data=[
    		"params"=>[
    			$strRand
    		],
    		"sig"=>$sig,
    		"sign"=>"么么便利店",
		    "tel"=>[
		    	"mobile"=> $strMobile,
		        "nationcode"=> "86"
		    ],
		    "time"=>$strTime,
		    "tpl_id"=>211341
    	];
    	$data=json_encode($data);
    	$api="https://yun.tim.qq.com/v5/tlssmssvr/sendsms?sdkappid={$appid}&random={$strRand}";
    	$ret = post($api, $data);
    	$res["tell"]=$strMobile;
    	$res["status"]=true;
        $res["code"]=$strRand;
    	return json_encode($res);
    }
}
