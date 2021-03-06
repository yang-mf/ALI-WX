<?php

namespace App\Http\Controllers\WX;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WXController extends Controller
{
    protected $access_token;

    public function __construct()
    {
        $this->getaccess_token();
    }

    public function getaccess_token(){
        $url='https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.env('appid').'&secret='.env('secret');
        $data_json=file_get_contents($url);
        $arr=json_decode($data_json,true);
        return $arr['access_token'];
    }



    public function info()
    {
        phpinfo();
    }



    public function wx(){
        $token = '2259b56f5898cd6192c50d338723d9e4';       //开发提前设置好的 token
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        $echostr = $_GET["echostr"];

        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        if( $tmpStr == $signature ){        //验证通过
            echo $echostr;
        }else{
            die("not ok");
        }
    }
    public function receiv(){
        $log_file="wx_log";
        //将接收的数据记录到日志文件
        $xml_str=file_get_contents("php://input");
        $data=date('Y-m-d H:i:s').$xml_str;
        file_put_contents($log_file,$data,FILE_APPEND);//追加写

        $xml_obj=simplexml_load_string($xml_str);
//        dd($xml_obj);
        $event=$xml_obj->Event;
        if($event=='subscribe'){
            $openid=$xml_obj->FromUserName;  //获取用户的openid
            //获取用户信息
            $url='https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$this->access_token.'&openid='.$openid.'&lang=zh_CN';
            $user_info=file_get_contents($url);
            file_put_contents('wx_user.log',$user_info,FILE_APPEND);
        }

        //判断消息类型
        $msg_type = $xml_obj->MsgType;

        $touser=$xml_obj->FromUserName;         //接收消息的用户的id
        $formuser=$xml_obj->ToUserName;         //开发者公众号的ID
        $time=time();
        $content=date('Y-m-d H:i:s').$xml_obj->Content;




        if($msg_type=='text'){
            $response_text='<xml>
  <ToUserName><![CDATA['.$touser.']]></ToUserName>
  <FromUserName><![CDATA['.$formuser.']]></FromUserName>
  <CreateTime>'.$time.'</CreateTime>
  <MsgType><![CDATA[event]]></MsgType>
  <Event><![CDATA['.$content.']]></Event>
</xml>';
            echo $response_text;        //回复用户消息
        }



    }




    /*
     * 获取用户的信息
     * */
    public function GetUserInfo()
    {
        $info = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.config('access_token').'&openid=OPENID&lang=zh_CN';
    }

}
