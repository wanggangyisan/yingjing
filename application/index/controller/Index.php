<?php
namespace app\index\controller;

use phpqrcode\QRcode;
use think\Controller;
use \app\index\model\Index as IndexModel;
class Index extends Controller
{
    /*项目首页*/
    public function index()
    {
        //调用模型获取数据
        $model = new IndexModel();
        $data = $model->getAll(1);
        $this->assign('data',$data);
        return $this->fetch();
    }
    /*文件上传页面*/
    public function form_test()
    {
        return $this->fetch();
    }
    /*接收文件上传的数据*/
    public function file_upload()
    {
        /*接收表单上传过来的所有数据*/
        $data = $_FILES['myfile'];
        /*判断文件是否上传*/
        if(!empty($data)){
            /*获取文件后缀名*/
            $type = explode('.',$data['name']);
//            return $type[1];
            if($type[1] =='txt' || $type[1] == 'fbd'){
                /*拼装文件移动后的名字*/
                $file_name = '/public/uploads/'.md5(time()).'.'.$type[1];
                $path = str_replace('\\','/',APP_ROOT_PATH);/*获取项目的根目录*/
                $move_path = $path.$file_name;
                /*吧文件移动到目标文件夹下*/
                $file = move_uploaded_file($data['tmp_name'],$move_path);
                if($file){
                    /*获取文件内容*/
                    $content = file_get_contents($move_path);
                    /*调用字符转换的方法*/
                    $replace_content = $this->replace_content(iconv('GBK','UTF-8//IGNORE',$content));
                    /*拼装数据保存道数据库*/
                    $arr = array(
                        'filename'  => $data['name'],/*原文件名*/
                        'title'     => '文章标题',/*文章标题*/
                        'content'   => $replace_content,/*文件内容,gbk格式编码转换成utf-8*/
                        'file_path' => $file_name,/*文件上传后保存的路径*/
                        'upload_time' => time(),/*文件上传的时间*/
                    );

                    /*实例化model*/
                    $Model = new \app\index\model\Index();
                    /*调用保存数据入库的方法保存数据*/
                    $res = $Model->upload($arr);
                    if($res){
                        return array('code'=>1,'msg'=>'文件上传成功！','_id'=>$res);
                    }else{
                        return array('code'=>2,'msg'=>'文件上传失败！');
                    }
                }else{
                    return array('code'=>2,'msg'=>'文件上传失败！');
                }
            }else{
                return array('code'=>2,'msg'=>'上传的文件只能是text格式或者fbd格式的');
            }
        }

    }
    /*字符转换*/
    public function replace_content($str)
    {
        $count = strlen($str);
        if(!$count){
            return $str;
        }
        /*〓替换成空格*/
        if(strpos('',$str)){
            $replace = str_replace('〓','&nbsp;',$str);
        }


    }
    /*修改文章内容*/
    public function update_content()
    {
        /*根据ID查询文章内容*/
        $id = input("file_id");
        /*实例化模型*/
        $model = new IndexModel();
        $data = $model->get_id($id);
        $this->assign('data',$data);
        return $this->fetch();
    }
    /*接收修改后内容*/
    public function get_content()
    {
        $data = input();
        return $data;
    }
}