<?php
namespace app\index\controller;

use \app\index\model\Index as IndexModel;
class Index extends Base
{
    /*项目首页*/
    public function index()
    {
        //调用模型获取数据
        $model = new IndexModel();
        $data = $model->getAll(1,20);
        /*计算页数*/
        $total_page = ceil($data['count']/20);
        /*调用分页方法 当前页数 总页数  js点击的方法名*/
        $page = my_page(1,$total_page);
        /*数据渲染到HTML模板*/
        $this->assign('data',$data['data']);
        /*分页数据渲染到HTML模板*/
        $this->assign("page",$page);
        return $this->fetch();
    }
    /*ajax请求文章列表*/
    public function ajax_index()
    {
        /*获取ajax请求的数据*/
        $data = input();
        $model = new IndexModel();
        /*搜索条件为空时*/
        if(empty($data['search'])){
            $data = $model->getAll($data['page'],20);
            /*计算页数*/
            $total_page = ceil($data['count']/20);
            /*调用分页方法 当前页数 总页数  js点击的方法名*/
            $page = my_page(1,$total_page);
        }else{
            $data = $model->search_data($data['page'],20,$data['search']);
            /*计算页数*/
            $total_page = ceil($data['count']/20);
            /*调用分页方法 当前页数 总页数  js点击的方法名*/
            $page = my_page(1,$total_page);
        }

        /*循环遍历数据*/
        $arr_data = '';
        foreach ($data['data'] as $key => $va) {
            $arr_data .= '<tr class="poor_list" id="list'.$va['id'].'">
            <td>'.($key+1).'</td>
            <td>'.$va['filename'].'</td>
            <td><a href="/index/index/article_info/id/'.$va['id'].'">'.$va['title'].'</a></td>
            <td>'.date("Y-m-d M:s",$va['upload_time']).'</td>
            <td>
                <button class="edit_content btn-success" type="button" name="'.$va['id'].'">编辑</button>
                <button class="delete_content btn-danger" type="button" name="'.$va['id'].'">删除</button>
            </td>
        </tr>';
        }
        /*返回数据*/
        return array('data'=>$arr_data,'page'=>$page);
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
        if($data['error'] == 0){
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
                    $content = file_get_contents($move_path,777);
                    /*调用字符转换的方法*/
                    $replace_content = $this->replace_content(iconv('GBK','UTF-8//IGNORE',$content));

                    /*PHP保存为UTF-8无BOM编码，然后转换字符串编码为UTF-8，再查找*/
                    mb_convert_encoding($replace_content, 'utf-8', 'gbk');
                    if(mb_strpos($replace_content,"摘要")){
                        $appear_abstract = strpos($replace_content,"摘要");
                        /*截取摘要  mb_substr函数参数  字符串  开始位置  截取长度*/
                        $begin_abstract = mb_substr($replace_content,$appear_abstract,1000);
                        /*替换上标*/
                        $sup_abstract =  $this->preg_all_sup($begin_abstract);
                        /*替换下标*/
                        $abstract = $this->preg_all_sub($sup_abstract);
                    }else{
                        $abstract = '摘要为空';
                    }
                    /*查找关键词字符出现的位置*/
                    if(mb_strpos($replace_content,"关键词")){
                        $appear_keyword  = mb_strpos($replace_content,"关键词");
                        /*截取关键词,*/
                        $begin_keyWord = mb_substr($replace_content,$appear_keyword,300);
                        /*替换上标*/
                        $sup_keyWord = $this->preg_all_sup($begin_keyWord);
                        /*替换下标*/
                        $keyWord = $this->preg_all_sub($sup_keyWord);
                        $content = mb_substr($replace_content,$appear_keyword);
                        /*替换商标*/
                        $content = $this->preg_all_sup($content);
                        /*替换下标*/
                        $content = $this->preg_all_sub($content);
                    }else{
                        $keyWord = '';
                        $content = $replace_content;
                        /*替换商标*/
                        $content = $this->preg_all_sup($content);
                        /*替换下标*/
                        $content = $this->preg_all_sub($content);
                    }
                    /*接收标题*/
                    $title = input("title");

                    /*拼装数据保存道数据库*/
                    $arr = array(
                        'filename'  => $data['name'],/*原文件名*/
                        'title'     => $title,/*文章标题*/
                        'content'   => $content,/*文件内容*/
                        'keyword'   => $keyWord,/*关键词*/
                        'abstract'  => $abstract,/*摘要*/
                        'file_path' => $file_name,/*文件上传后保存的路径*/
                        'upload_time' => time(),/*文件上传的时间*/
                    );

                    /*实例化model*/
                    $Model = new \app\index\model\Index();
                    /*调用保存数据入库的方法保存数据*/
                    $res = $Model->upload($arr);
                    if($res != 0){
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
        }else{
            return array('code'=>2,'msg'=>'请选择文件！');
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
        $replace = str_replace('〓','',$str);
        if(strpos($replace,'〖SM(〗')|| strpos($replace,'〖HT5”〗')||strpos($replace,"[AM]")){
            $replace = str_replace('〖SM(〗','',$replace);
            $replace = str_replace('〖HT5”〗','',$replace);
            $replace = str_replace('[AM]','',$replace);
        }
        if(strpos($replace,'〖ZW(*〗')){
            $replace = str_replace('〖ZW(*〗','',$replace);
            $replace = str_replace('〖BFQ〗','',$replace);
        }
        /*抓取作者*/

        return $replace;
    }
    /*修改文章内容*/
    public function update_content()
    {
        /*根据ID查询文章内容*/
        $id = input("id");
        /*实例化模型*/
        $model = new IndexModel();
        $data = $model->get_id($id);
        $replace = str_replace('〓','&nbsp;',$data['content']);
        /*PHP保存为UTF-8无BOM编码，然后转换字符串编码为UTF-8，再查找*/
//        mb_convert_encoding($replace, 'utf-8', 'gbk');
//        var_dump(mb_strpos($replace,"关键"));
//        var_dump($replace);
        $this->assign('data',$data);
        return $this->fetch();
    }
    /*正则替换上标*/
    public function preg_all_sup($str)
    {
        /*寻找待替换内容 只带向上箭头的*/
        $begin_str = '/[0-9|0-9**]/';
        /*寻找待替换内容 带向上箭头和括号*/
        $brack = '/[0-9],[0-9]|[0-9**]/';
        /*替换后格式*/
        $end_str = '/<sup>[0-9]</sup>/';
        /*替换后内容 带向上箭头和括号*/
        $end_barck = '/<sup>[0-9],[0-9]|[0-9**]</sup>/';
        preg_replace($begin_str,$end_str,$str);
        preg_replace($brack,$end_barck,$str);
        return $str;
    }
    /*正则替换下标*/
    public function preg_all_sub($str)
    {
        /*寻找待替换内容 只带向下箭头的*/
        $begin_str = '/[0-9|0-9**]/';
        /*替换后格式*/
        $end_str = '/<sub>[0-9]</sub>/';
        preg_replace($begin_str,$end_str,$str);
        /*寻找待替换内容 带向下箭头和括号*/
        $brack = '/[0-9],[0-9]|[0-9**]/';
        /*替换后内容 带向下箭头和括号*/
        $end_barck = '/<sub>[0-9],[0-9]|[0-9**]</sub>/';
        preg_replace($brack,$end_barck,$str);
        return $str;
    }
    /*接收修改后内容*/
    public function get_content()
    {
        /*获取修改后的数据*/
        $data = input();
        /*实例化模型*/
        $model = new IndexModel();
        $res = $model->save_data($data);
        return $res;
    }
    /*删除文章*/
    public function delete_article()
    {
        /*获取文章id*/
        $id = input("id");
        $model = new IndexModel();
        /*调用删除文章方法*/
        $res = $model->delete_content($id);
        /*返回执行结果（因为是修改，返回的是受影响的行数）1*/
        return $res;
    }
    /*文章详情*/
    public function article_info()
    {
        /*根据ID查询文章内容*/
        $id = input("id");
        /*实例化模型*/
        $model = new IndexModel();
        $data = $model->get_id($id);
        $this->assign("data",$data);
        return $this->fetch();
    }
}