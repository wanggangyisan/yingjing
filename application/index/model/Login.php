<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/31
 * Time: 22:47
 */

namespace app\index\model;


use think\Db;
use think\Model;

class Login extends Model
{
    /*接收创建用户数据*/
    public function create_data($arr)
    {
        $data = Db::name("user")->insert($arr);
        return $data;
    }
    /*查询用户是否存在*/
    public function find_user($username,$password)
    {
        /*判断用户是否正确*/
        $res = Db::name("user")->where("username='".$username."'")->find();
        if(empty($res)){
            return array("code"=>2,"msg"=>"用户名错误或者不存");
        }
        $data = Db::name("user")->where("password='".$password."' and username='".$username."'")->find();
        if(empty($data)){
            return array("code"=>2,"msg"=>"密码输入错误");
        }
        return array("code"=>1,"msg"=>"登录成功");
    }
}