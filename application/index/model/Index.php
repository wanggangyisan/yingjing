<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/17
 * Time: 15:45
 */

namespace app\index\model;


use think\Db;
use think\Model;

class Index extends Model
{
    /*把数据保存到数据库*/
    public function upload($arr)
    {
        /*数据保存入库并返回当前数据的ID*/
        $data = Db::name('file_info')->insertGetId($arr);
        return $data;
    }
    /*获取分页数据或者查询数据*/
    public function getAll()
    {
        $data = Db::name('file_info')->page(1,20)->select();
        return $data;
    }
}