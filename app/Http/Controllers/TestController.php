<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\UserModel;
class TestController extends Controller{
    function reg(Request $request){
        //echo '<pre>';print_r($_POST);echo '</pre>';
        $pass1=request()->input('pass1');
        $pass2=request()->input('pass2');
        //验证两次输入的密码
        if($pass1!=$pass2){
            echo "两次输入的密码不一致";die;
        }
        $user_name=request()->input('user_name');
        $user_email=request()->input('user_email');
        //验证 用户名 email 是否已被注册
        $u=UserModel::where(['user_name'=>$user_name])->first();
        if($u){
            $response = [
                'error' => 500002,
                'msg' => '用户名已被使用'
            ];
            die(json_encode($response,JSON_UNESCAPED_UNICODE));
        }
        //验证email
        $u=UserModel::where(['user_email'=>$user_email])->first();
        if($u){
            $response = [
                'error' => 500003,
                'msg' => 'Email已被使用'
            ];
            die(json_encode($response,JSON_UNESCAPED_UNICODE)); 
        }
        //生成密码
        $user_pwd=password_hash($pass1,PASSWORD_BCRYPT);
        //入库
        $user_info = [
            'user_email' => $user_email,
            'user_name' => $user_name,
            'user_pwd' => $user_pwd
        ];
        $uid = UserModel::insertGetId($user_info);
        if($uid){
            $response = [
                'error' => 0,
                'msg' => 'ok'
            ];
        }else{
            $response = [
                'error' => 500001,
                'msg' => '服务器内部错误,请稍后再试'
            ];
        }
        die(json_encode($response));
    }

    function login(Request $request){
        $value=request()->input('user_name');
        $user_pwd=request()->input('user_pwd');
        //按name找记录
        $u1=UserModel::where(['user_name'=>$value])->first();
        $u2=UserModel::where(['user_email'=>$value])->first();
        
        if($u1==NULL&&$u2==NULL){
            $response = [
                'error' => 400004,
                'msg' => '用户不存在'
            ];
            return $response;
        }
        if($u1){//使用用户名登陆
            if(password_verify($user_pwd,$u1->user_pwd)){
                $user_id=$u1->user_id; 
           }else{
                $response = [
                     'error' => 400003,
                     'msg' => 'password wrong'
                ];
                return $response;
           }
        }
        if($u2){//使用email登陆
            if(password_verify($user_pwd,$u2->user_pwd)){
                $user_id=$u2->user_id; 
           }else{
                $response = [
                     'error' => 400003,
                     'msg' => 'password wrong'
                ];
                return $response;
           }
        }
        $token=$this->getToken($user_id);
        $response = [
            'error' => 0,
            'msg' => 'ok',
            'data' => [
                'user_id' => $user_id,
                'token' => $token
            ]
        ];
        return $response;
    }

    //生成用户token
    protected function getToken($uid){
        $token=md5(time().mt_rand(11111,99999).$uid);
        return substr($token,5,20);
    }

    //获取用户信息接口
    function userInfo(){
        echo '<pre>';print_r($_GET);echo '</pre>';
    }



}
 