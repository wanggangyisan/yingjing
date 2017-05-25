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
        /*根据文件判断文章是否已经上传过、没有上传过直接保存、否则修改*/
        $article = Db::name("file_info")->where(array("filename"=>$arr['filename']))->find();
        if($article){
            /*根据文件名修改文章*/
            $res = Db::name('file_info')->where("id",$arr['filename'])->update($arr);
        }else{
            /*数据保存入库并返回当前数据的ID*/
            $res = Db::name('file_info')->insertGetId($arr);
        }
        return $res;
    }
    /*获取分页数据或者查询数据  $index+page 当前页  每页显示20条数据*/
    public function getAll($index_page)
    {
        $data = Db::name('file_info')
            ->field('id,filename,title,upload_time')
            ->where('status',0)
            ->page($index_page,20)
            ->order('id desc')
            ->select();
        return $data;
    }
    /*根据ID获取数据*/
    public function get_id($id)
    {
        $data = Db::name('file_info')->where('id',$id)->find();
        return $data;
    }
    /*修改数据*/
    public function save_data($data)
    {
        $res = Db::name('file_info')->where("id",$data['id'])->update($data);
        return $res;
    }
    /*删除文章 把文章的状态值修改为1*/
    public function delete_content($id)
    {
        $res = Db::name("file_info")->where("id",$id)->update(array("status"=>1));
        return $res;
    }
}