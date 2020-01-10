<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{

    function req()
    {
    	// $url = "http://server.1905.com/goods?id=8888";
    	// $response = file_get_contents($url);
    	// echo $response;
        echo '<pre>';print_r($_GET);echo '</pre>'; 
    }

    function encrypt(){
    	$data=$_GET['data'];
    	echo "原文: ".$data;echo '</br>';
    	$method ='AES-256-CBC';
    	$key='1905api';
    	$iv='WUSD8796IDjhkchd';
    	$enc_data=openssl_encrypt($data, $method, $key,OPENSSL_RAW_DATA,$iv);
    	echo "加密后密文: ".$enc_data;echo '</br>';
    	echo '<hr>';
    	echo '解密: ';echo '</br>';
        //发送加密数据
        $url="http://server.1905.com/decrypt?data=".urlencode(base64_encode($enc_data));
        echo $url;echo '</br>';
        $response=file_get_contents($url);
        echo $response;
        
    	//解密
    	// $dec_data = openssl_decrypt($enc_data, $method, $key,OPENSSL_RAW_DATA,$iv);
    	// echo $dec_data;
    }

    function encrypt2(){
        $data=[
            'name' => 'liuwei',
            'email' => '2841732297@qq.com',
            'age' => 17
        ];

        echo '<pre>';print_r($data);echo '</pre>';
        $json_str=json_encode($data);
        echo "原文: ".$json_str;echo '</br>';

        //加密
        $method ='AES-256-CBC';
        $key='1905api';
        $iv='WUSD8796IDjhkchd';

        $enc_data=openssl_encrypt($json_str, $method, $key,OPENSSL_RAW_DATA,$iv);
        echo "加密后密文: ".$enc_data;echo '</br>';
        
        //base64encode 密文
        $base64_str = base64_encode($enc_data);
        echo "base64_str: ".$base64_str;echo '</br>';

        //url_encode
        $url_encode_str = urlencode($base64_str);
        echo '$url_encode_str : '.$url_encode_str;echo '</br>';

        //发送加密数据
        $url="http://server.1905.com/decrypt2?data=".$url_encode_str;
        echo $url;echo '</br>';
        $response=file_get_contents($url);
        echo $response;
    }

    function rsa1(){
        $priv_key=file_get_contents(storage_path('keys/priv.key'));
        echo $priv_key;echo '<hr>';

        $data="hello world";
        echo "待加密数据: ".$data;echo '</br>';

        openssl_private_encrypt($data, $enc_data,$priv_key);
        var_dump($enc_data);
        echo '<hr>';
        //将密文发送至对方
        $base64_encode_str=base64_encode($enc_data);  //密文经 base64 编码
        echo $base64_encode_str;echo '<hr>';
        $url='http://server.1905.com/rsadescypt1?data='.urlencode($base64_encode_str);

        echo $url;die;
        file_get_contents($url);  //发送请求

        //解密
        // $pub_key=file_get_contents(storage_path('keys/pub.key'));
        // openssl_public_decrypt($enc_data, $dec_data, $pub_key);
        // echo "解密数据: ".$dec_data;

    }

    function curl1(){
        $url='http://server.1905.com/test/curl1?name=liuwei&email=liuwei@qq.com';
        echo $url;echo '</br>';
        //初始化
        $ch=curl_init();
        //设置参数
        curl_setopt($ch, CURLOPT_URL, $url);
        //执行会话 释放资源
        curl_exec($ch);
        curl_close($ch);
    }

    function curl2(){
        $url='http://server.1905.com/test/curl2';
        $data=[
            'name'=>'liuwei',
            'email'=>'liuwei@qq.com'
        ];
        echo $url;echo '</br>';

        $ch=curl_init();
        
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        curl_exec($ch);
        curl_close($ch);
    }

    function curl3(){
        $url='http://server.1905.com/test/curl3';
        $data=[
            'img1'=>new \CURLFile('gsl.jpg')
        ];
        //echo $url;echo '</br>';
        //初始化
        $ch=curl_init();
        //设置参数
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        //执行会话 释放资源
        curl_exec($ch);
        curl_close($ch);
    }

    function curl4(){
        $url='http://server.1905.com/test/curl4';
        $token=Str::random(20);
        $data=[
            'name'=>'liuwei',
            'email'=>'liuwei@qq.com',
            'age'=>17
        ];
        $json_str=json_encode($data);
        echo "待发送数据 json: ".$json_str;
        //初始化
        $ch=curl_init();
        //设置参数
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json_str);
        curl_setopt($ch, CURLOPT_HTTPHEADER,[
            'Content-Type:text/plain',
            'token: '.$token
            ]);
        //执行会话 释放资源
        curl_exec($ch);
        curl_close($ch);
    }

    function sign1(){
        $params = [
            'name' =>"liuwei",
            'email' =>"liuwei@qq.com",
            'amount'=>5000,
            'data'=>time()
        ];

        echo '<pre>';print_r($params);echo '</pre>';
        //将参数字典序排序
        ksort($params);
        echo '<pre>';print_r($params);echo '</pre>';echo '<hr>';
        //拼接字符串
        $str="";
        foreach($params as $k=>$v){
            $str .= $k . '=' . $v . '&';
        }
        $str=rtrim($str,'&');
        echo $str;echo '<hr>';

        //使用 私钥进行签名
        $priv_key=file_get_contents(storage_path('keys/priv.key'));
        openssl_sign($str,$signature,$priv_key,OPENSSL_ALGO_SHA256);
        //echo openssl_error_string();die;
        var_dump($signature);
        echo '</br>';
        //base64编码签名
        $sign =base64_encode($signature);
        echo '<hr>';
        echo "签名: ".$sign;echo '<hr>';
        $url="http://api.1905.com/sign1?".$str.'&sign='. urlencode($sign);
        echo $url;

    }

    function sign2(){
        $sign_token='abcdefg';
        $params = [
            'order_id'=>mt_rand(111111,999999),
            'amount'=>9999,
            'uid'=>100,
            'data'=>time()
        ];
        //字典序排序
        ksort($params);
        //拼接字符串
        $str="";
        foreach($params as $k=>$v){
            $str .= $k . '=' . $v . '&';
        }
        $str=rtrim($str,'&');
        echo $str;

        //计算签名
        $tmp_str=$str . $sign_token;
        echo '</br>';
        echo $tmp_str;echo '</br>';
        $sign=sha1($tmp_str);
        echo "签名结果: ".$sign;
        echo '</br>';
        $url = "http://api.1905.com/test?".$str.'&sign='.$sign;
        echo $url;echo '</br>';

    }



}
 