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
            $res = Db::name('file_info')->where("filename",$arr['filename'])->update($arr);
        }else{
            /*数据保存入库并返回当前数据的ID*/
            $res = Db::name('file_info')->insertGetId($arr);
        }
        return $res;
    }
    /*获取分页数据或者查询数据  $index_page 当前页  $total_page每页显示条数*/
    public function getAll($index_page,$total_page)
    {
        $data = Db::name('file_info')
            ->field('id,filename,title,upload_time')
            ->where('status',0)
            ->page($index_page,$total_page)
            ->order('id desc')
            ->select();
        $count = Db::name("file_info")->where("status",0)->count();
        return array('data'=>$data,'count'=>$count);
    }
    /*条件查询*/
    public function search_data($index_page,$total_page,$search)
    {
        /*条件查询数据*/
        $data = Db::name('file_info')
                ->field('id,filename,title,upload_time')
                ->where('status',0)
                ->where("title like '%".$search."%'")
                ->page($index_page,$total_page)
                ->order('id desc')
                ->select();
        /*计算查询到的条数*/
        $count = Db::name("file_info")
                ->where('status',0)
                ->where("title like '%".$search."%'")
                ->count();
        return array('data'=>$data,'count'=>$count);
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