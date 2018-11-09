<?php
namespace app\user\controller;

class Userinfo extends \think\Controller
{
    // function print_log(,$data){
    //     $file = fopen($url,"a");  
    //     fwrite($file,date('Y-m-d H:i:s').'：'.$data."\r\n");  
    //     fclose($file);    
    // }
    /**
 * 发送HTTP请求方法
 * @param  string $url 请求URL
 * @param  array $params 请求参数
 * @param  string $method 请求方法GET/POST
 * @return array  $data   响应数据
 */
    function http($url, $params, $method = 'GET', $header = array(), $multi = false)
    {
        $opts = array(
                CURLOPT_TIMEOUT        => 30,
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_HTTPHEADER     => $header
        );
        /* 根据请求类型设置特定参数 */
        switch(strtoupper($method)){
            case 'GET':
                $opts[CURLOPT_URL] = $url . '?' . http_build_query($params);
                break;
            case 'POST':
                //判断是否传输文件
                $params = $multi ? $params : http_build_query($params);
                $opts[CURLOPT_URL] = $url;
                $opts[CURLOPT_POST] = 1;
                $opts[CURLOPT_POSTFIELDS] = $params;
                break;
            default:
                throw new Exception('不支持的请求方式！');
        }
        /* 初始化并执行curl请求 */
        $ch = curl_init();
        curl_setopt_array($ch, $opts);
        $data  = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);
        if($error) throw new Exception('请求发生错误：' . $error);
        return  $data;
    }

    public function keycode(){  //获取openid和session_key
        $code = input('get.code');
        $appid = "wx727ac3b3f4439a25";
        $secret = "6e0e4ab0275ec68362c1cc75e4bfa408";
        $url = "https://api.weixin.qq.com/sns/jscode2session?appid=APPID&secret=SECRET&js_code=JSCODE&grant_type=authorization_code"; 
        $data['appid']=$appid;
        $data['secret']=$secret;
        $data['js_code']=$code;

        $curl = $this->http($url, $data, 'POST', array("Content-Type: application/x-www-form-urlencoded"));
        // dump($curl);
        return $curl;
    }

    public function index()
    {
        $opid=input('get.openid');
        $res=db('user')->where("openid='{$opid}'")->find();
        if($res["address"]==null || $res['address']=='[]'){
            $res["firaddres"]="";
        }
        else{
             $ret=json_decode($res["address"]);
             $res["firaddres"]=$ret[0];
        }

        return json_encode($res);
    }
}
